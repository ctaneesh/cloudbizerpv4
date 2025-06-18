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

class Deliverynote_model extends CI_Model
{
    var $table = 'cberp_delivery_notes';
    var $column_order = array(null, 'cberp_delivery_notes.delivery_note_number', 'cberp_delivery_notes.salesorder_number', 'cberp_delivery_notes.total_amount', 'cberp_delivery_notes.due_date','cberp_delivery_notes.created_date','cberp_customers.name','cberp_store.store_name','cberp_custom_data.data','cberp_delivery_notes.status', null);
    var $column_search = array('cberp_delivery_notes.delivery_note_number',  'cberp_delivery_notes.salesorder_number', 'cberp_delivery_notes.total_amount','cberp_delivery_notes.due_date','cberp_delivery_notes.created_date','cberp_customers.name','cberp_store.store_name','cberp_custom_data.data','cberp_delivery_notes.status');
    var $order = array('cberp_delivery_notes.delivery_note_date' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    private function _get_datatables_query($opt = '')
    {
        $filter_status = $this->input->post('filter_status');
        
        $filter_expiry_date_from = !empty($this->input->post('filter_expiry_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_from'))) : ""; 

        $filter_expiry_date_to = !empty($this->input->post('filter_expiry_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_to'))) : "";

        $filter_price_from = !empty($this->input->post('filter_price_from')) ? $this->input->post('filter_price_from') : 0;
        $filter_price_to = !empty($this->input->post('filter_price_to')) ? $this->input->post('filter_price_to'): 0;

        $filter_customer = !empty($this->input->post('filter_customer')) ?$this->input->post('filter_customer') : "";
        $salesorder_number = !empty($this->input->post('salesorder_number')) ?$this->input->post('salesorder_number') : "";
        $this->db->select('cberp_delivery_notes.delivery_note_number,cberp_delivery_notes.delivery_note_number, cberp_delivery_notes.salesorder_number, cberp_delivery_notes.total_amount, cberp_delivery_notes.created_date,cberp_delivery_notes.created_date,cberp_delivery_notes.customer_id, cberp_customers.name,cberp_customers.customer_id,cberp_custom_data.data,cberp_delivery_notes.status,cberp_store.store_name,cberp_delivery_notes.pick_ticket_status,cberp_delivery_notes.pick_item_recieved_status,cberp_delivery_notes.pick_item_recieved_note,cberp_delivery_notes.due_date');
        $this->db->from($this->table);
        $this->db->join('cberp_customers', 'cberp_delivery_notes.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_custom_data', 'cberp_custom_data.rid = cberp_customers.customer_id','left');
        $this->db->join('cberp_store', 'cberp_store.store_id = cberp_delivery_notes.store_id','left');
        // $this->db->where('cberp_custom_data.module', 1);
        if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
        {
            $this->db->where('DATE(cberp_delivery_notes.created_date) >=', datefordatabase($this->input->post('start_date')));
            $this->db->where('DATE(cberp_delivery_notes.created_date) <=', datefordatabase($this->input->post('end_date')));
        }
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

        if (!empty($filter_status)) {
            $this->db->where_in('cberp_delivery_notes.status', $filter_status);
        }
        if (!empty($filter_expiry_date_from) && !empty($filter_expiry_date_to)) {
            $this->db->where("cberp_delivery_notes.created_date BETWEEN '$filter_expiry_date_from' AND '$filter_expiry_date_to'");
        }

        if(!empty($filter_customer)){
            $this->db->where_in("cberp_delivery_notes.customer_id",$filter_customer);
        }

        if(!empty($salesorder_number)){
            $this->db->where_in("cberp_delivery_notes.salesorder_number",$salesorder_number);
        }

