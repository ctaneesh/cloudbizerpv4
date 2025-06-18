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

class Deliveryreturn_model extends CI_Model
{
    var $table = 'cberp_delivery_returns';

    var $column_order = array(null,  'cberp_delivery_returns.delivery_return_number', 'cberp_delivery_returns.total_amount', 'cberp_delivery_returns.created_date','cberp_customers.name','cberp_custom_data.data','cberp_delivery_returns.status', null);

    var $column_search = array('cberp_delivery_returns.delivery_return_number', 'cberp_delivery_returns.total_amount','cberp_delivery_returns.created_date','cberp_customers.name','cberp_custom_data.data','cberp_delivery_returns.status');
    var $order = array('cberp_delivery_returns.created_date' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    private function _get_datatables_query($opt = '')
    {
        //created_time
        $this->db->select('cberp_delivery_returns.delivery_return_number,cberp_delivery_returns.delivery_return_number, cberp_delivery_returns.total_amount, cberp_delivery_returns.created_date,cberp_delivery_returns.delivery_note_number,cberp_delivery_returns.customer_id, cberp_customers.name,cberp_custom_data.data,cberp_delivery_returns.status,cberp_delivery_returns.delivery_note_status,cberp_delivery_returns.convert_to_credit_note_flag,cberp_invoices.invoice_number AS invoice_id');
        $this->db->from($this->table);
        $this->db->join('cberp_customers', 'cberp_delivery_returns.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_custom_data', 'cberp_custom_data.rid = cberp_customers.customer_id','left');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.delivery_note_number = cberp_delivery_returns.delivery_note_number','left');
        $this->db->join('cberp_invoices', 'cberp_invoices.invoice_number = cberp_delivery_notes.invoice_number','left');
        // $this->db->where('cberp_custom_data.module', 1);
        if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
        {
            $this->db->where('DATE(cberp_delivery_returns.created_date) >=', datefordatabase($this->input->post('start_date')));
            $this->db->where('DATE(cberp_delivery_returns.created_date) <=', datefordatabase($this->input->post('end_date')));
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

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
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
        // echo $this->db->last_query();
        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($opt) {
            $this->db->where('deliverynote_id', $opt);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('cberp_delivery_returns.delivery_note_number');
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function productidFromdeliverynote($noteidid)
    {
        $this->db->select('product_id');
        $this->db->from('cberp_delivery_return_items');
        $this->db->where('deliverynote_id', $noteidid);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function customerByDeliverynoteid($id)
    {
        $this->db->select('cberp_customers.*');
        $this->db->from('cberp_customers');        
        $this->db->join('cberp_delivery_returns', 'cberp_delivery_returns.customer_id = cberp_customers.customer_id', 'left');
        $this->db->where('cberp_delivery_returns.delivery_note_number', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function itemcountwithstatus($deliveryNoteId){
        // First SELECT query to count invoiced items
        $this->db->select('COUNT(cberp_delivery_return_items.deliverynote_id) AS invoiced_count');
        $this->db->from('cberp_delivery_return_items');
        $this->db->join('cberp_delivery_returns', 'cberp_delivery_returns.delivery_note_number = cberp_delivery_return_items.deliverynote_id');
        $this->db->where('cberp_delivery_return_items.deliverynote_id', $deliveryNoteId);
        $this->db->where('cberp_delivery_return_items.status', 'Invoiced');
        $query1 = $this->db->get();
        $result1 = $query1->row()->invoiced_count;
    }
    public function itemcountwithoutstatus($deliveryNoteId){
        $this->db->select('COUNT(cberp_delivery_return_items.deliverynote_id) AS total_count');
        $this->db->from('cberp_delivery_return_items');
        $this->db->join('cberp_delivery_returns', 'cberp_delivery_returns.delivery_note_number = cberp_delivery_return_items.deliverynote_id');
        $this->db->where('cberp_delivery_return_items.deliverynote_id', $deliveryNoteId);
        $query2 = $this->db->get();
        $result2 = $query2->row()->total_count;
    }

    //erp2024 function for delivery note by id 08-07-2024
    public function deliveryreturnbyid($id)
    {
        //delivery_note_status 
        $this->db->select('cberp_delivery_returns.*,cberp_customers.*,cberp_customers.customer_id as customerid,cberp_sales_orders.*,cberp_delivery_returns.status as notestatus,cberp_delivery_returns.order_discount as return_order_discount,cberp_delivery_returns.order_discount_percentage as return_order_discount_percentage,cberp_delivery_returns.delivery_note_number AS delevery_note_id,cberp_store.store_name as warehousename,cberp_delivery_notes.delivery_note_number as deliverynotenum,cberp_delivery_notes.transaction_number,cberp_delivery_notes.invoice_number,cberp_delivery_returns.store_id as warehouseid,cberp_delivery_notes.status AS delivery_note_status,cberp_delivery_notes.delivery_note_number,cberp_delivery_returns.transaction_number AS return_transaction_number,cberp_delivery_returns.created_by,cberp_delivery_returns.created_date');
        $this->db->from('cberp_delivery_returns');        
        $this->db->join('cberp_customers', 'cberp_delivery_returns.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.delivery_note_number = cberp_delivery_returns.delivery_note_number');
        $this->db->join('cberp_sales_orders', 'cberp_sales_orders.salesorder_number = cberp_delivery_notes.salesorder_number', 'left');
        $this->db->join('cberp_store', 'cberp_store.store_id = cberp_delivery_returns.store_id', 'left');
        $this->db->where('cberp_delivery_returns.delivery_return_number', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function deliveryreturn_products($id)
    {
        $this->db->select('
            cberp_delivery_return_items.*,
            cberp_delivery_return_items.return_quantity as return_qty,
            cberp_delivery_return_items.delivered_quantity as delivered_quantity,
            cberp_delivery_return_items.subtotal AS deliverysubtotal,
            cberp_delivery_return_items.total_tax AS deliverytaxtotal,
            cberp_products.onhand_quantity AS totalQty,
            cberp_product_description.product_name,
            cberp_products.alert_quantity,
            cberp_products.product_code,
            cberp_products.unit,
            cberp_products.income_account_number,
            cberp_products.expense_account_number
        ');
        $this->db->from('cberp_delivery_return_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_delivery_return_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_delivery_return_items.delivery_return_number', $id);

        $query = $this->db->get();

        // Uncomment below line for debugging only
        // echo $this->db->last_query(); die();

        return $query->result_array();
    }

    public function deliverynote_products_for_return($id)
    {
        $this->db->select('cberp_delivery_return_items.*, cberp_products.product_code AS prdcode, cberp_product_description.product_name AS prdname');
        $this->db->from('cberp_delivery_return_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_delivery_return_items.product_code', 'inner');        
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_product_description.product_code');
        $this->db->join('cberp_sales_orders_items', 'cberp_sales_orders_items.tid = cberp_delivery_return_items.salesorder_id AND cberp_sales_orders_items.pid = cberp_delivery_return_items.product_id');
        $this->db->where('cberp_delivery_return_items.delivery_return_number', $id);
        // $this->db->group_by('cberp_delivery_return_items.delivery_return_number');
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->result_array();


    }
    public function last_delivery_return_number(){
        $this->db->select('delivery_return_number');
        $this->db->from('cberp_delivery_returns');
        $this->db->order_by('delivery_return_number', 'DESC');
        $this->db->limit(1);
        $query2 = $this->db->get();
        $result2 = $query2->row_array();

        if ($result2) {
            return $result2['delivery_return_number'] + 1000;
        } else {
            return 1000;
        }

    }
    public function existingnotelist($deliverynote_id,$product_id){
        $this->db->select('cberp_delivery_return_items.product_qty');
        $this->db->from('cberp_delivery_return_items');        
        $this->db->where('cberp_delivery_return_items.deliverynote_id', $deliverynote_id);
        $this->db->where('cberp_delivery_return_items.product_id', $product_id);
        $this->db->limit(1);
        $query2 = $this->db->get();        
        $result2 =$query2->row_array();
        if ($result2) {
            return $result2['product_qty'];
        } else {
            return 0;
        }
    }
    public function deliverynote_status($delivery_note_number){
        $this->db->select('cberp_delivery_notes.status');
        $this->db->from('cberp_delivery_notes');        
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
        $this->db->limit(1);
        $query2 = $this->db->get();        
        $result2 =$query2->row_array();
        if ($result2) {
            return $result2['status'];
        } else {
            return 0;
        }
    }
    public function invoice_details_by_delnoteid($delivery_note_number){
        $this->db->select('cberp_invoices.invoice_number');
        $this->db->from('cberp_invoices');       
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.invoice_number = cberp_invoices.invoice_number');
        $this->db->join('cberp_delivery_returns', 'cberp_delivery_returns.delivery_note_number = cberp_delivery_notes.delivery_note_number','left');
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number); 
        // $this->db->where('cberp_invoices.delevery_note_id', $deliverynote_id);
        $this->db->limit(1);
        $query2 = $this->db->get();   
        // die( $this->db->last_query());
        $result2 =$query2->row_array();
        
        if ($result2) {
            return $result2['invoice_number'];
        } else {
            return 0;
        }
    }
    public function customer_details($delivery_return_number){
        $this->db->select('cberp_customers.customer_id,cberp_customers.name,cberp_customers.phone,cberp_customers.address,cberp_customers.city,cberp_customers.region,cberp_customers.postbox,cberp_customers.email,cberp_customers.credit_limit,cberp_customers.credit_period,cberp_customers.avalable_credit_limit');
        $this->db->from('cberp_delivery_returns');        
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_delivery_returns.customer_id');
        $this->db->where('cberp_delivery_returns.delivery_return_number', $delivery_return_number);
        $this->db->limit(1);
        $query2 = $this->db->get();        
        $result2 =$query2->row_array();
        return $result2;
    }
    public function deliveryreturn_maindata($delivery_return_number) {
        $this->db->select('cberp_delivery_returns.salesorder_id');
        $this->db->from('cberp_delivery_returns');
        $this->db->where('cberp_delivery_returns.delivery_return_number', $delivery_return_number);
        $query = $this->db->get();
        $result = $query->row_array();
        return($result['salesorder_id']);
    }
    public function invoice_details($delivery_note_number) {
        $this->db->select('cberp_invoices.invoice_number');
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.invoice_number = cberp_invoices.invoice_number');
        $this->db->join('cberp_delivery_returns', 'cberp_delivery_returns.delivery_note_number = cberp_delivery_notes.delivery_note_number','left');
        // $this->db->where('cberp_delivery_returns.delivery_note_number', $deliverynote_id);
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number); 
        $query = $this->db->get();
        // die( $this->db->last_query());
        $result = $query->row_array();
        return($result);
    }
    //erp2024 function for delivery note by id 08-07-2024
    public function deliverynote_byretid($id)
    {
        $this->db->select('delnote_number,status,delivery_note_number');
        $this->db->from('cberp_delivery_notes');        
        $this->db->where('cberp_delivery_notes.delevery_note_id', $id);
        $query = $this->db->get();
        return $query->row_array();
        
    }
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
     public function delivery_return_journal_records($id){
       
        $this->db->select('
            cberp_delivery_returns.transaction_number,
            cberp_transactions.debit,
            cberp_transactions.credit,
            cberp_transactions.date,
            cberp_transactions.acid,
            cberp_employees.name AS employee,
            cberp_accounts.holder,
            cberp_accounts.acn
        ');
        $this->db->from('cberp_delivery_returns');
        $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_delivery_returns.transaction_number');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_transactions.eid', 'left');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_delivery_returns.delivery_return_number', $id);

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

    //         SUM(CASE WHEN status = 'Pending' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
    //         SUM(CASE WHEN status = 'Pending' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
    //         SUM(CASE WHEN status = 'Pending' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
    //         SUM(CASE WHEN status = 'Pending' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
    //         SUM(CASE WHEN status = 'Pending' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

    //         SUM(CASE WHEN status = 'Approved' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
    //         SUM(CASE WHEN status = 'Approved' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
    //         SUM(CASE WHEN status = 'Approved' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
    //         SUM(CASE WHEN status = 'Approved' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
    //         SUM(CASE WHEN status = 'Approved' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN total_amount ELSE 0 END) AS yearly_total,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN total_amount ELSE 0 END) AS quarterly_total,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN total_amount ELSE 0 END) AS monthly_total,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN total_amount ELSE 0 END) AS weekly_total,
    //         SUM(CASE WHEN DATE(created_date) = '$today' THEN total_amount ELSE 0 END) AS daily_total

    //     FROM cberp_delivery_returns
    // ");
  
    //     return $query->row();
    // }


    public function get_filter_count()
    {
        $today = date('Y-m-d');

        // Generate date ranges with a helper
        $ranges = getCommonDateRanges($today);
        $startYear    = $ranges['year'];
        $startQuarter = $ranges['quarter'];
        $startMonth   = $ranges['month'];
        $startWeek    = $ranges['week'];

        $query = $this->db->query("
            SELECT 
                -- Total counts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- Pending status
                SUM(CASE WHEN status = 'Pending' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN status = 'Pending' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN status = 'Pending' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN status = 'Pending' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN status = 'Pending' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

                -- Approved status
                SUM(CASE WHEN status = 'Approved' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN status = 'Approved' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN status = 'Approved' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN status = 'Approved' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN status = 'Approved' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- Total amounts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN total_amount ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN total_amount ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN total_amount ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN total_amount ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(created_date) = '$today' THEN total_amount ELSE 0 END) AS daily_total

            FROM cberp_delivery_returns
        ");

        return $query->row();
    }

}
