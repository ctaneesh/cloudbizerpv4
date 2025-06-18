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

class Sales_model extends CI_Model
{
    var $table = 'cberp_sales_orders';
    var $column_order = array(null, 'cberp_sales_orders.salesorder_number', 'cberp_sales_orders.total', 'cberp_sales_orders.items', 'cberp_sales_orders.invoicedate', 'cberp_sales_orders.status','cberp_customers.customer_id','cberp_customers.name', null);
    var $column_search = array('cberp_sales_orders.salesorder_number', 'cberp_sales_orders.total', 'cberp_sales_orders.items', 'cberp_sales_orders.invoicedate','cberp_sales_orders.status','cberp_customers.customer_id','cberp_customers.name');
    var $order = array('cberp_sales_orders.id' => 'desc');
    
    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($pid,$type)
    { 
        if($type == 'cberp_delivery_note_items'){
            $this->db->select('cberp_delivery_note_items.product_qty, cberp_delivery_note_items.product_price, cberp_delivery_notes.created_date' );
            // $this->db->select('cberp_delivery_note_items.*, cberp_delivery_notes.created_date, cberp_delivery_notes.created_time' );
            $this->db->from("cberp_delivery_note_items");
            $this->db->join('cberp_delivery_notes', 'cberp_delivery_note_items.delevery_note_id = cberp_delivery_notes.delevery_note_id');
            // if ($pid) $this->db->where('cberp_delivery_notes.id', $pid);
            if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
            {
                $this->db->where('DATE(cberp_delivery_notes.created_date) >=', datefordatabase($this->input->post('start_date')));
                $this->db->where('DATE(cberp_delivery_notes.created_date) <=', datefordatabase($this->input->post('end_date')));
            }
            
            // $this->db->where('cberp_delivery_notes.salesorder_number IS NOT NULL');
            // $pid = '18';
            $this->db->where('cberp_delivery_note_items.product_id',$pid);
            // $this->db->where('cberp_delivery_note_items.status = "Delivered" OR cberp_delivery_note_items.status = "Invoiced"');
        }
        elseif($type == 'cberp_delivery_return_items'){
            $this->db->select('cberp_delivery_return_items.approved_return_qty as product_qty, cberp_delivery_return_items.product_price, cberp_delivery_returns.created_date' );
            // $this->db->select('cberp_delivery_return_items.approved_return_qty as product_qty, cberp_delivery_return_items.product_price, cberp_delivery_returns.created_date, cberp_delivery_returns.created_time' );
            $this->db->from("cberp_delivery_return_items");
            $this->db->join('cberp_delivery_returns', 'cberp_delivery_return_items.delivery_return_number = cberp_delivery_returns.delivery_return_number');
            // if ($pid) $this->db->where('cberp_delivery_returns.id', $pid);
            if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
            {
                $this->db->where('DATE(cberp_delivery_returns.created_date) >=', datefordatabase($this->input->post('start_date')));
                $this->db->where('DATE(cberp_delivery_returns.created_date) <=', datefordatabase($this->input->post('end_date')));
            }
            
            $this->db->where('cberp_delivery_returns.salesorder_number IS NOT NULL');
          
            $this->db->where('cberp_delivery_return_items.product_id',$pid);
           
        }  
        elseif($type == 'purchase_items'){
            $this->db->select('cberp_purchase_order_items.quantity as product_qty, cberp_purchase_order_items.price as product_price , cberp_purchase_orders.created_date as created_date' );
            $this->db->from("cberp_purchase_order_items");
            $this->db->join('cberp_purchase_orders', 'cberp_purchase_order_items.purchase_number = cberp_purchase_orders.purchase_number');
           
            if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
            {
                $this->db->where('DATE(cberp_purchase_orders.created_date) >=', datefordatabase($this->input->post('start_date')));
                $this->db->where('DATE(cberp_purchase_orders.created_date) <=', datefordatabase($this->input->post('end_date')));
            }
          
            $this->db->where('cberp_purchase_order_items.product_code',$pid);
        }    
      
    }