        if($filter_price_to > 0){
            $this->db->where("cberp_delivery_notes.total_amount BETWEEN $filter_price_from AND $filter_price_to");
        }
        // if (isset($_POST['order'])) // here order processing
        // {
        //     $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        // } 
        if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

    }

    function get_datatables($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($opt) {
            $this->db->where('delivery_note_number', $opt);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('cberp_delivery_notes.delivery_note_number');
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function productidFromdeliverynote($noteidid)
    {
        $this->db->select('product_id');
        $this->db->from('cberp_delivery_note_items');
        $this->db->where('delevery_note_id', $noteidid);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function customerByDeliverynoteid($delivery_note_number)
    {
        $this->db->select('cberp_customers.*,cberp_country.name as countryname');
        $this->db->from('cberp_customers');        
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_customers.country');
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function itemcountwithstatus($deliveryNoteId){
        // First SELECT query to count invoiced items
        $this->db->select('COUNT(cberp_delivery_note_items.delevery_note_id) AS invoiced_count');
        $this->db->from('cberp_delivery_note_items');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.delevery_note_id = cberp_delivery_note_items.delevery_note_id');
        $this->db->where('cberp_delivery_note_items.delevery_note_id', $deliveryNoteId);
        $this->db->where('cberp_delivery_note_items.status', 'Invoiced');
        $query1 = $this->db->get();
        $result1 = $query1->row()->invoiced_count;
    }
    public function itemcountwithoutstatus($deliveryNoteId){
        $this->db->select('COUNT(cberp_delivery_note_items.delevery_note_id) AS total_count');
        $this->db->from('cberp_delivery_note_items');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.delevery_note_id = cberp_delivery_note_items.delevery_note_id');
        $this->db->where('cberp_delivery_note_items.delevery_note_id', $deliveryNoteId);
        $query2 = $this->db->get();
        $result2 = $query2->row()->total_count;
    }

    //erp2024 function for delivery note by id 08-07-2024
    public function deliverynoteby_number($delivery_note_number)
    {
        $this->db->select('cberp_delivery_notes.*,cberp_delivery_notes.store_id as warehouseid,cberp_delivery_notes.delivery_note_number as delnotenumber,cberp_delivery_notes.reference as delnoterefer,cberp_delivery_notes.created_date as delnoteinvoicedate, cberp_delivery_notes.order_discount as delivery_order_discount,cberp_delivery_notes.order_discount_percentage as delivery_order_discount_percentage,cberp_customers.*,cberp_delivery_notes.status as notestatus,cberp_delivery_notes.note as notes,cberp_store.store_name AS warehousename');
        $this->db->from('cberp_delivery_notes');        
        $this->db->join('cberp_customers', 'cberp_delivery_notes.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_sales_orders', 'cberp_sales_orders.salesorder_number = cberp_delivery_notes.salesorder_number','left');
        $this->db->join('cberp_store', 'cberp_store.store_id = cberp_delivery_notes.store_id','left');
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->row_array();
    }
    public function deliverynotedetails_byid($id)
    {
        $this->db->select('cberp_delivery_notes.*');
        $this->db->from('cberp_delivery_notes');    
        $this->db->where('cberp_delivery_notes.delevery_note_id', $id);
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->row_array();
    }
    
    public function deliverynote_products($delivery_note_number)
    {
        
        $this->db->select('cberp_delivery_note_items.*,cberp_delivery_note_items.discount_type AS delnote_discounttype,cberp_delivery_note_items.subtotal AS deliverysubtotal, cberp_delivery_note_items.total_tax AS deliverytaxtotal,cberp_delivery_note_items.total_discount AS deliverytotaldiscount,cberp_delivery_note_items.quantity AS deliverynote_quantity,cberp_delivery_note_items.quantity AS product_qty, cberp_sales_orders_items.*, cberp_products.onhand_quantity AS totalQty,cberp_products.product_code AS product_code, cberp_product_description.product_name AS product_name, cberp_products.alert_quantity, cberp_products.product_code, cberp_products.unit,cberp_products.onhand_quantity AS onhandqty,         
        cberp_product_pricing.minimum_price as minprice,cberp_products.maximum_discount_rate as maximumdiscount,cberp_products.income_account_number,cberp_products.expense_account_number');
        $this->db->from('cberp_delivery_note_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_delivery_note_items.product_code');
      
        $this->db->join('cberp_sales_orders_items', 'cberp_sales_orders_items.salesorder_number = cberp_delivery_note_items.salesorder_number AND cberp_sales_orders_items.product_code = cberp_delivery_note_items.product_code', 'left');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code', 'left');
        $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code', 'left');
        $this->db->where('cberp_delivery_note_items.delivery_note_number', $delivery_note_number);
        // $this->db->group_by('cberp_delivery_note_items.delivery_note_number');
        $query = $this->db->get();      

        //  echo $this->db->last_query(); die(); 
        return $query->result_array();

    }
    public function deliverynote_products_for_return($delivery_note_number)
    {
        $this->db->select('cberp_delivery_note_items.*,cberp_delivery_note_items.quantity AS delivered_quantity,cberp_delivery_note_items.delivery_returned_quantity AS return_qty, cberp_products.product_code AS prdcode, cberp_product_description.product_name AS prdname, cberp_products.product_code,cberp_products.unit, cberp_product_description.product_name,cberp_products.income_account_number,cberp_products.expense_account_number');       
        
        $this->db->from('cberp_delivery_note_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_delivery_note_items.product_code', 'inner');
        $this->db->join('cberp_product_description', 'cberp_products.product_code = cberp_product_description.product_code', 'inner');
        $this->db->join('cberp_sales_orders_items', 'cberp_sales_orders_items.salesorder_number = cberp_delivery_note_items.salesorder_number AND cberp_sales_orders_items.product_code = cberp_delivery_note_items.product_code','left');
        $this->db->where('cberp_delivery_note_items.delivery_note_number', $delivery_note_number);
        // $this->db->group_by('cberp_delivery_note_items.delivery_note_detail_id');
        $query = $this->db->get();
        return $query->result_array();


    }
    
    public function last_delivery_return_number(){
        $prefix =  get_prefix_72();
        $this->db->select('delivery_return_number');
        $this->db->from('cberp_delivery_returns');
        $this->db->order_by('delivery_return_number', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result2 = $query->row_array();
        if ($query->num_rows() > 0) {
            $last_delivery_return_number = $query->row()->delivery_return_number;
            $parts = explode('/', $last_delivery_return_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $prefix['deliveryreturn_prefix'].$next_number;
        } else {
            return $prefix['deliveryreturn_prefix'].'1001';
        }

    }
    public function existingnotelist($deliverynote_id,$product_id){
        $this->db->select('cberp_delivery_note_items.product_qty');
        $this->db->from('cberp_delivery_note_items');        
        $this->db->where('cberp_delivery_note_items.delevery_note_id', $deliverynote_id);
        $this->db->where('cberp_delivery_note_items.product_id', $product_id);
        $this->db->limit(1);
        $query2 = $this->db->get();        
        $result2 =$query2->row_array();
        if ($result2) {
            return $result2['product_qty'];
        } else {
            return 0;
        }
    }
    public function productstockbyid($product_code){
        $this->db->select('cberp_products.onhand_quantity');
        $this->db->from('cberp_products');        
        $this->db->where('cberp_products.product_code', $product_code);
        $this->db->limit(1);
        $query2 = $this->db->get();        
        $result2 =$query2->row_array();
        if ($result2) {
            return $result2['onhand_quantity'];
        } else {
            return 0;
        }
    }
    public function warehouseprdstock_byprdid($product_code,$store_id){
        $this->db->select('cberp_product_to_store.stock_quantity');
        $this->db->from('cberp_product_to_store');        
        $this->db->where('cberp_product_to_store.product_code', $product_code);
        $this->db->where('cberp_product_to_store.store_id', $store_id);
        $this->db->limit(1);
        $query2 = $this->db->get();    
        // die($this->db->last_query());
        $result2 =$query2->row_array();
        if ($result2) {
            return $result2['stock_quantity'];
        } else {
            return 0;
        }
    }


    public function check_customer_is_same($selectedIds) {
        $this->db->select('DISTINCT(customer_id), store_id');
        $this->db->from('cberp_delivery_notes');
        $this->db->where_in('delevery_note_id', $selectedIds);
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();
    
        if (count($result) === 1) {
            return true; // All records have the same customer_id and store_id
        } else {
            return false; // Different customer_id or store_id found
        }
    }
    
 
    public function delivered_qty_update_to_delivery_note_items_table($delivery_note_number, $product_code, $return_qty) {
        $this->db->select('delivery_returned_quantity');
        $this->db->from('cberp_delivery_note_items');
        $this->db->where('cberp_delivery_note_items.delivery_note_number', $delivery_note_number);
        $this->db->where('cberp_delivery_note_items.product_code', $product_code);
        $query = $this->db->get();    
      
        $result = $query->row_array();
        if ($result) { 
            $current_ret_qty = intval($result['delivery_returned_quantity']);
        } else {
            $current_ret_qty = 0; 
        }
        
        $delivery_returned_qty = intval($current_ret_qty) + intval($return_qty);
        
        $this->db->set('delivery_returned_quantity', $delivery_returned_qty);
        $this->db->where('cberp_delivery_note_items.delivery_note_number', $delivery_note_number);
        $this->db->where('cberp_delivery_note_items.product_code', $product_code);
        $this->db->update('cberp_delivery_note_items');  
    }
    public function deliverynotedetails_byid_for_multiple($id)
    {
        $this->db->select('
            cberp_delivery_notes.store_id as store_id,
            cberp_delivery_notes.refer as refer, 
            cberp_delivery_notes.order_discount, 
            cberp_delivery_notes.term as term,
            cberp_delivery_notes.customer_id,
            cberp_delivery_notes.delnote_number as delnote_number,
            cberp_delivery_notes.delivery_note_number,
            cberp_delivery_notes.eid as employeeid,
            cberp_delivery_notes.transaction_number,
            cberp_delivery_note_items.delevery_note_id,
            cberp_delivery_note_items.salesorder_id,
            cberp_delivery_note_items.product_id,
            cberp_delivery_note_items.product,
            cberp_delivery_note_items.product_qty, 
            cberp_delivery_note_items.product_price,
            cberp_delivery_note_items.product_tax,
            cberp_delivery_note_items.product_discount,
            cberp_delivery_note_items.discount_type,
            cberp_delivery_note_items.product_code,
            cberp_delivery_note_items.subtotal,
            cberp_delivery_note_items.totaldiscount,
            cberp_delivery_note_items.totaltax,
            cberp_delivery_note_items.unit,
            cberp_delivery_note_items.salesorder_product_qty,
            cberp_delivery_note_items.delivery_returned_qty,
            cberp_delivery_note_items.discount_type AS delnote_discounttype,
            cberp_delivery_note_items.status,
            cberp_delivery_note_items.product_cost,cberp_delivery_note_items.totaldiscount AS deliverytotaldiscount,
            cberp_products.product_name AS product_name, cberp_products.alert_quantity, cberp_products.product_code, cberp_products.unit, cberp_product_ai.min_price as minprice,cberp_product_ai.max_disrate as maximumdiscount,cberp_product_ai.income_account_number,cberp_product_ai.expense_account_number,cberp_products.onhand_quantity AS totalQty,cberp_delivery_note_items.subtotal AS deliverysubtotal, cberp_delivery_note_items.totaltax AS deliverytaxtotal'
            
        );

        
        $this->db->from('cberp_delivery_notes');
        $this->db->join('cberp_delivery_note_items', 'cberp_delivery_note_items.delevery_note_id = cberp_delivery_notes.delevery_note_id');
        $this->db->join('cberp_store', 'cberp_store.id = cberp_delivery_notes.store_id');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_delivery_note_items.product_id', 'left');
        $this->db->join('cberp_product_ai', 'cberp_product_ai.product_id = cberp_products.pid', 'left');
        $this->db->where_in('cberp_delivery_notes.delevery_note_id', $id);

        $query = $this->db->get();
    //   die($this->db->last_query());
        $result = $query->result_array();
        return $result;

    }

    public function check_delivered_and_return_qty_equal($delivery_note_number) {
        $this->db->select("CASE 
            WHEN MIN(cberp_delivery_note_items.quantity = cberp_delivery_note_items.delivery_returned_quantity) = 1 
            THEN 1 
            ELSE 0 
            END AS all_equal", false);
        $this->db->from('cberp_delivery_note_items');
        $this->db->where('cberp_delivery_note_items.delivery_note_number', $delivery_note_number);
        
        $query = $this->db->get();
        return $query->row()->all_equal;
    }
    
    //erp2024 function for delivery note by id 08-07-2024
    //erp2024 function for delivery note by id 29-09-2024

    public function employee($id)
    {
        $this->db->select('cberp_employees.name,cberp_employees.sign,cberp_users.roleid,,cberp_users.email,cberp_employees.phone');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function sales_reference($salesorder_number)
    {
        $this->db->select('cberp_sales_orders.customer_reference_number,cberp_sales_orders.salesorder_date as refdate');
        $this->db->from('cberp_sales_orders');  
        $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_number);
        $query = $this->db->get();
        return $query->row_array();
    }
    //erp2024 function for delivery note by id 29-09-2024
     //erp2024 16-10-2024 starts
     public function get_deliverynote_count_filter($datefield,$amountfield,$filter_status,$filter_expiry_date_from,$filter_expiry_date_to,$filter_price_from,$filter_price_to,$salesorder_number,$filter_customer)
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
         $this->db->from('cberp_delivery_notes');
         $this->db->where('cberp_delivery_notes.salesorder_number IS NOT NULL');
             // Apply the filter conditions
         if (!empty($filter_status)) {
             $this->db->where_in('cberp_delivery_notes.status', $filter_status);
         }
         if (!empty($filter_expiry_date_from) && !empty($filter_expiry_date_to)) {
             $this->db->where("cberp_delivery_notes.created_date BETWEEN '$filter_expiry_date_from' AND '$filter_expiry_date_to'");
         }
 
         if(!empty($filter_customer)){
             $this->db->where_in("cberp_delivery_notes.customer_id",$filter_customer);
         }
 
         if(!empty($salesorder_number)){
             $this->db->where_in("cberp_delivery_notes.salesorder_number",$salesorder_number);
         }
 
         if($filter_price_to > 0){
             $this->db->where("cberp_delivery_notes.total_amount BETWEEN $filter_price_from AND $filter_price_to");
         }
         $query = $this->db->get();
        //  die($this->db->last_query());
         return $query->row_array();
     }
     //erp2024 16-10-2024 ends
   


    public function deliverynote_number()
    {
        $this->db->select('delivery_note_number');
        $this->db->from('cberp_delivery_notes');
        $this->db->order_by('created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $prefix1 = get_prefix_72();
        $prefix = $prefix1['deliverynote_prefix'];
        if ($query->num_rows() > 0) {
            $last_quote_number = $query->row()->delivery_note_number;
            $parts = explode('/', $last_quote_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $prefix.$next_number;
        } else {
            return $prefix.'1001';
        }
    }

     public function deliverynote_already_exist_or_not($delivery_note_number){
        $this->db->select('delivery_note_number');
        $this->db->from('cberp_delivery_notes');
        $this->db->where_in("cberp_delivery_notes.delivery_note_number",$delivery_note_number);
        $query2 = $this->db->get();
        $result2 = $query2->row_array();
        if ($result2) {
            return $result2['delivery_note_number'];
        } else {
            return 0;
        }

    }
    public function order_amount_total_by_delivery_note_ids($delivery_note_ids){
        $this->db->select_sum('order_discount');
        $this->db->where_in('delevery_note_id', $delivery_note_ids);
        $query = $this->db->get('cberp_delivery_notes');
        $result = $query->row();
        $order_discount_sum = $result->order_discount;
        return $order_discount_sum;
    }
    public function delivery_note_journal_records($delivery_note_number){
       
        $this->db->select('
            cberp_delivery_notes.transaction_number,
            cberp_transactions.debit,
            cberp_transactions.credit,
            cberp_transactions.date,
            cberp_transactions.acid,
            cberp_employees.name AS employee,
            cberp_accounts.holder,
            cberp_accounts.acn
        ');
        $this->db->from('cberp_delivery_notes');
        $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_delivery_notes.transaction_number');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_transactions.eid', 'left');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    // public function get_filter_count(){
    //     $today = date('Y-m-d');
    //     $query = $this->db->query("
    //     SELECT 
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
    //         SUM(CASE WHEN DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_count,

    //         SUM(CASE WHEN (status = 'Completed' OR status = 'Invoiced' OR status = 'Canceled') AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_assigned_count,
    //         SUM(CASE WHEN (status = 'Completed' OR status = 'Invoiced' OR status = 'Canceled') AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_assigned_count,
    //         SUM(CASE WHEN (status = 'Completed' OR status = 'Invoiced' OR status = 'Canceled') AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_assigned_count,
    //         SUM(CASE WHEN (status = 'Completed' OR status = 'Invoiced' OR status = 'Canceled') AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_assigned_count,
    //         SUM(CASE WHEN (status = 'Completed' OR status = 'Invoiced' OR status = 'Canceled') AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_assigned_count,

    //         SUM(CASE WHEN status = 'Created' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
    //         SUM(CASE WHEN status = 'Created' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
    //         SUM(CASE WHEN status = 'Created' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
    //         SUM(CASE WHEN status = 'Created' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
    //         SUM(CASE WHEN status = 'Created' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

    //         SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
    //         SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
    //         SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
    //         SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
    //         SUM(CASE WHEN status = 'Draft' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

    //         SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_progress_count,
    //         SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_progress_count,
    //         SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_progress_count,
    //         SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_progress_count,
    //         SUM(CASE WHEN status = 'In Progress' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_progress_count,

    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN total_amount ELSE 0 END) AS yearly_total,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN total_amount ELSE 0 END) AS quarterly_total,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN total_amount ELSE 0 END) AS monthly_total,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN total_amount ELSE 0 END) AS weekly_total,
    //         SUM(CASE WHEN DATE(created_date) = '$today' THEN total_amount ELSE 0 END) AS daily_total

    //     FROM cberp_delivery_notes
    // ");  
  
    //     return $query->row();
    // }

    public function get_filter_count($ranges)
    {
        $today = date('Y-m-d')." 23:59:59";
        $today_date = date('Y-m-d');
        $startYear     = $ranges['year']." 00:00:00";
        $startQuarter  = $ranges['quarter']." 00:00:00";
        $startMonth    = $ranges['month']." 00:00:00";
        $startWeek     = $ranges['week']." 00:00:00";

        $query = $this->db->query("
            SELECT 
                -- Total counts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(created_date) = '$today_date' THEN 1 ELSE 0 END) AS daily_count,

                -- Assigned statuses: Completed, Invoiced, Canceled
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_assigned_count,
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_assigned_count,
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_assigned_count,
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_assigned_count,
                SUM(CASE WHEN status IN ('Completed', 'Invoiced', 'Canceled') AND DATE(created_date) = '$today_date' THEN 1 ELSE 0 END) AS daily_assigned_count,

                -- Created status
                SUM(CASE WHEN status = 'Created' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN status = 'Created' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN status = 'Created' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN status = 'Created' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN status = 'Created' AND DATE(created_date) = '$today_date' THEN 1 ELSE 0 END) AS daily_created_count,

                -- Draft status
                SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN status = 'Draft' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN status = 'Draft' AND DATE(created_date) = '$today_date' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- In Progress status
                SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_progress_count,
                SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_progress_count,
                SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_progress_count,
                SUM(CASE WHEN status = 'In Progress' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_progress_count,
                SUM(CASE WHEN status = 'In Progress' AND DATE(created_date) = '$today_date' THEN 1 ELSE 0 END) AS daily_progress_count,

                -- Total amounts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN total_amount ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN total_amount ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN total_amount ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN total_amount ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(created_date) = '$today_date' THEN total_amount ELSE 0 END) AS daily_total

            FROM cberp_delivery_notes
        "); 

        return $query->row();
    }

    public function check_product_existornot($delivery_note_number,$product_code)
    {
        $this->db->select('product_code');
        $this->db->from('cberp_delivery_note_items');
        $this->db->where('delivery_note_number', $delivery_note_number);
        $this->db->where('product_code', $product_code);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }

    }

}
