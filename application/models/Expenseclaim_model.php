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

class Expenseclaim_model extends CI_Model
{
    var $table = 'cberp_expense_claims';
    var $column_order = array(null,'transcat_id','transcat_name','transcat_parentid','status', null);
    var $column_search = array('transcat_id','transcat_name','transcat_parentid','status');
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    public function load_banking_headers()
    {
        $this->db->from('cberp_bank_transtype');
        $this->db->where('status', 'Active');
        $this->db->order_by('transtype_name', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    private function _get_datatables_query()
    {

        $this->db->select('cberp_expense_claims.*');
        $this->db->from('cberp_expense_claims');
        // $this->db->join('cberp_bank_transtype', 'cberp_bank_transtype.transtype_id = cberp_bank_transcategory.transtype_id');
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

    function get_datatables($dategap="")
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
		// $this->db->where('cberp_coa_types.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function load_category_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_bank_transcategory');
        $this->db->where('id', $id);   
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function load_approvers()
    {
        $this->db->select('cberp_employees.id,cberp_employees.name,cberp_users.roleid');
        $this->db->from('cberp_employees');
        $this->db->join('cberp_users', 'cberp_users.id = cberp_employees.id');
        $this->db->order_by('cberp_employees.name', 'ASC');   
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function expense_clam_details_by_number($claim_number)
    {
        $this->db->select('cberp_expense_claims.*, cberp_suppliers.name AS supplier, cberp_suppliers.phone AS supplierphone, cberp_suppliers.email AS supplieremail, cberp_suppliers.city AS suppliercity, cberp_suppliers.region AS supplierregion, cberp_suppliers.address AS supplieraddress,cberp_suppliers.company,cberp_suppliers.contact_person, cberp_suppliers.contact_phone1, cberp_suppliers.contact_email1,cberp_suppliers.supplier_id as supplierid, employeetbl.name as employee,approvartbl.name as approver');
        $this->db->from('cberp_expense_claims');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_expense_claims.supplier_id');
        $this->db->join('cberp_employees as employeetbl', 'employeetbl.id = cberp_expense_claims.employee_id');
        $this->db->join('cberp_employees as approvartbl', 'approvartbl.id = cberp_expense_claims.approver_id');
        $this->db->where('cberp_expense_claims.claim_number', $claim_number);
        $query = $this->db->get();
        return $query->row_array();

    }

    public function load_expnseclaim_journel_by_number($claim_number)
    {
        $this->db->select('cberp_transactions.acid, 
                   cberp_transactions.transaction_number, 
                   cberp_transactions.debit AS debitamount, 
                   cberp_transactions.credit AS creditamount, 
                   cberp_accounts.holder');
        $this->db->from('cberp_expense_claims');
        $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_expense_claims.transaction_number');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_expense_claims.transaction_number', $claim_number);

        $query = $this->db->get();

        return $query->result_array();

    }
    public function load_expnseclaim_items_by_number($claim_number)
    {
        $this->db->select('cberp_expense_claim_items.*,cberp_products.product_code,cberp_products.product_name,cberp_expense_claims.claim_discount_amount,cberp_expense_claims.claim_discount,cberp_expense_claims.discount_type,cberp_expense_claims.claim_total,cberp_expense_claims.claim_subtotal');
        $this->db->from('cberp_expense_claim_items');
        $this->db->join('cberp_expense_claims', 'cberp_expense_claims.claim_number = cberp_expense_claim_items.claim_number');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_expense_claim_items.product_code');
        $this->db->where('cberp_expense_claim_items.claim_number', $claim_number);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function load_expnseclaim_payment_transactions($claim_number)
    {
        $this->db->select('cberp_payment_transaction_link.bank_transaction_number,cberp_payment_transaction_link.transaction_number, cberp_bank_transactions.trans_date, cberp_bank_transactions.trans_payment_method, cberp_bank_transactions.trans_amount,cberp_bank_transactions.trans_ref_number,cberp_bank_transactions.trans_customer_id,cberp_bank_transactions.trans_supplier_id');
        $this->db->from('cberp_expense_claims');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_expense_claims.claim_number');
        $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number');
        $this->db->where('cberp_expense_claims.claim_number', $claim_number);
        
        $query = $this->db->get(); 
        return $query->result_array();
        

    }
    
    public function get_expense_payment_tarnsaction_by_claimnumber($claim_number)
    {
        $this->db->select('cberp_payment_transaction_link.bank_transaction_number,cberp_payment_transaction_link.transaction_number');
        $this->db->from('cberp_payment_transaction_link');
        $this->db->where('cberp_payment_transaction_link.trans_type_number', $claim_number);
        
        $query = $this->db->get(); 
        return $query->row_array();
        

    }
    
    public function load_trasnsation_numbers_by_reference_number($trans_ref_number)
    {
        $this->db->select('cberp_payment_transaction_link.bank_transaction_number,cberp_payment_transaction_link.transaction_number,cberp_payment_transaction_link.trans_type_number, cberp_bank_transactions.trans_date, cberp_bank_transactions.trans_payment_method, cberp_bank_transactions.trans_amount, cberp_bank_transactions.trans_ref_number, cberp_bank_transactions.trans_account_id, cberp_bank_transactions.trans_chart_of_account_id');
        $this->db->from('cberp_expense_claims');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_expense_claims.claim_number');
        $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number');
        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function get_claim_items_details_by_number($claim_number)
    {
        $this->db->select('cberp_expense_claim_items.*,cberp_products.product_name');
        $this->db->from('cberp_expense_claim_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_expense_claim_items.product_code');
        $this->db->where('cberp_expense_claim_items.claim_number', $claim_number);
        
        $query = $this->db->get(); 
        return $query->result_array();

    }

    public function get_transaction_details_by_number($transaction_number, $type)
    {   
        $this->db->select('acid');
        $this->db->from('cberp_transactions');
        $this->db->where('transaction_number', $transaction_number);        
        $this->db->where('type', $type);        
        $query = $this->db->get(); 
        echo $this->db->last_query();
        $result = $query->row();
        return $result ? $result->acid : null; 
    }
    
    



}
