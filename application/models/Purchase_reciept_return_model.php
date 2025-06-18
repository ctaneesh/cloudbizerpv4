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

class Purchase_reciept_return_model extends CI_Model
{
    var $table = 'cberp_purchase_reciept_returns';
    var $column_order = array(null, 'cberp_purchase_reciept_returns.purchase_reciept_number','cberp_purchase_reciept_returns.receipt_return_number', 'cberp_suppliers.name', 'cberp_purchase_reciept_returns.return_date','cberp_purchase_reciept_returns.total', 'cberp_purchase_reciept_returns.status', null);

    var $column_search = array('cberp_purchase_reciept_returns.purchase_reciept_number','cberp_purchase_reciept_returns.receipt_return_number', 'cberp_suppliers.name', 'cberp_purchase_reciept_returns.return_date','cberp_purchase_reciept_returns.total', 'cberp_purchase_reciept_returns.status');
    var $order = array('cberp_purchase_reciept_returns.id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function last_return()
    {
        $prefix = get_prefix();
        $purchasereturn_prefix =  $prefix['purchasereturn_prefix'];
        $this->db->select('receipt_return_number');
        $this->db->from('cberp_purchase_reciept_returns');
        $this->db->order_by('return_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $last_purchase_number = $query->row()->receipt_return_number;
            $parts = explode('/', $last_purchase_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $purchasereturn_prefix.$next_number;
        } else {
            return $purchasereturn_prefix."1001";
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

    public function currencies()
    {

        $this->db->select('*');
        $this->db->from('cberp_currencies');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function purchase_details($receipt_return_number)
    {

       
            $this->db->select('cberp_purchase_reciept_returns.*,cberp_purchase_reciept_returns.id AS iid,SUM(cberp_purchase_reciept_returns.shipping_charge + cberp_purchase_reciept_returns.shipping_tax) AS shipping,cberp_suppliers.*,cberp_suppliers.supplier_id AS cid, cberp_purchase_orders.purchase_number as ponumber,cberp_purchase_orders.internal_reference as poreference,cberp_purchase_orders.customer_reference as supplier_reference, cberp_purchase_orders.customer_contact_person as supplier_contactperson, cberp_purchase_orders.customer_contact_number as supplier_contactno, cberp_purchase_orders.customer_contact_email as supplier_contacctemail, cberp_purchase_orders.purchase_type as supplier_doctype, cberp_purchase_orders.currency_id as supplier_currency,,cberp_purchase_receipts.id as receiptid,cberp_purchase_receipts.purchase_reciept_number as receiptnumber,cberp_purchase_receipts.bill_number,cberp_purchase_receipts.bill_date,cberp_purchase_receipts.purchase_receipt_date,cberp_purchase_receipts.note');

            // $this->db->select('cberp_purchase_reciept_returns.*,cberp_purchase_reciept_returns.id AS iid,SUM(cberp_purchase_reciept_returns.shipping_charge + cberp_purchase_reciept_returns.shipping_tax) AS shipping,cberp_suppliers.*,cberp_suppliers.supplier_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms, cberp_purchase_orders.purchase_number as ponumber,cberp_purchase_orders.internal_reference as poreference,cberp_purchase_orders.customer_reference as supplier_reference, cberp_purchase_orders.customer_contact_person as supplier_contactperson, cberp_purchase_orders.customer_contact_number as supplier_contactno, cberp_purchase_orders.customer_contact_email as supplier_contacctemail, cberp_purchase_orders.purchase_type as supplier_doctype, cberp_purchase_orders.currency_id as supplier_currency,,cberp_purchase_receipts.id as receiptid,cberp_purchase_receipts.purchase_reciept_number as receiptnumber,cberp_purchase_receipts.bill_number,cberp_purchase_receipts.bill_date,cberp_purchase_receipts.purchase_receipt_date,cberp_purchase_receipts.note');
            $this->db->from($this->table);
            $this->db->join('cberp_suppliers', 'cberp_purchase_reciept_returns.supplier_id = cberp_suppliers.supplier_id', 'left');
            // $this->db->join('cberp_terms', 'cberp_terms.id = cberp_purchase_reciept_returns.payment_terms', 'left');
            $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.purchase_reciept_number = cberp_purchase_reciept_returns.purchase_reciept_number', 'left');
            $this->db->join('cberp_purchase_orders', 'cberp_purchase_orders.purchase_number = cberp_purchase_receipts.purchase_number', 'left');
        
            $this->db->where('cberp_purchase_reciept_returns.receipt_return_number', $receipt_return_number);
            $query = $this->db->get();
            // die($this->db->last_query());
            return $query->row_array();
        // }

    }

    public function purchase_products($receipt_return_number)
    {

        $this->db->select('cberp_purchase_reciept_returns_items.*,cberp_purchase_reciept_returns_items.quantity AS product_quantity_recieved,cberp_purchase_reciept_returns_items.damaged_quantity,cberp_product_description.product_name as product,cberp_products.product_code as code');
        $this->db->from('cberp_purchase_reciept_returns_items');
        $this->db->where('cberp_purchase_reciept_returns_items.receipt_return_number', $receipt_return_number);
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_purchase_reciept_returns_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');

        // $this->db->join('cberp_purchase_receipt_items', 'cberp_purchase_receipt_items.stockreciptid = cberp_purchase_reciept_returns_items.purchase_reciept_id AND cberp_purchase_receipt_items.product_id = cberp_purchase_reciept_returns_items.pid', 'left');

        // if(!empty($purchase_reciept_id) && $purchase_reciept_id>0)
        // {
        //     $this->db->where('cberp_stock_returns_items.tid', $id);
        // }
        $this->db->group_by('cberp_purchase_reciept_returns_items.product_code');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();

    }

    public function purchase_transactions($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where('tid', $id);
        $this->db->where('ext', 6);
        $query = $this->db->get();
        return $query->result_array();

    }

    public function purchase_delete($id)
    {

        $this->db->trans_start();

        $this->db->select('pid,qty');
        $this->db->from('cberp_stock_returns_items');
        $this->db->where('tid', $id);
        $query = $this->db->get();
        $prevresult = $query->result_array();

        $this->db->select('i_class');
        $this->db->from('cberp_purchase_reciept_returns');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $stock = $query->row_array();
        if (($stock['i_class'] != 2) OR ($stock['i_class'] == 2)) {
            foreach ($prevresult as $prd) {
                $amt = $prd['qty'];
                $this->db->set('qty', "qty+$amt", FALSE);
                $this->db->where('pid', $prd['pid']);
                $this->db->update('cberp_products');
            }
            $whr = array('id' => $id);
            // if ($this->aauth->get_user()->loc) {
            //     $whr = array('id' => $id, 'loc' => $this->aauth->get_user()->loc);
            // }
            $this->db->delete('cberp_purchase_reciept_returns', $whr);
            if ($this->db->affected_rows()) $this->db->delete('cberp_stock_returns_items', array('tid' => $id));
            if ($this->db->trans_complete()) {
                return true;
            } else {
                return false;
            }
        }
    }


    private function _get_datatables_query($type = 0)
    {
        
            $this->db->select('cberp_purchase_reciept_returns.id,cberp_purchase_reciept_returns.purchase_reciept_number,cberp_purchase_reciept_returns.receipt_return_number,cberp_purchase_reciept_returns.total,cberp_purchase_reciept_returns.return_status,cberp_suppliers.name,cberp_purchase_reciept_returns.approval_flag,cberp_purchase_reciept_returns.assigned_to,cberp_purchase_reciept_returns.payment_status,cberp_purchase_reciept_returns.return_date');
            $this->db->from($this->table);
            $this->db->join('cberp_suppliers', 'cberp_purchase_reciept_returns.supplier_id=cberp_suppliers.supplier_id', 'left');
            // $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.id=cberp_purchase_reciept_returns.purchase_reciept_id', 'left');
            // $this->db->where('cberp_purchase_reciept_returns.invoice_id IS NULL', null, false);
            //  $this->db->join('cberp_employees', 'cberp_employees.id=cberp_purchase_reciept_returns.assign_to', 'left');

        if ($this->input->post('start_date') && $this->input->post('end_date'))
        {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(cberp_purchase_reciept_returns.return_date) BETWEEN '$start_date' AND '$end_date'");

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

    function get_datatables($type = 0)
    {
        $this->_get_datatables_query($type);
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
        $this->db->from($this->table);

        // $this->db->where('cberp_purchase_reciept_returns.i_class', $type);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_purchase_reciept_returns.loc', $this->aauth->get_user()->loc);
        // }
        // elseif(!BDATA) { $this->db->where('cberp_purchase_reciept_returns.loc', 0); }
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
        $this->db->where('cberp_metadata.type', 4);
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

    //erp2024 04-10-2024
    public function merged_stock_return_details($id)
    {

        $this->db->select('cberp_purchase_orders.*,cberp_purchase_orders.id AS iid,SUM(cberp_purchase_orders.shipping + cberp_purchase_orders.ship_tax) AS shipping,cberp_suppliers.*,cberp_suppliers.supplier_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms,cberp_country.name as countryname,cberp_purchase_receipts.id as receiptid');
        $this->db->from($this->table);
        $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.purchase_id = cberp_purchase_orders.id');
        $this->db->join('cberp_suppliers', 'cberp_purchase_orders.csd = cberp_suppliers.supplier_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_suppliers.country', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_purchase_orders.term', 'left');
        $this->db->where('cberp_purchase_receipts.id', $id);
        $query = $this->db->get();
        return $query->row_array();

    }

    public function assigedemployee($id)
    {
        $this->db->select('cberp_employees.name,cberp_employees.sign,cberp_users.roleid,cberp_users.email,cberp_employees.phone');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    public function gethistory($id)
    {
        $this->db->select('cberp_purchase_return_logs.*,cberp_employees.name');
        $this->db->from('cberp_purchase_return_logs');  
        $this->db->join('cberp_employees','cberp_purchase_return_logs.performed_by=cberp_employees.id');
        $this->db->where('cberp_purchase_return_logs.purchase_return_id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }
     //erp2024 06-01-2025 detailed history log starts

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
     //erp2024 06-01-2025 detailed history log ends

     //erp2024 31-01-2025 load journal records
     public function purchase_return_journal_records($receipt_return_number)
     {
         $this->db->select('
             cberp_purchase_reciept_returns.transaction_number,
             cberp_transactions.debit,
             cberp_transactions.credit,
             cberp_transactions.date,
             cberp_transactions.acid,
             cberp_employees.name AS employee,
             cberp_accounts.holder,
             cberp_accounts.acn
         ');
         $this->db->from('cberp_purchase_reciept_returns');
         $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_purchase_reciept_returns.transaction_number');
         $this->db->join('cberp_employees', 'cberp_employees.id = cberp_transactions.eid', 'left');
         $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
         $this->db->where('cberp_purchase_reciept_returns.receipt_return_number', $receipt_return_number);
 
         $query = $this->db->get();
         $result = $query->result_array();
         return $result;
     }

   //erp2024 25-10-2024 starts   
   public function purchase_return_data($receipt_return_number)
   {
       $this->db->select('cberp_purchase_reciept_returns.*,cberp_purchase_reciept_returns.id AS iid,cberp_suppliers.*,cberp_suppliers.supplier_id AS cid,cberp_country.name as countryname');
       $this->db->from('cberp_purchase_reciept_returns');
       $this->db->where('cberp_purchase_reciept_returns.receipt_return_number', $receipt_return_number);
       $this->db->join('cberp_suppliers', 'cberp_purchase_reciept_returns.supplier_id = cberp_suppliers.supplier_id', 'left');
       $this->db->join('cberp_country', 'cberp_country.id = cberp_suppliers.country', 'left');
       $query = $this->db->get();
       return $query->row_array();
   }

   public function purchase_return_payments_received($receipt_return_number)
   {


       $this->db->select('
           cberp_purchase_reciept_returns.receipt_return_number as srv,
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
       $this->db->from('cberp_purchase_reciept_returns');
       $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_purchase_reciept_returns.receipt_return_number');
       $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number');
       $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_bank_transactions.trans_supplier_id', 'left');
       $this->db->join('cberp_accounts AS account_chart', 'account_chart.acn = cberp_bank_transactions.trans_chart_of_account_id');
       $this->db->join('cberp_accounts AS account_trans', 'account_trans.acn = cberp_bank_transactions.trans_account_id');
       $this->db->where('cberp_purchase_reciept_returns.receipt_return_number', $receipt_return_number);
       $this->db->where('cberp_payment_transaction_link.trans_type', 'Purchase Return');

       $query = $this->db->get();
       $result = $query->result_array();

       return $result;
   }

   public function purchase_receipt_products($purchase_reciept_number)
   {

    
       $this->db->select('cberp_purchase_receipt_items.*,cberp_purchase_receipt_items.account_code AS account_number,cberp_purchase_receipt_items.product_quantity_recieved AS qty,cberp_purchase_receipt_items.damaged_quantity AS damaged_qty,cberp_purchase_receipt_items.price,cberp_purchase_receipt_items.netamount AS subtotal,cberp_purchase_receipt_items.discountamount AS totaldiscount,cberp_purchase_receipt_items.purchase_reciept_number AS purchase_reciept_id,cberp_purchase_receipt_items.purchase_reciept_number,cberp_product_description.product_name AS product,cberp_products.product_code AS pid, cberp_products.product_code AS code, cberp_products.product_cost AS product_cost');
       $this->db->from('cberp_purchase_receipt_items');
       $this->db->join('cberp_products', 'cberp_products.product_code = cberp_purchase_receipt_items.product_code');
       $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
       $this->db->where('cberp_purchase_receipt_items.purchase_reciept_number', $purchase_reciept_number);
       $query = $this->db->get();
       return $query->result_array();
   }

   public function purchase_receipt_details($purchase_reciept_number)
   { 
       $this->db->select('cberp_purchase_orders.*,cberp_purchase_orders.id AS iid2,SUM(cberp_purchase_orders.shipping_charge + cberp_purchase_orders.shipping_tax) AS shipping, cberp_purchase_orders.purchase_number AS ponumber, cberp_suppliers.*,cberp_suppliers.supplier_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms,cberp_country.name as countryname,cberp_purchase_receipts.id as receiptid,cberp_purchase_receipts.purchase_reciept_number as receiptnumber,cberp_purchase_receipts.bill_number,cberp_purchase_receipts.bill_date,cberp_purchase_receipts.purchase_receipt_date,cberp_purchase_receipts.note,cberp_purchase_receipts.purchase_number,cberp_purchase_receipts.id AS purchase_reciept_id,cberp_purchase_receipts.purchase_reciept_number,cberp_purchase_orders.customer_id as supplier_id');
       $this->db->from('cberp_purchase_orders');
       $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.purchase_number = cberp_purchase_orders.purchase_number');
       $this->db->join('cberp_suppliers', 'cberp_purchase_orders.customer_id = cberp_suppliers.supplier_id', 'left');
       $this->db->join('cberp_country', 'cberp_country.id = cberp_suppliers.country', 'left');
       $this->db->join('cberp_terms', 'cberp_terms.id = cberp_purchase_orders.payment_terms', 'left');
       $this->db->where('cberp_purchase_receipts.purchase_reciept_number', $purchase_reciept_number);
       $query = $this->db->get();
       return $query->row_array();
   }

   public function get_purchase_order_by_field($fieldName, $fieldVal)
   {
       $this->db->select('receipt_return_number');
       $this->db->from('cberp_purchase_reciept_returns');
       $this->db->where($fieldName, $fieldVal);
       $query = $this->db->get();
       return $query->num_rows();
   }


   public function get_filter_count(){
    $startOfToday = date('Y-m-d 00:00:00');
    $endOfToday   = date('Y-m-d 23:59:59');
    $query = $this->db->query("
        SELECT 
            SUM(CASE WHEN return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_count,
            SUM(CASE WHEN return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_count,
            SUM(CASE WHEN return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_count,
            SUM(CASE WHEN return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_count,
            SUM(CASE WHEN return_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_count,

            SUM(CASE WHEN return_status = 'Draft' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_created_count,
            SUM(CASE WHEN return_status = 'Draft' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_created_count,
            SUM(CASE WHEN return_status = 'Draft' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_created_count,
            SUM(CASE WHEN return_status = 'Draft' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_created_count,
            SUM(CASE WHEN return_status = 'Draft' AND return_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_created_count,

            SUM(CASE WHEN return_status = 'Pending' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_partial_count,
            SUM(CASE WHEN return_status = 'Pending' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_partial_count,
            SUM(CASE WHEN return_status = 'Pending' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_partial_count,
            SUM(CASE WHEN return_status = 'Pending' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_partial_count,
            SUM(CASE WHEN return_status = 'Pending' AND return_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_partial_count,

            SUM(CASE WHEN return_status = 'Sent' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_paid_count,
            SUM(CASE WHEN return_status = 'Sent' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_paid_count,
            SUM(CASE WHEN return_status = 'Sent' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_paid_count,
            SUM(CASE WHEN return_status = 'Sent' AND return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_paid_count,
            SUM(CASE WHEN return_status = 'Sent' AND return_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_paid_count,

            SUM(CASE WHEN return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN total ELSE 0 END) AS yearly_total,
            SUM(CASE WHEN return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN total ELSE 0 END) AS quarterly_total,
            SUM(CASE WHEN return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN total ELSE 0 END) AS monthly_total,
            SUM(CASE WHEN return_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN total ELSE 0 END) AS weekly_total,
            SUM(CASE WHEN return_date BETWEEN '$startOfToday' AND '$endOfToday' THEN total ELSE 0 END) AS daily_total

        FROM 
            cberp_purchase_reciept_returns

    ");
    
        return $query->row();
    }
}