    function get_datatables($pid,$type)
    {
        $this->_get_datatables_query($pid,$type);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
       
        return $query->result();
       
    }

    function count_filtered($eid,$type)
    {
        $this->_get_datatables_query($eid,$type);
    
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($eid,$type)
    {
        // $this->db->select('cberp_sales_orders.id');
        $this->_get_datatables_query($eid,$type);
        // $this->db->from($this->table);
        // if ($eid) $this->db->where('cberp_sales_orders.tid', $eid);
        return $this->db->count_all_results();
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

  

    public function salesorder_details($id)
    {

        $this->db->select('cberp_sales_orders.*,cberp_sales_orders.id AS iid,SUM(cberp_sales_orders.shipping + cberp_sales_orders.ship_tax) AS shipping,cberp_customers.*,cberp_sales_orders.loc as loc,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms');
        $this->db->from("cberp_sales_orders");
        $this->db->where('cberp_sales_orders.id', $id);
        //  if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_sales_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_sales_orders.loc', 0);
        // }
        $this->db->join('cberp_customers', 'cberp_sales_orders.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_sales_orders.term', 'left');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();

    }

    public function salesorder_products($id)
    {
        $this->db->select('cberp_sales_orders_items.*, cberp_products.onhand_quantity AS totalQty, cberp_products.alert_quantity');
        $this->db->from('cberp_sales_orders_items');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_sales_orders_items.pid', 'left');
        $this->db->where('tid', $id);
        $query = $this->db->get();

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
        $this->db->join('cberp_sales_orders_items', 'cberp_sales_orders_items.tid = cberp_sales_orders.id');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_sales_orders_items.pid');
        $this->db->join('cberp_quotes', 'cberp_quotes.id = cberp_sales_orders.quote_id');
        $this->db->join('cberp_customer_lead_items', 'cberp_customer_lead_items.tid = cberp_quotes.lead_id AND cberp_customer_lead_items.pid = cberp_sales_orders_items.pid', 'left');
        $this->db->where('cberp_sales_orders_items.tid', $salesorderid);
        $this->db->where('cberp_sales_orders_items.salesorder_number', $salesorder_number);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    function get_customers($start_date,$end_date,$customer)
    {
        // echo $customer; 
        $this->_get_customers_query($start_date,$end_date,$customer);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
       
        return $query->result();
       
    }
    function get_customers1($start_date,$end_date,$customer)
    {
        $this->_get_customers_query1($start_date,$end_date,$customer);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
       
        return $query->result();
       
    }

    private function _get_customers_query($start_date,$end_date,$customer)
    { 
            $this->db->distinct();
            $this->db->select('cberp_delivery_notes.customer_id, cberp_customers.name');
            $this->db->from('cberp_delivery_notes');
            $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_delivery_notes.customer_id','left');

            $this->db->where('DATE(cberp_delivery_notes.created_date) >=', datefordatabase($start_date));
            $this->db->where('DATE(cberp_delivery_notes.created_date) <=', datefordatabase($end_date));    
            if($customer!=''){
                $this->db->where('cberp_customers.customer_id', $customer);  
            }           
    }
    private function _get_customers_query1($start_date,$end_date,$customer)
    {   
            $this->db->distinct();
            $this->db->select('cberp_delivery_returns.customer_id, cberp_customers.name');
            $this->db->from('cberp_delivery_returns');
            $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_delivery_returns.customer_id','left');

            // $this->db->select('customer_id');
            // $this->db->from('cberp_delivery_returns');
            $this->db->where('DATE(cberp_delivery_returns.created_date) >=', datefordatabase($start_date));
            $this->db->where('DATE(cberp_delivery_returns.created_date) <=', datefordatabase($end_date));    
            if($customer!=''){
                $this->db->where('cberp_customers.customer_id', $customer);  
            }              
    }
    //erp2024 15-10-2024 ends

    public function customer_by_customer_id($id)
    {
        $this->db->select('name');
        $this->db->from('cberp_customers');
        $this->db->where('id',$id);

        $query = $this->db->get();
        // die($this->db->last_query());

        $result = $query->result_array();
        return $result[0];


    }
    public function customer_sale_by_customer_id($id,$start_date,$end_date)
    {
        
        $this->db->select('cberp_delivery_note_items.product_qty, cberp_delivery_notes.created_date, cberp_delivery_notes.customer_id, cberp_products.product_code, cberp_products.product_des, cberp_delivery_note_items.product_price as price, cberp_products.product_cost as cost' );
        $this->db->from("cberp_delivery_notes");
        $this->db->join('cberp_delivery_note_items', 'cberp_delivery_note_items.delevery_note_id = cberp_delivery_notes.delevery_note_id','left');
        $this->db->join('cberp_products', 'cberp_delivery_note_items.product_id = cberp_products.pid','left');

        $this->db->where('cberp_delivery_notes.customer_id',$id);
       
        $this->db->where('DATE(cberp_delivery_notes.created_date) >=', datefordatabase($start_date));
        $this->db->where('DATE(cberp_delivery_notes.created_date) <=', datefordatabase($end_date));
        $this->db->where('(cberp_delivery_note_items.status = "Delivered" OR cberp_delivery_note_items.status = "Invoiced")');    
        
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();
        
        return $result;
    }
    public function customer_sales_return_by_customer_id($id,$start_date,$end_date)
    {
        
        $this->db->select('cberp_delivery_return_items.approved_return_qty as product_qty, cberp_delivery_return_items.product_price as price, cberp_delivery_returns.created_date, cberp_delivery_returns.customer_id, cberp_products.product_code, cberp_products.product_des, cberp_products.product_cost as cost' );
        $this->db->from("cberp_delivery_returns");
        $this->db->join('cberp_delivery_return_items', 'cberp_delivery_return_items.delivery_return_number = cberp_delivery_returns.delivery_return_number','left');
        $this->db->join('cberp_products', 'cberp_delivery_return_items.product_id = cberp_products.pid','left');

        $this->db->where('cberp_delivery_returns.customer_id',$id);
       
        $this->db->where('DATE(cberp_delivery_returns.created_date) >=', datefordatabase($start_date));
        $this->db->where('DATE(cberp_delivery_returns.created_date) <=', datefordatabase($end_date));
        // $this->db->where('(cberp_delivery_return_items.status = "Delivered" OR cberp_delivery_return_items.status = "Invoiced")');    
        
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();
        
        return $result;
    }
    public function cust_details($custid)
    {
        $this->db->select('cberp_customers.customer_id,cberp_customers.name,users.lang');
        $this->db->from('cberp_customers');
        $this->db->join('users', 'users.cid=cberp_customers.customer_id', 'left');
        $this->db->where('cberp_customers.customer_id', $custid);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    //erp2024 01-01-2025 starts

    public function sale_by_date($start_date,$end_date)
    {
        
        $subquery = $this->db->select('MAX( delivery_note_detail_id ) AS latest_id')
                
                             ->from('cberp_delivery_note_items p')
                             ->join('cberp_delivery_notes o', 'o.delevery_note_id = p.delevery_note_id', 'inner') 
                             ->where('o.created_date >=', datefordatabase($start_date))
                             ->where('o.created_date <=', datefordatabase($end_date))
                             ->group_by('p.product_id')
                             ->get_compiled_select();

       
        $this->db->select('cberp_delivery_note_items.product_qty, cberp_delivery_notes.customer_id,cberp_delivery_notes.created_date,cberp_products.product_name,cberp_products.product_code,cberp_products.product_des,cberp_products.onhand_quantity,cberp_products.pid, cberp_products.product_cost as cost' );
        $this->db->from("cberp_delivery_notes");
        $this->db->join('cberp_delivery_note_items', 'cberp_delivery_note_items.delevery_note_id = cberp_delivery_notes.delevery_note_id','left'); 
        $this->db->join('cberp_products', 'cberp_delivery_note_items.product_id = cberp_products.pid','left');
        $this->db->join("($subquery) latest", 'cberp_delivery_note_items.delivery_note_detail_id = latest.latest_id', 'inner');  
        $this->db->where('DATE(cberp_delivery_notes.created_date) >=', datefordatabase($start_date));
        $this->db->where('DATE(cberp_delivery_notes.created_date) <=', datefordatabase($end_date));
        $this->db->where('(cberp_delivery_note_items.status = "Delivered")'); 
        $this->db->order_by('cberp_delivery_notes.created_date', 'DESC'); 

        $query = $this->db->get();
        $result = $query->result_array();   
        return $result;
    }

    public function purchase_by_date($start_date,$end_date)
    {
       $subquery = $this->db->select('MAX( p.id ) AS latest_id')
                
                             ->from('cberp_purchase_receipt_items p')
                             ->join('cberp_purchase_receipts o', 'o.purchase_reciept_number = p.purchase_reciept_number', 'inner') 
                             ->where('o.created_date >=', datefordatabase($start_date))
                             ->where('o.created_date <=', datefordatabase($end_date))
                             ->group_by('p.product_code')
                             ->get_compiled_select();
      
        $this->db->select('cberp_purchase_receipts.created_date as purchdate,cberp_purchase_receipt_items.product_quantity_recieved as purchqty,cberp_products.product_name,cberp_products.product_code,cberp_products.onhand_quantity, cberp_products.product_des,cberp_products.pid,cberp_products.product_cost as cost' );
        $this->db->from("cberp_purchase_receipts");
        $this->db->join('cberp_purchase_receipt_items', 'cberp_purchase_receipt_items.purchase_reciept_number = cberp_purchase_receipts.purchase_reciept_number','left');  
        $this->db->join('cberp_products', 'cberp_purchase_receipt_items.product_code = cberp_products.product_code','left');
        $this->db->join("($subquery) latest", 'cberp_purchase_receipt_items.id = latest.latest_id', 'inner');
        $this->db->where('DATE(cberp_purchase_receipts.created_date) <=', datefordatabase($end_date));
        $this->db->where('DATE(cberp_purchase_receipts.created_date) <=', datefordatabase($end_date));    
        $this->db->order_by('cberp_purchase_receipts.created_date', 'DESC'); 
  
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();     
        return $result;
    }

    public function get_monthly_salesreport($given_date)
    {
     
        $query = $this->db->query("
        SELECT 
            p.product_id AS product_id,c.product_code AS product_code,c.product_name AS product_name,c.qty AS onhand,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 11 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_1`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 10 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_2`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 9 MONTH), '%Y-%m') THEN  p.product_qty ELSE 0 END) AS `Month_3`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 8 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_4`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 7 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_5`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 6 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_6`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 5 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_7`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 4 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_8`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 3 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_9`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 2 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_10`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT(DATE_SUB('$given_date', INTERVAL 1 MONTH), '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_11`,
            SUM(CASE WHEN DATE_FORMAT(s.created_date, '%Y-%m') = DATE_FORMAT('$given_date', '%Y-%m') THEN p.product_qty ELSE 0 END) AS `Month_12`
        FROM cberp_delivery_notes s
        JOIN cberp_delivery_note_items p ON s.delevery_note_id = p.delevery_note_id
        JOIN cberp_products c ON p.product_id = c.pid
        WHERE s.created_date BETWEEN DATE_SUB('$given_date', INTERVAL 12 MONTH) AND '$given_date'
        GROUP BY p.product_id
        ORDER BY p.product_id
    ");
  
    return $query->result_array();
    }

}
