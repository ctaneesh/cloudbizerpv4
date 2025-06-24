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

class Purchase_model extends CI_Model
{
    var $table = 'cberp_purchase_orders';
    var $column_order = array(null, 'cberp_purchase_orders.purchase_number', 'cberp_suppliers.name', 'cberp_purchase_orders.purchase_order_date', 'cberp_purchase_orders.order_total', 'cberp_purchase_orders.order_status','cberp_employees.name','cberp_purchase_orders.receipt_status', null);
    var $column_search = array('cberp_purchase_orders.purchase_number', 'cberp_suppliers.name', 'cberp_purchase_orders.purchase_order_date', 'cberp_purchase_orders.order_total','cberp_purchase_orders.order_status','cberp_employees.name','cberp_purchase_orders.receipt_status');
    var $order = array('cberp_purchase_orders.purchase_number' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    // public function lastpurchase()
    // {
    //     $this->db->select('MAX(id) + 1 as next_id');
    //     $this->db->from($this->table);
    //     $query = $this->db->get();
    //     $next_id = $query->row()->next_id;
    //     if ($query->num_rows() > 0) {
    //         return $next_id;
    //     } else {
    //         return 1001;
    //     }
        
    // }

    public function lastpurchase()
    {
        $this->db->select('purchase_number');
        $this->db->from($this->table);
        $this->db->where("purchase_number IS NOT NULL");
        $this->db->order_by('created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_purchase_number = $query->row()->purchase_number;
            $parts = explode('/', $last_purchase_number);
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
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        return $query->result_array();

    }
   

    public function purchase_details($purchase_number)
    {

        $this->db->select('cberp_purchase_orders.*,cberp_purchase_orders.purchase_number AS iid,SUM(cberp_purchase_orders.shipping_charge + cberp_purchase_orders.shipping_tax) AS shipping,cberp_suppliers.*,cberp_suppliers.supplier_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms,cberp_country.name as countryname,cberp_purchase_orders.discount as purchasediscount');
        $this->db->from($this->table);
        $this->db->where('cberp_purchase_orders.purchase_number', $purchase_number);
        $this->db->join('cberp_suppliers', 'cberp_purchase_orders.customer_id = cberp_suppliers.supplier_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_suppliers.country', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_purchase_orders.payment_terms', 'left');
        $query = $this->db->get();
        return $query->row_array();

    }

    public function purchase_products($purchase_number)
    {
        $this->db->select('cberp_purchase_order_items.*,cberp_products.product_code,cberp_product_description.product_name AS product');
        $this->db->from('cberp_purchase_order_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_purchase_order_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_purchase_order_items.purchase_number', $purchase_number);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }


    public function purchase_transactions($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where('tid', $id);
        $this->db->where('ext', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function purchase_delete($purchase_number)
    {
        $this->db->trans_start();
        $this->db->select('product_code,qty');
        $this->db->from('cberp_purchase_order_items');
        $this->db->where('purchase_number', $purchase_number);
        $query = $this->db->get();
        $prevresult = $query->result_array();
        foreach ($prevresult as $prd) {
            $amt = $prd['qty'];
            $this->db->set('qty', "qty-$amt", FALSE);
            $this->db->where('product_code', $prd['product_code']);
            $this->db->update('cberp_products');
        }
        $whr = array('id' => $id);
        // if ($this->aauth->get_user()->loc) {
        //     $whr = array('id' => $id, 'loc' => $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //        $whr = array('id' => $id, 'loc' =>0);
        // }
        $this->db->delete('cberp_purchase_orders', $whr);
        if ($this->db->affected_rows()) $this->db->delete('cberp_purchase_order_items', array('purchase_number' => $purchase_number));
        if ($this->db->trans_complete()) {
            return true;
        } else {
            return false;
        }
    }


    private function _get_datatables_query()
    {
        $this->db->select('cberp_purchase_orders.purchase_number as id,cberp_purchase_orders.order_status,cberp_purchase_orders.purchase_number,cberp_purchase_orders.purchase_order_date,cberp_purchase_orders.duedate,cberp_purchase_orders.order_total,cberp_purchase_orders.order_status,cberp_purchase_orders.approval_flag,cberp_suppliers.name,cberp_purchase_orders.assigned_to,cberp_employees.name as assigned_person,cberp_purchase_orders.purchase_type,cberp_purchase_orders.receipt_status');
        $this->db->from($this->table);
        $this->db->join('cberp_suppliers', 'cberp_purchase_orders.customer_id=cberp_suppliers.supplier_id', 'left');
        $this->db->join('cberp_employees', 'cberp_employees.id=cberp_purchase_orders.assigned_to', 'left');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_purchase_orders.loc', $this->aauth->get_user()->loc);
        // }
        // elseif(!BDATA) { $this->db->where('cberp_purchase_orders.loc', 0); }
        if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
        {
            $this->db->where('DATE(cberp_purchase_orders.purchase_order_date) >=', datefordatabase($this->input->post('start_date')));
            $this->db->where('DATE(cberp_purchase_orders.purchase_order_date) <=', datefordatabase($this->input->post('end_date')));
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
        $this->db->from($this->table);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_purchase_orders.loc', $this->aauth->get_user()->loc);
        // }
        // elseif(!BDATA) { $this->db->where('cberp_purchase_orders.loc', 0); }
        return $this->db->count_all_results();
    }


    public function billingterms()
    {
        $this->db->select('id,title');
        $this->db->from('cberp_terms');
        $this->db->where('type', 4);
        $this->db->or_where('type', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function currencies()
    {
        $this->db->select('code,symbol,rate,id');
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
    
    // erp2024 01-10-2024 start
    public function check_quote_existornot($purchase_number)
    {
        $this->db->select('purchase_number');
        $this->db->from('cberp_purchase_orders');
        $this->db->where('purchase_number', $purchase_number);
        $query = $this->db->get();
        // die($this->db->last_query());
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['purchase_number']);
        } else {
            return 0;
        }

    }
    public function check_product_existornot($purchase_number,$product_code)
    {
        $this->db->select('id');
        $this->db->from('cberp_purchase_order_items');
        $this->db->where('purchase_number', $purchase_number);
        $this->db->where('product_code', $product_code);
        $query = $this->db->get();
      
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }

    }
    public function check_approval_existornot($tid)
    {
        $this->db->select('id');
        $this->db->from('authorization_history');
        $this->db->where('function_id', $tid);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['id']);
        } else {
            return 0;
        }

    }
    // erp2024 01-10-2024 ends

    //erp2024 04-10-2024 starts
    
    public function purchase_receipt_products($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_purchase_receipt_items');
        $this->db->where('stockreciptid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function purchase_receipt_details($id)
    {

        $this->db->select('cberp_purchase_orders.*,cberp_purchase_orders.id AS iid,SUM(cberp_purchase_orders.shipping + cberp_purchase_orders.ship_tax) AS shipping,cberp_suppliers.*,cberp_suppliers.supplier_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms,cberp_country.name as countryname,cberp_purchase_receipts.id as receiptid,cberp_purchase_receipts.srv as receiptnumber,cberp_purchase_receipts.bill_number,cberp_purchase_receipts.bill_date,cberp_purchase_receipts.srvdate,cberp_purchase_receipts.note');
        $this->db->from($this->table);
        $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.purchase_id = cberp_purchase_orders.id');
        $this->db->join('cberp_suppliers', 'cberp_purchase_orders.customer_id = cberp_suppliers.supplier_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_suppliers.country', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_purchase_orders.term', 'left');
        $this->db->where('cberp_purchase_receipts.id', $id);
        $query = $this->db->get();
        return $query->row_array();

    }

   //erp2024 04-10-2024 ends
   //erp2024 25-10-2024 starts
   
   public function purchase_receipt_data($purchase_reciept_number)
   {

       $this->db->select('cberp_purchase_receipts.*,cberp_purchase_receipts.id AS iid,cberp_suppliers.*,cberp_suppliers.supplier_id AS cid,cberp_country.name as countryname');
       $this->db->from('cberp_purchase_receipts');
       $this->db->where('cberp_purchase_receipts.purchase_reciept_number', $purchase_reciept_number);
       $this->db->join('cberp_suppliers', 'cberp_purchase_receipts.supplier_id = cberp_suppliers.supplier_id', 'left');
       $this->db->join('cberp_country', 'cberp_country.id = cberp_suppliers.country', 'left');
       $query = $this->db->get();
       return $query->row_array();

   }
   //erp2024 25-10-2024 ends
   public function check_purchase_receipt_ispaid($receiptid)
   {
        $this->db->select('payment_transaction_number,payment_status');
        $this->db->from("cberp_purchase_receipts");
        $this->db->where('id', $receiptid);
        $query = $this->db->get();
        return $query->row_array();  
   }

   public function reset_purchase_payment_accounts($transaction_number)
    {
       
        // Fetch the data
        $this->db->select('cberp_transactions.acid, cberp_transactions.credit AS creditamount, cberp_transactions.debit AS debitamount');
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_transactions.transaction_number', $transaction_number);
        $query = $this->db->get();
        $data = $query->result_array();  
      
        if ($data) {
            foreach ($data as $row) {
                $debitamount = $row['debitamount'];
                $creditamount = $row['creditamount'];
                $acn = $row['acid'];
               
                if ($debitamount>0) {
                    $this->db->set('lastbal', "lastbal - $debitamount", FALSE);
                    $this->db->where('acn', $acn);
                    $this->db->update('cberp_accounts');

                    $this->db->set('trans_amount', "trans_amount - $debitamount", FALSE);
                    $this->db->where('from_trans_number', $transaction_number);
                    $this->db->update('cberp_bank_transactions');


                    // $this->db->update('cberp_transactions',['credit'=>$debitamount,'debit'=>$creditamount],['transaction_number'=>$transaction_number,'acid'=>$acn]);
                }
                else
                {
                    $this->db->set('lastbal', "lastbal + $creditamount", FALSE);
                    $this->db->where('acn', $acn);
                    $this->db->update('cberp_accounts');
                    // $this->db->update('cberp_transactions',['credit'=>$debitamount,'debit'=>$creditamount],['transaction_number'=>$transaction_number,'acid'=>$acn]);
                }
            }
            $this->db->delete('cberp_transactions',['transaction_number'=>$transaction_number]);
        }

        // Optionally, return the fetched data if needed for debugging or further processing
        // return $data;
    }
    
    public function reset_credit_accounts($transaction_number)
    {
        // Fetch the data
        $this->db->select('cberp_transactions.acid, cberp_transactions.account, cberp_transactions.credit AS creditamount');
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        // $this->db->where('account IS NULL', null, false);
        $this->db->where('cberp_transactions.transaction_number', $transaction_number);
        // $this->db->where('cberp_accounts.default_flg', '1');
        $query = $this->db->get();
        $data = $query->result_array();  
        
        // Group the data by 'acid' and sum the 'creditamount'
        $groupedData = [];
        foreach ($data as $row) {
            if (isset($groupedData[$row['acid']])) {
                $groupedData[$row['acid']]['creditamount'] += $row['creditamount']; // Sum the creditamount
            } else {
                $groupedData[$row['acid']] = [
                    'acid' => $row['acid'],
                    'creditamount' => $row['creditamount']
                ];
            }
        }
    
        $batchSize = 500; // Define the batch size
        
        // Process the grouped data in chunks
        for ($i = 0; $i < count($groupedData); $i += $batchSize) {
            $chunk = array_slice($groupedData, $i, $batchSize);
            $sql = "UPDATE cberp_accounts SET lastbal = CASE";
            
            // Iterate over the grouped data
            foreach ($chunk as $row) {
                $sql .= " WHEN acn = '{$row['acid']}' THEN lastbal + {$row['creditamount']}";
            }
    
            // Add the WHERE condition for the IN clause (with acid wrapped in single quotes)
            $sql .= " END WHERE acn IN ('" . implode("','", array_column($chunk, 'acid')) . "')";
            // Execute the batch update for the current chunk
            $this->db->query($sql);
            // echo $sql."\n<br>";
           
        }
    }
    public function reset_debit_accounts($transaction_number)
    {
     
        // Fetch the data
        $this->db->select('cberp_transactions.acid, cberp_transactions.account, cberp_transactions.debit AS debitamount');
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_transactions.transaction_number', $transaction_number);
        // $this->db->where('cberp_accounts.default_flg','0');
        $query = $this->db->get();
        $data = $query->result_array();  
        // Group the data by 'acid' and sum the 'debitamount'
        $groupedData = [];
        foreach ($data as $row) {
            if (isset($groupedData[$row['acid']])) {
                $groupedData[$row['acid']]['debitamount'] += $row['debitamount']; // Sum the debitamount
            } else {
                $groupedData[$row['acid']] = [
                    'acid' => $row['acid'],
                    'debitamount' => $row['debitamount']
                ];
            }
        }

        $batchSize = 500; // Define the batch size
        
        // Process the grouped data in chunks
        for ($i = 0; $i < count($groupedData); $i += $batchSize) {
            $chunk = array_slice($groupedData, $i, $batchSize);
            $sql = "UPDATE cberp_accounts SET lastbal = CASE";
            
            // Iterate over the grouped data
            foreach ($chunk as $row) {
                $sql .= " WHEN acn = '{$row['acid']}' THEN lastbal - {$row['debitamount']}";
            }

            // Add the WHERE condition for the IN clause (with acid wrapped in single quotes)
            $sql .= " END WHERE acn IN ('" . implode("','", array_column($chunk, 'acid')) . "')";
           
            // Execute the batch update for the current chunk
            $this->db->query($sql);
            // Optionally output the SQL query for debugging
            //  echo $sql."\n<br>";
          
           
        }
    }
    public function reset_transaction_amounts($transactionNumber)
    {
        $this->db->select('id, debit, credit, transaction_number');
        $this->db->where('transaction_number', $transactionNumber);
        $query = $this->db->get('cberp_transactions');
        $transactions = $query->result();
        if($transactions){
            $updateData = [];
            foreach ($transactions as $transaction) {
                $updateData[] = [
                    'id' => $transaction->id,
                    'debit' => $transaction->credit,
                    'credit' => $transaction->debit
                ];
            }
            $this->db->update_batch('cberp_transactions', $updateData, 'id');
        }
    }
    public function transaction_number_by_id($receipt_id)
    {
        $this->db->select('transaction_number');
        $this->db->from('cberp_purchase_receipts');
        $this->db->where('id',  $receipt_id);
        $query = $this->db->get();
        $data = $query->row_array();  
        return $data;
    }

    public function purchase_receipt_data_by_id($trans_ref_number)
    {
        $this->db->select(
            'cberp_bank_transactions.trans_ref_number,
             cberp_bank_transactions.trans_amount,
             cberp_bank_transactions.trans_account_id AS banktransfer_accountid,
             cberp_bank_transactions.trans_chart_of_account_id,
             cberp_bank_transactions.trans_supplier_id,
             cberp_payment_transaction_link.transaction_number,
             cberp_payment_transaction_link.bank_transaction_number,
             cberp_purchase_receipts.id AS receiptid,
             cberp_purchase_receipts.purchase_reciept_number'
        );
        $this->db->from('cberp_bank_transactions');
        $this->db->join(
            'cberp_payment_transaction_link',
            'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number',
            'inner'
        );
        $this->db->join(
            'cberp_purchase_receipts',
            'cberp_purchase_receipts.purchase_reciept_number = cberp_payment_transaction_link.trans_type_number',
            'inner'
        );
        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);
        $query = $this->db->get();
        return($query->row_array());
        
    }
    public function gethistory($tid)
    {
        $this->db->select('cberp_purchase_order_logs.*,cberp_employees.name');
        $this->db->from('cberp_purchase_order_logs');  
        $this->db->join('cberp_employees','cberp_purchase_order_logs.performed_by=cberp_employees.id');
        $this->db->where('cberp_purchase_order_logs.purchase_order_id',$tid);
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
         $this->db->order_by('cberp_master_log.seqence_number', 'ASC');
         $query = $this->db->get();
         return $query->result_array();
     }
     //erp2024 09-01-2025 detailed history log ends

     public function last_purchase_price($product_code)
     {
         $this->db->select('cberp_purchase_order_items.price');
         $this->db->from('cberp_purchase_order_items');
         $this->db->join('cberp_purchase_orders','cberp_purchase_orders.purchase_number=cberp_purchase_order_items.purchase_number');
         $this->db->where('cberp_purchase_order_items.product_code', $product_code);         
         $this->db->where('cberp_purchase_orders.order_status !=', 'Dummy');         
         $this->db->order_by('cberp_purchase_orders.created_by', 'DESC');
         $this->db->limit(1);
         $query = $this->db->get();
         if ($query->num_rows() > 0) {
            // $cberp_purchase_order_items = $query->row()->price;
            return($query->row()->price);
         }
     }

     public function get_data_from_average_price_table()
     {
  
         $this->db->select('*');
         $this->db->from('average_price_table');
         $this->db->where('product_id', '1234');
         $this->db->order_by('date', 'ASC');
         $query = $this->db->get();
         return $query->result();
  
     }

     
    // public function get_filter_count(){        
    //     $startOfToday = date('Y-m-d 00:00:00');
    //     $endOfToday   = date('Y-m-d 23:59:59');

    //     $query = $this->db->query("
    //         SELECT 
    //             SUM(CASE WHEN purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_count,
    //             SUM(CASE WHEN purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_count,
    //             SUM(CASE WHEN purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_count,
    //             SUM(CASE WHEN purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_count,
    //             SUM(CASE WHEN purchase_order_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_count,

    //             SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_created_count,
    //             SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_created_count,
    //             SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_created_count,
    //             SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_created_count,
    //             SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_created_count,

    //             SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_draft_count,
    //             SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_draft_count,
    //             SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_draft_count,
    //             SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_draft_count,
    //             SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_draft_count,

    //             SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_approved_count,
    //             SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_approved_count,
    //             SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_approved_count,
    //             SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_approved_count,
    //             SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_approved_count,

    //             SUM(CASE WHEN order_status = 'Sent'  AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_sent_count,
    //             SUM(CASE WHEN order_status = 'Sent'  AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_sent_count,
    //             SUM(CASE WHEN order_status = 'Sent'  AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_sent_count,
    //             SUM(CASE WHEN order_status = 'Sent'  AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_sent_count,
    //             SUM(CASE WHEN order_status = 'Sent'  AND purchase_order_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_sent_count,

    //             SUM(CASE WHEN order_status= 'Dummy' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_dummy_count,
    //             SUM(CASE WHEN order_status= 'Dummy' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_dummy_count,
    //             SUM(CASE WHEN order_status= 'Dummy' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_dummy_count,
    //             SUM(CASE WHEN order_status= 'Dummy' AND purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_dummy_count,
    //             SUM(CASE WHEN order_status= 'Dummy' AND purchase_order_date BETWEEN '$startOfToday' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_dummy_count,

    //             SUM(CASE WHEN purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 YEAR) AND '$endOfToday' THEN order_total ELSE 0 END) AS yearly_total,
    //             SUM(CASE WHEN purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 QUARTER) AND '$endOfToday' THEN order_total ELSE 0 END) AS quarterly_total,
    //             SUM(CASE WHEN purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 MONTH) AND '$endOfToday' THEN order_total ELSE 0 END) AS monthly_total,
    //             SUM(CASE WHEN purchase_order_date BETWEEN DATE_SUB('$startOfToday', INTERVAL 1 WEEK) AND '$endOfToday' THEN order_total ELSE 0 END) AS weekly_total,
    //             SUM(CASE WHEN purchase_order_date BETWEEN '$startOfToday' AND '$endOfToday' THEN order_total ELSE 0 END) AS daily_total

    //         FROM cberp_purchase_orders
    //     ");
    //     return $query->row();
    // }

    public function get_filter_count($ranges)
    {
        $today        = date('Y-m-d 00:00:00');
        $endOfToday   = date('Y-m-d 23:59:59');

        $startYear    = $ranges['year'];
        $startQuarter = $ranges['quarter'];
        $startMonth   = $ranges['month'];
        $startWeek    = $ranges['week'];
        
        $query = $this->db->query("
            SELECT 
                -- Total count
                SUM(CASE WHEN purchase_order_date BETWEEN '$startYear' AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN purchase_order_date BETWEEN '$startQuarter' AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN purchase_order_date BETWEEN '$startMonth' AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN purchase_order_date BETWEEN '$startWeek' AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN purchase_order_date BETWEEN '$today' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_count,

                -- Status: Pending
                SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN '$startYear' AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN '$startQuarter' AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN '$startMonth' AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN '$startWeek' AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN order_status = 'Pending' AND purchase_order_date BETWEEN '$today' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_created_count,

                -- Status: Draft
                SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN '$startYear' AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN '$startQuarter' AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN '$startMonth' AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN '$startWeek' AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN order_status = 'Draft' AND purchase_order_date BETWEEN '$today' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- Status: Approved
                SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN '$startYear' AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_approved_count,
                SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN '$startQuarter' AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_approved_count,
                SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN '$startMonth' AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_approved_count,
                SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN '$startWeek' AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_approved_count,
                SUM(CASE WHEN order_status = 'Approved' AND purchase_order_date BETWEEN '$today' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_approved_count,

                -- Status: Sent
                SUM(CASE WHEN order_status = 'Sent' AND purchase_order_date BETWEEN '$startYear' AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_sent_count,
                SUM(CASE WHEN order_status = 'Sent' AND purchase_order_date BETWEEN '$startQuarter' AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_sent_count,
                SUM(CASE WHEN order_status = 'Sent' AND purchase_order_date BETWEEN '$startMonth' AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_sent_count,
                SUM(CASE WHEN order_status = 'Sent' AND purchase_order_date BETWEEN '$startWeek' AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_sent_count,
                SUM(CASE WHEN order_status = 'Sent' AND purchase_order_date BETWEEN '$today' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_sent_count,

                -- Status: Dummy
                SUM(CASE WHEN order_status = 'Dummy' AND purchase_order_date BETWEEN '$startYear' AND '$endOfToday' THEN 1 ELSE 0 END) AS yearly_dummy_count,
                SUM(CASE WHEN order_status = 'Dummy' AND purchase_order_date BETWEEN '$startQuarter' AND '$endOfToday' THEN 1 ELSE 0 END) AS quarterly_dummy_count,
                SUM(CASE WHEN order_status = 'Dummy' AND purchase_order_date BETWEEN '$startMonth' AND '$endOfToday' THEN 1 ELSE 0 END) AS monthly_dummy_count,
                SUM(CASE WHEN order_status = 'Dummy' AND purchase_order_date BETWEEN '$startWeek' AND '$endOfToday' THEN 1 ELSE 0 END) AS weekly_dummy_count,
                SUM(CASE WHEN order_status = 'Dummy' AND purchase_order_date BETWEEN '$today' AND '$endOfToday' THEN 1 ELSE 0 END) AS daily_dummy_count,

                -- Totals
                SUM(CASE WHEN purchase_order_date BETWEEN '$startYear' AND '$endOfToday' THEN order_total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN purchase_order_date BETWEEN '$startQuarter' AND '$endOfToday' THEN order_total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN purchase_order_date BETWEEN '$startMonth' AND '$endOfToday' THEN order_total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN purchase_order_date BETWEEN '$startWeek' AND '$endOfToday' THEN order_total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN purchase_order_date BETWEEN '$today' AND '$endOfToday' THEN order_total ELSE 0 END) AS daily_total

            FROM cberp_purchase_orders
        ");

        return $query->row();
    }

}
