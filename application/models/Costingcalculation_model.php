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

class Costingcalculation_model extends CI_Model
{
    var $table = 'cberp_purchase_receipts';
    var $column_order = array(null, 'cberp_purchase_receipts.id','cberp_purchase_receipts.salepoint_name','cberp_suppliers.name','cberp_purchase_receipts.party_name','cberp_purchase_receipts.bill_number','cberp_purchase_receipts.purchase_reciept_number','cberp_purchase_receipts.purchase_amount','cberp_purchase_receipts.cost_factor','cberp_purchase_receipts.created_date','cberp_purchase_receipts.damageclaim_account_id','cberp_purchase_receipts.damageclaim_ac_name', null);
    var $column_search = array('cberp_purchase_receipts.id','cberp_purchase_receipts.salepoint_name','cberp_suppliers.name','cberp_purchase_receipts.party_name','cberp_purchase_receipts.bill_number','cberp_purchase_receipts.purchase_reciept_number','cberp_purchase_receipts.purchase_amount','cberp_purchase_receipts.cost_factor','cberp_purchase_receipts.created_date','cberp_purchase_receipts.damageclaim_account_id','cberp_purchase_receipts.damageclaim_ac_name');
    var $order = array('cberp_purchase_receipts.id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function lastinvoice()
    {
        $this->db->select('tid');
        $this->db->from($this->table);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $this->db->where('i_class', 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->tid;
        } else {
            return 1000;
        }
    }
    public function lastenquiry()
    {
        $this->db->select('lead_number');
        $this->db->from('cberp_customer_leads');
        $this->db->where("lead_number IS NOT NULL");
        $this->db->order_by('lead_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_lead_number = $query->row()->lead_number;
            $parts = explode('/', $last_lead_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $next_number;
        } else {
            return '1001';
        }
    }
    // public function lastsrvNumber($id)
    // {
    //     $this->db->select('srv,purchase_id');
    //     $this->db->from("cberp_purchase_receipts");
    //     $this->db->order_by('srv', 'DESC');
    //     $this->db->group_start();
    //     $this->db->where('srv !=', NULL);
    //     $this->db->or_where('srv !=', ''); 
    //     $this->db->group_end();
    //     $this->db->limit(1);
    //     $query = $this->db->get();
    //     // die($this->db->last_query());
    //     $prefix = "TCODE/RECPT/";
    //     if ($query->num_rows() > 0) {
           
    //         if($query->row()->purchase_id==$id){
    //             die("here");
    //             $srvdata =[
    //                 "srv"=>$query->row()->srv,
    //                 "srvflg" =>1
    //             ];
    //             return $srvdata;
    //         }
    //         else{
    //             die("here");
    //             $srv =  $query->row()->srv+1;
    //             $srvdata =[
    //                 "srv"=>$srv,
    //                 "srvflg" =>0
    //             ];
    //             return $srvdata;
    //         }
           
    //     } 
    //     else {
    //         $srvdata =[
    //             "srv"=>$prefix."10001",
    //             "srvflg" =>0
    //         ];
    //         return $srvdata;
    //     }
    // }

    public function lastsrvNumber($var="")
    {
        $this->configurations = $this->session->userdata('configurations');
        $prefixlist = get_prefix();
        $prefix = $prefixlist['receipt_prefix'];
        $this->db->select('purchase_reciept_number');
        $this->db->from('cberp_purchase_receipts');
        $this->db->order_by('created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
       
        if ($query->num_rows() > 0) {
            $last_invoice_number = $query->row()->purchase_reciept_number;
            $parts = explode('/', $last_invoice_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $prefix.$next_number;
        } else {
            return $prefix.'1001';
        }
    }

    public function purchase_details($purchase_number){
        $query = $this->db->select('cberp_purchase_orders.*,cberp_purchase_orders.purchase_number AS purchase_id,cberp_purchase_orders.purchase_type AS doctype, cberp_purchase_orders.order_total AS purchase_amount,cberp_purchase_orders.order_total AS bill_amount, cberp_purchase_orders.notes AS bill_description, cberp_suppliers.name AS supplier_name, cberp_suppliers.supplier_id AS supplier_id, cberp_suppliers.name AS party_name, cberp_store.store_name AS salepoint_name, cberp_store.store_id  AS salepoint_id, cberp_currencies.code AS currency_id, cberp_currencies.rate AS currency_rate')
        ->from('cberp_purchase_orders')
        ->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_purchase_orders.customer_id')
        ->join('cberp_store', 'cberp_store.store_id = cberp_purchase_orders.store_id', 'left')
        ->join('cberp_currencies', 'cberp_currencies.id = cberp_purchase_orders.currency_id')
        ->where('cberp_purchase_orders.purchase_number', $purchase_number)
        ->get();
        // die( $this->db->last_query());
        return $query->row_array();
    }
    public function purchase_order_by_srv($purchase_reciept_number){
        
        $this->db->select('cberp_purchase_orders.purchase_number, cberp_purchase_orders.purchase_order_date,cberp_purchase_orders.id');
        $this->db->from('cberp_purchase_receipts');
        $this->db->join('cberp_purchase_orders', 'cberp_purchase_orders.purchase_number = cberp_purchase_receipts.purchase_number');
        $this->db->where('cberp_purchase_receipts.purchase_reciept_number', $purchase_reciept_number);
        $query = $this->db->get();
        return $query->row_array();
    }

    
    public function cberp_costing_master_details($purchase_reciept_number){
        $query = $this->db->select('cberp_purchase_receipts.*,cberp_suppliers.name AS supplier_name')
        ->from('cberp_purchase_receipts')
        ->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_purchase_receipts.supplier_id')
        ->where('cberp_purchase_receipts.purchase_reciept_number', $purchase_reciept_number)
        ->get();
        return $query->row_array();
    }
    
    public function costing_idby_purchase_id($id){
        $query = $this->db->select('cberp_purchase_receipts.id')
        ->from('cberp_purchase_receipts')
        ->where('cberp_purchase_receipts.purchase_id', $id)
        ->get();       
        $data = $query->row_array();
         return $data['id'];
        
    }
    public function costing_item_details($purchase_reciept_number){
        // $query = $this->db->select('cberp_purchase_receipt_items.*')
        // ->from('cberp_purchase_receipt_items')
        // ->where('cberp_purchase_receipt_items.stockreciptid', $id)
        // ->get();

        $this->db->select('cberp_purchase_receipt_items.*, cberp_products.product_cost as cost,cberp_product_description.product_name,cberp_products.product_code,cberp_products.unit AS product_unit');
        $this->db->from('cberp_purchase_receipt_items');
        $this->db->where('cberp_purchase_receipt_items.purchase_reciept_number', $purchase_reciept_number);
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_purchase_receipt_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function cberp_costing_expenses_details($purchase_reciept_number){
        $query = $this->db->select('cberp_purchase_receipt_expenses.*')
        ->from('cberp_purchase_receipt_expenses')
        ->where('cberp_purchase_receipt_expenses.purchase_reciept_number', $purchase_reciept_number)
        ->get();
        return $query->result_array();
    }

    public function purchase_item_details($purchase_number){
        // $query = $this->db->query("SELECT * FROM cberp_purchase_order_items WHERE tid = '$id'");
        // cberp_purchase_order_items.*
        $this->db->select('cberp_purchase_order_items.*, cberp_purchase_order_items.quantity AS product_qty,cberp_purchase_order_items.quantity AS ordered_quantity,cberp_purchase_order_items.received_quantity AS product_qty_recieved,cberp_purchase_order_items.discount AS discountamount,cberp_purchase_order_items.subtotal AS netamount,cberp_purchase_order_items.product_code, cberp_product_description.product_name,cberp_products.product_code,cberp_products.product_cost as cost,cberp_products.unit AS product_unit');
        $this->db->from('cberp_purchase_order_items');
        $this->db->where('cberp_purchase_order_items.purchase_number', $purchase_number);
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_purchase_order_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
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

    public function items_with_product($id)
    {

        $this->db->select('cberp_invoice_items.*,cberp_products.onhand_quantity AS alert');
        $this->db->from('cberp_invoice_items');
        $this->db->where('tid', $id);
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
        $query = $this->db->get();
        return $query->result_array();

    }

    public function currencies()
    {

        $this->db->select('*');
        $this->db->from('cberp_currencies');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function currency_d($id, $loc = 0)
    {
        if ($loc) {
            $query = $this->db->query("SELECT cur FROM cberp_locations WHERE id='$loc' LIMIT 1");
            $row = $query->row_array();
            $id = $row['cur'];
        }
        $this->db->select('*');
        $this->db->from('cberp_currencies');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
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

    private function _get_datatables_query($opt = '')
    {
        $this->db->select('cberp_purchase_receipts.*,cberp_suppliers.name as supplier_name');
        $this->db->from('cberp_purchase_receipts');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_purchase_receipts.supplier_id');
        $this->db->where('cberp_purchase_receipts.status', '1');
        if ($opt) {
            $this->db->where('cberp_purchase_receipts.id', $opt);
        }

        $i = 0;
        // $this->db->where("receipt_type","Genuine");
        if ($this->input->post('start_date') && $this->input->post('end_date'))
        {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(cberp_purchase_receipts.created_date) BETWEEN '$start_date' AND '$end_date'");
        }

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
        // $this->db->where('cberp_purchase_receipts.status', 1);
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($opt) {
            $this->db->where('id', $opt);
        }
        
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('cberp_purchase_receipts.id');
        $this->db->from($this->table);
        $this->db->where('cberp_purchase_receipts.status', 1);
        if ($opt) {
            $this->db->where('cberp_purchase_receipts.id', $opt);

        }
        
        return $this->db->count_all_results();
    }

    //erp2024 03-10-2024
    public function default_warehouse()
    {
        $this->db->select('store_id,store_name');
        $this->db->from('cberp_store');
        $this->db->where('warehouse_type','Main');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
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
    public function supplier_details($id)
    {
        $this->db->select('cberp_suppliers.name, cberp_suppliers.email, cberp_suppliers.phone, cberp_suppliers.email as shipping_email, cberp_suppliers.phone as shipping_phone, cberp_suppliers.company,cberp_suppliers.address,cberp_suppliers.city, cberp_country.name as countryname, cberp_suppliers.region,cberp_suppliers.postbox');
        $this->db->from('cberp_suppliers');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_suppliers.country', 'left');
        $this->db->where('cberp_suppliers.supplier_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function check_product_existornot($purchase_reciept_number,$product_code)
    {
        $this->db->select('id');
        $this->db->from('cberp_purchase_receipt_items');
        $this->db->where('purchase_reciept_number', $purchase_reciept_number);
        $this->db->where('product_code', $product_code);
        $query = $this->db->get();
      
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }

    }
    public function check_expense_existornot($purchase_reciept_number,$expnseid)
    {
        $this->db->select('id');
        $this->db->from('cberp_purchase_receipt_expenses');
        $this->db->where('purchase_reciept_number', $purchase_reciept_number);
        $this->db->where('expense_id', $expnseid);
        $query = $this->db->get();      
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }

    }

    public function tracking_details($field, $id)
    {
        $this->db->select('cberp_transaction_tracking.*');
        $this->db->from('cberp_transaction_tracking');
        $this->db->where('cberp_transaction_tracking.' . $field, $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function purchase_receipt_payments_received($purchase_reciept_number)
    {


        $this->db->select('
            cberp_purchase_receipts.purchase_reciept_number,
            cberp_payment_transaction_link.status,
            cberp_payment_transaction_link.bank_transaction_number,
            cberp_payment_transaction_link.created_by,
            cberp_payment_transaction_link.created_dt,
            cberp_payment_transaction_link.cancelled_by,
            cberp_payment_transaction_link.cancelled_dt,
            cberp_payment_transaction_link.note,
            cberp_bank_transactions.trans_account_id,
            cberp_bank_transactions.trans_chart_of_account_id,
            cberp_bank_transactions.trans_amount,
            cberp_bank_transactions.trans_ref_number,
            cberp_bank_transactions.trans_customer_id,
            cberp_bank_transactions.trans_payment_method,
            cberp_suppliers.name AS customer,
            account_chart.holder AS chart_holder,
            account_trans.holder AS trans_holder
        ');
        $this->db->from('cberp_purchase_receipts');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_purchase_receipts.purchase_reciept_number');
        $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_bank_transactions.trans_supplier_id', 'left');
        $this->db->join('cberp_accounts AS account_chart', 'account_chart.acn = cberp_bank_transactions.trans_chart_of_account_id');
        $this->db->join('cberp_accounts AS account_trans', 'account_trans.acn = cberp_bank_transactions.trans_account_id');
        $this->db->where('cberp_purchase_receipts.purchase_reciept_number', $purchase_reciept_number);
        $this->db->where('cberp_payment_transaction_link.trans_type', 'Purchase');

        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function purchase_receipt_journal_records($purchase_reciept_number)
    {
        $this->db->select('
            cberp_purchase_receipts.transaction_number,
            cberp_transactions.debit,
            cberp_transactions.credit,
            cberp_transactions.date,
            cberp_transactions.acid,
            cberp_employees.name AS employee,
            cberp_accounts.holder,
            cberp_accounts.acn
        ');
        $this->db->from('cberp_purchase_receipts');
        $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_purchase_receipts.transaction_number');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_transactions.eid', 'left');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_purchase_receipts.purchase_reciept_number', $purchase_reciept_number);

        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }

    
    public function get_purchase_receipt_by_srvNumber($purchase_reciept_number)
    {
        $this->db->select('purchase_reciept_number');
        $this->db->from('cberp_purchase_receipts');
        $this->db->where('purchase_reciept_number', $purchase_reciept_number);
        $query = $this->db->get();
        return $query->num_rows();
    }


    // public function get_filter_count(){
    //     $query = $this->db->query("
    //         SELECT 
    //         SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_count,
    //         SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_count,
    //         SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_count,
    //         SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_count,
    //         SUM(CASE WHEN DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_count,

        
    //         SUM(CASE WHEN reciept_status = 'Pending' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_created_count,
    //         SUM(CASE WHEN reciept_status = 'Pending' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_created_count,
    //         SUM(CASE WHEN reciept_status = 'Pending' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_created_count,
    //         SUM(CASE WHEN reciept_status = 'Pending' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_created_count,
    //         SUM(CASE WHEN reciept_status = 'Pending' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_created_count,

        
    //         SUM(CASE WHEN reciept_status = 'Received' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_received_count,
    //         SUM(CASE WHEN reciept_status = 'Received' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_received_count,
    //         SUM(CASE WHEN reciept_status = 'Received' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_received_count,
    //         SUM(CASE WHEN reciept_status = 'Received' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_received_count,
    //         SUM(CASE WHEN reciept_status = 'Received' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_received_count,

    //         SUM(CASE WHEN reciept_status = 'Draft' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_draft_count,
    //         SUM(CASE WHEN reciept_status = 'Draft' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_draft_count,
    //         SUM(CASE WHEN reciept_status = 'Draft' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_draft_count,
    //         SUM(CASE WHEN reciept_status = 'Draft' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_draft_count,
    //         SUM(CASE WHEN reciept_status = 'Draft' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_draft_count,

    //          SUM(CASE WHEN reciept_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_assigned_count,
    //         SUM(CASE WHEN reciept_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_assigned_count,
    //         SUM(CASE WHEN reciept_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_assigned_count,
    //         SUM(CASE WHEN reciept_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_assigned_count,
    //         SUM(CASE WHEN reciept_status = 'Assigned' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_assigned_count,

    //         SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN bill_amount ELSE 0 END) AS yearly_total,
    //         SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN bill_amount ELSE 0 END) AS quarterly_total,
    //         SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN bill_amount ELSE 0 END) AS monthly_total,
    //         SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN bill_amount ELSE 0 END) AS weekly_total,
    //         SUM(CASE WHEN DATE(created_date) = CURDATE() THEN bill_amount ELSE 0 END) AS daily_total
    //         FROM 
    //         cberp_purchase_receipts
    //     ");

      
    //     return $query->row();
    // }
    

    public function get_filter_count()
{
    $today       = date('Y-m-d 00:00:00');
    $endOfToday  = date('Y-m-d 23:59:59');

    $ranges = getCommonDateRanges($today);
    $startYear    = $ranges['year'];
    $startQuarter = $ranges['quarter'];
    $startMonth   = $ranges['month'];
    $startWeek    = $ranges['week'];

    $query = $this->db->query("
        SELECT 
            -- Total Count
            SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$endOfToday' AND supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS yearly_count,
            SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$endOfToday' AND supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS quarterly_count,
            SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$endOfToday' AND supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS monthly_count,
            SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$endOfToday' AND supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS weekly_count,
            SUM(CASE WHEN created_date BETWEEN '$today' AND '$endOfToday' AND supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS daily_count,

            -- Pending
            SUM(CASE WHEN reciept_status = 'Pending' AND created_date BETWEEN '$startYear' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS yearly_created_count,
            SUM(CASE WHEN reciept_status = 'Pending' AND created_date BETWEEN '$startQuarter' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS quarterly_created_count,
            SUM(CASE WHEN reciept_status = 'Pending' AND created_date BETWEEN '$startMonth' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS monthly_created_count,
            SUM(CASE WHEN reciept_status = 'Pending' AND created_date BETWEEN '$startWeek' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS weekly_created_count,
            SUM(CASE WHEN reciept_status = 'Pending' AND created_date BETWEEN '$today' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS daily_created_count,

            -- Received
            SUM(CASE WHEN reciept_status = 'Received' AND created_date BETWEEN '$startYear' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS yearly_received_count,
            SUM(CASE WHEN reciept_status = 'Received' AND created_date BETWEEN '$startQuarter' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS quarterly_received_count,
            SUM(CASE WHEN reciept_status = 'Received' AND created_date BETWEEN '$startMonth' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS monthly_received_count,
            SUM(CASE WHEN reciept_status = 'Received' AND created_date BETWEEN '$startWeek' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS weekly_received_count,
            SUM(CASE WHEN reciept_status = 'Received' AND created_date BETWEEN '$today' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS daily_received_count,

            -- Draft
            SUM(CASE WHEN reciept_status = 'Draft' AND created_date BETWEEN '$startYear' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS yearly_draft_count,
            SUM(CASE WHEN reciept_status = 'Draft' AND created_date BETWEEN '$startQuarter' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS quarterly_draft_count,
            SUM(CASE WHEN reciept_status = 'Draft' AND created_date BETWEEN '$startMonth' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS monthly_draft_count,
            SUM(CASE WHEN reciept_status = 'Draft' AND created_date BETWEEN '$startWeek' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS weekly_draft_count,
            SUM(CASE WHEN reciept_status = 'Draft' AND created_date BETWEEN '$today' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS daily_draft_count,

            -- Assigned
            SUM(CASE WHEN reciept_status = 'Assigned' AND created_date BETWEEN '$startYear' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS yearly_assigned_count,
            SUM(CASE WHEN reciept_status = 'Assigned' AND created_date BETWEEN '$startQuarter' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS quarterly_assigned_count,
            SUM(CASE WHEN reciept_status = 'Assigned' AND created_date BETWEEN '$startMonth' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS monthly_assigned_count,
            SUM(CASE WHEN reciept_status = 'Assigned' AND created_date BETWEEN '$startWeek' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS weekly_assigned_count,
            SUM(CASE WHEN reciept_status = 'Assigned' AND created_date BETWEEN '$today' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN 1 ELSE 0 END) AS daily_assigned_count,

            -- Bill Amount Totals
            SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN bill_amount ELSE 0 END) AS yearly_total,
            SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN bill_amount ELSE 0 END) AS quarterly_total,
            SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN bill_amount ELSE 0 END) AS monthly_total,
            SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN bill_amount ELSE 0 END) AS weekly_total,
            SUM(CASE WHEN created_date BETWEEN '$today' AND '$endOfToday' AND  supplier_id IS NOT NULL THEN bill_amount ELSE 0 END) AS daily_total

        FROM cberp_purchase_receipts
    ");

    return $query->row();
}
    


}
