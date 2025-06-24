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

class Transactions_model extends CI_Model
{
    var $table = 'cberp_transactions';
    var $column_order = array('id', 'date', 'acid', 'debit', 'credit', 'payer', 'method'); // Orderable columns
    var $column_search = array('id', 'account', 'payer'); // Columns for search
    var $order = array('date' => 'desc'); // Default order
    var $opt = ''; // Option for type filtering
    

    private function _get_datatables_query()
    {
        $this->db->select('cberp_transactions.*, cberp_transactions.id as id');
        $this->db->from($this->table);

        // Apply type filter based on the option
        switch ($this->opt) {
            case 'income':
                $this->db->where('type', 'Income');
                break;
            case 'expense':
                $this->db->where('type', 'Expense');
                break;
        }

        // Date range filter if provided
        if ($this->input->post('start_date') && $this->input->post('end_date')) {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(cberp_transactions.date) BETWEEN '$start_date' AND '$end_date'");
        }

        $i = 0;
        if ($this->input->post('search')['value']) {
            // Search query logic
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

       // Order logic
    // if (isset($_POST['order'])) {
    //     // Apply order from DataTable request
    //     $this->db->order_by(
    //         $this->column_order[$_POST['order']['0']['column']],
    //         $_POST['order']['0']['dir'],'DESC'
    //     );
    // } else {
        // Apply default order if no custom order is provided
        $this->db->order_by('id', 'DESC');
    // }
    }


    function get_datatables($opt = 'all')
    {
        $this->opt = $opt;
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
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
        switch ($this->opt) {
            case 'income':
                $this->db->where('type', 'Income');
                break;
            case 'expense':
                $this->db->where('type', 'Expense');
                break;
        }
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // }

        return $this->db->count_all_results();
    }



    public function categories()
    {
        $this->db->select('*');
        $this->db->from('cberp_trans_cat');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function acc_list()
    {
        $this->db->select('id,acn,holder');
        $this->db->from('cberp_accounts');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function addcat($name)
    {
        $data = array(
            'name' => $name
        );

        return $this->db->insert('cberp_trans_cat', $data);
    }

    public function addtrans($payer_id, $payer_name, $pay_acc, $date, $debit, $credit, $pay_type, $pay_cat, $paymethod, $note, $eid, $loc = 0, $ty = 0)
    {

        if ($pay_acc > 0) {

            $this->db->select('holder');
            $this->db->from('cberp_accounts');
            $this->db->where('id', $pay_acc);
            // if ($this->aauth->get_user()->loc) {
            //     $this->db->group_start();
            //     $this->db->where('loc', $this->aauth->get_user()->loc);
            //     if (BDATA) $this->db->or_where('loc', 0);
            //     $this->db->group_end();
            // } elseif (!BDATA) {
            //     $this->db->where('loc', 0);
            // }
            $query = $this->db->get();
            $account = $query->row_array();

            if ($account) {
                $data = array(
                    'payerid' => $payer_id,
                    'payer' => $payer_name,
                    'acid' => $pay_acc,
                    'account' => $account['holder'],
                    'date' => $date,
                    'debit' => $debit,
                    'credit' => $credit,
                    'type' => $pay_type,
                    'cat' => $pay_cat,
                    'method' => $paymethod,
                    'eid' => $eid,
                    'note' => $note,
                    'ext' => $ty,
                    'loc' => $loc
                );
                $amount = $credit - $debit;
                $this->db->set('lastbal', "lastbal+$amount", FALSE);
                $this->db->where('id', $pay_acc);
                $this->db->update('cberp_accounts');

                return $this->db->insert('cberp_transactions', $data);
            }
        }
    }

    public function addtransfer($pay_acc, $pay_acc2, $amount, $eid, $loc = 0)
    {

        if ($pay_acc > 0) {

            $this->db->select('holder');
            $this->db->from('cberp_accounts');
            $this->db->where('id', $pay_acc);
            // if ($this->aauth->get_user()->loc) {
            //     $this->db->group_start();
            //     $this->db->where('loc', $this->aauth->get_user()->loc);
            //     if (BDATA) $this->db->or_where('loc', 0);
            //     $this->db->group_end();
            // } elseif (!BDATA) {
            //     $this->db->where('loc', 0);
            // }
            $query = $this->db->get();
            $account = $query->row_array();
            $this->db->select('holder');
            $this->db->from('cberp_accounts');
            $this->db->where('id', $pay_acc2);
            // if ($this->aauth->get_user()->loc) {
            //     $this->db->group_start();
            //     $this->db->where('loc', $this->aauth->get_user()->loc);
            //     if (BDATA) $this->db->or_where('loc', 0);
            //     $this->db->group_end();
            // } elseif (!BDATA) {
            //     $this->db->where('loc', 0);
            // }
            $query = $this->db->get();
            $account2 = $query->row_array();

            if ($account2) {
                $data = array(
                    'payerid' => '',
                    'payer' => '',
                    'acid' => $pay_acc2,
                    'account' => $account2['holder'],
                    'date' => date('Y-m-d'),
                    'debit' => 0,
                    'credit' => $amount,
                    'type' => 'Transfer',
                    'cat' => '',
                    'method' => '',
                    'eid' => $eid,
                    'note' => 'Transferred by ' . $account['holder'],
                    'ext' => 9,
                    'loc' => $loc
                );
                $this->db->insert('cberp_transactions', $data);


                $this->db->set('lastbal', "lastbal+$amount", FALSE);
                $this->db->where('id', $pay_acc2);
                $this->db->update('cberp_accounts');
                $datec = date('Y-m-d');

                $data = array(
                    'payerid' => '',
                    'payer' => '',
                    'acid' => $pay_acc,
                    'account' => $account['holder'],
                    'date' => $datec,
                    'debit' => $amount,
                    'credit' => 0,
                    'type' => 'Transfer',
                    'cat' => '',
                    'method' => '',
                    'eid' => $eid,
                    'note' => 'Transferred to ' . $account2['holder'],
                    'ext' => 9,
                    'loc' => $loc
                );

                $this->db->set('lastbal', "lastbal-$amount", FALSE);
                $this->db->where('id', $pay_acc);
                $this->db->update('cberp_accounts');

                return $this->db->insert('cberp_transactions', $data);
            }
        }
    }


    public function delt($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $this->db->where('id', $id);
        $query = $this->db->get();
        $trans = $query->row_array();

        $amt = $trans['credit'] - $trans['debit'];
        $this->db->set('lastbal', "lastbal-$amt", FALSE);
        $this->db->where('id', $trans['acid']);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $this->db->update('cberp_accounts');

        if ($trans['tid'] > 0 && $trans['ext'] == 0) {
            $crd = $trans['credit'];
            $this->db->set('pamnt', "pamnt-$crd", FALSE);
            $this->db->set('status', "partial");
            $this->db->where('id', $trans['tid']);
            $this->db->update('cberp_invoices');
        }
        if ($trans['tid'] > 0 && $trans['ext'] == 1) {
            $crd = $trans['debit'];
            $this->db->set('pamnt', "pamnt-$crd", FALSE);
            $this->db->set('status', "partial");
            $this->db->where('id', $trans['tid']);
            $this->db->update('cberp_purchase_orders');
        }
        $this->db->delete('cberp_transactions', array('id' => $id));
        $alert = $this->custom->api_config(66);
        if ($alert['key2'] == 1) {
            $this->load->model('communication_model');
            $subject = $trans['payer'] . ' ' . $this->lang->line('DELETED');
            $body = $subject . '<br> ' . $this->lang->line('Credit') . ' ' . $this->lang->line('Amount') . ' ' . $trans['credit'] . '<br> ' . $this->lang->line('Debit') . ' ' . $this->lang->line('Amount') . ' ' . $trans['debit'] . '<br> ID# ' . $trans['id'];
            $out = $this->communication_model->send_corn_email($alert['url'], $alert['url'], $subject, $body, false, '');
        }
        return array('status' => 'Success', 'message' => $this->lang->line('DELETED'));


    }

    public function view($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where('id', $id);

        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function cview($id, $ext = 0)
    {

        if ($ext == 1) {
            $this->db->select('*');
            $this->db->from('cberp_suppliers');
            $this->db->where('id', $id);
            // if ($this->aauth->get_user()->loc) {
            //     $this->db->group_start();
            //     $this->db->where('loc', $this->aauth->get_user()->loc);
            //     if (BDATA) $this->db->or_where('loc', 0);
            //     $this->db->group_end();
            // } elseif (!BDATA) {
            //     $this->db->where('loc', 0);
            // }
            $query = $this->db->get();
            return $query->row_array();
        } elseif ($ext == 4) {
            $this->db->select('cberp_employees.*,cberp_users.email');
            $this->db->from('cberp_employees');
            $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
            $this->db->where('cberp_employees.id', $id);
            // if ($this->aauth->get_user()->loc) {
            //     $this->db->group_start();
            //     $this->db->where('loc', $this->aauth->get_user()->loc);
            //     if (BDATA) $this->db->or_where('loc', 0);
            //     $this->db->group_end();
            // } elseif (!BDATA) {
            //     $this->db->where('loc', 0);
            // }
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('*');
            $this->db->from('cberp_customers');
            $this->db->where('id', $id);
            // if ($this->aauth->get_user()->loc) {
            //     $this->db->group_start();
            //     $this->db->where('loc', $this->aauth->get_user()->loc);
            //     if (BDATA) $this->db->or_where('loc', 0);
            //     $this->db->group_end();
            // } elseif (!BDATA) {
            //     $this->db->where('loc', 0);
            // }
            $query = $this->db->get();
            return $query->row_array();
        }

    }

    public function cat_details($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_trans_cat');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
       public function cat_details_name($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_trans_cat');
        $this->db->where('name', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function cat_update($id, $cat_name)
    {

        $data = array(
            'name' => $cat_name

        );


        $this->db->set($data);
        $this->db->where('id', $id);

        if ($this->db->update('cberp_trans_cat')) {
            return true;
        } else {
            return false;
        }
    }

    public function check_balance($id)
    {
        $this->db->select('balance');
        $this->db->from('cberp_customers');
        $this->db->where('id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_dynamic_count(){
        $datefield = 'date';
        $query = $this->db->query("SELECT 
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN debit ELSE 0 END) AS yearly_debit,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 3 MONTH AND CURDATE() THEN debit ELSE 0 END) AS quarterly_debit,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN debit ELSE 0 END) AS monthly_debit,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN debit ELSE 0 END) AS weekly_debit,
            SUM(CASE WHEN DATE($datefield) = CURDATE() THEN debit ELSE 0 END) AS daily_debit,
            
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN credit ELSE 0 END) AS yearly_credit,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 3 MONTH AND CURDATE() THEN credit ELSE 0 END) AS quarterly_credit,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN credit ELSE 0 END) AS monthly_credit,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN credit ELSE 0 END) AS weekly_credit,
            SUM(CASE WHEN DATE($datefield) = CURDATE() THEN credit ELSE 0 END) AS daily_credit,

            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE NULL END) AS yearly_count,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 3 MONTH AND CURDATE() THEN 1 ELSE NULL END) AS quarterly_count,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE NULL END) AS monthly_count,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE NULL END) AS weekly_count,
            COUNT(CASE WHEN DATE($datefield) = CURDATE() THEN 1 ELSE NULL END) AS daily_count
        FROM cberp_transactions");
        return $query->row();
    }

    public function check_customer_account_details($id)
    {
        $this->db->select('avalable_credit_limit,credit_limit');
        $this->db->from('cberp_customers');
        $this->db->where('customer_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function transactions_ai_details($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_payments');
        $this->db->where('cberp_payments.id', $id); 
        $query = $this->db->get();         
        return  $query->row_array();
    }

    public function load_account_details_by_code($acn)
    {
        $this->db->select('cberp_accounts.holder, cberp_accounts.acn, cberp_coa_types.typename');
        $this->db->from('cberp_accounts');
        $this->db->join('cberp_coa_types', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id', 'inner');
        $this->db->where('cberp_accounts.acn', $acn);
        $query = $this->db->get();         
        return  $query->row_array();
    }
    public function load_account_transactions_by_code($acid)
    { 
        //refere
        $this->db->select('cberp_transactions.date,cberp_transactions.debit AS debitamount,cberp_transactions.credit AS creditamount,cberp_transactions.invoice_number AS typenumber,cberp_transactions.transaction_number,cberp_transactions.cat as transcategory,cberp_payment_transaction_link.bank_transaction_number,cberp_payment_transaction_link.trans_type as transationtype,cberp_bank_transactions.trans_ref_number AS bank_transaction_refernce,cberp_invoices.id AS invoiceid,cberp_invoices.invoice_type,cberp_purchase_receipts.id as receipt_number,cberp_purchase_receipts.purchase_reciept_number,cberp_expense_claims.claim_number,cberp_delivery_notes.delevery_note_id,cberp_delivery_notes.invoice_number as deliverynote_invoice_number,cberp_delivery_returns.delivery_return_number,deliveryinvoice.id as deliverynote_invoiceid, cberp_stock_returns.id as invoice_returnid,cberp_purchase_reciept_returns.receipt_return_number');
        $this->db->from('cberp_transactions');

        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.transaction_number = cberp_transactions.transaction_number', 'left');

        $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number', 'left');

        $this->db->join('cberp_invoices', 'cberp_invoices.transaction_number = cberp_transactions.transaction_number','left');

        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.transaction_number = cberp_transactions.transaction_number','left');
        $this->db->join('cberp_invoices as deliveryinvoice', 'deliveryinvoice.invoice_number = cberp_delivery_notes.invoice_number','left');

        $this->db->join('cberp_delivery_returns', 'cberp_delivery_returns.transaction_number = cberp_transactions.transaction_number','left');

        $this->db->join('cberp_purchase_reciept_returns', 'cberp_purchase_reciept_returns.transaction_number = cberp_transactions.transaction_number','left');

        $this->db->join('cberp_stock_returns', 'cberp_stock_returns.transaction_number = cberp_transactions.transaction_number','left');

        $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.transaction_number = cberp_transactions.transaction_number','left');
        $this->db->join('cberp_expense_claims', 'cberp_expense_claims.transaction_number = cberp_transactions.transaction_number','left');
        $this->db->where('cberp_transactions.acid', $acid);
        $this->db->order_by('cberp_transactions.id','ASC');
        $query = $this->db->get();   
        // die($this->db->last_query());
        return  $query->result_array();
    }

    public function get_bank_transaction_details($trans_ref_number)
    {
        $this->db->select('
            cberp_bank_transactions.trans_ref_number,
            cberp_bank_transactions.trans_customer_id,
            cberp_bank_transactions.trans_date,
            cberp_bank_transactions.trans_type,
            cberp_bank_transactions.trans_amount,
            cberp_bank_transactions.trans_account_id,
            cberp_bank_transactions.trans_chart_of_account_id,
            cberp_payment_transaction_link.transaction_number,
            cberp_payment_transaction_link.bank_transaction_number,
            cberp_payment_transaction_link.status,
            cberp_payment_transaction_link.trans_type_number,
            cberp_customers.name AS customer,
            cberp_customers.phone,
            cberp_customers.city,
            cberp_customers.address,
            cberp_customers.region,
            cberp_customers.postbox,
            cberp_customers.email,
            account_chart.holder AS chart_holder,
            account_trans.holder AS bank_holder
        ');

        $this->db->from('cberp_bank_transactions');
        $this->db->join(
            'cberp_payment_transaction_link',
            'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number'
        );
        $this->db->join(
            'cberp_customers',
            'cberp_customers.customer_id = cberp_bank_transactions.trans_customer_id',
            'left'
        );
        $this->db->join('cberp_accounts AS account_chart', 'account_chart.acn = cberp_bank_transactions.trans_chart_of_account_id');
        $this->db->join('cberp_accounts AS account_trans', 'account_trans.acn = cberp_bank_transactions.trans_account_id');
        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);
        // $this->db->where('cberp_payment_transaction_link.trans_type', 'Invoice');

        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    public function get_invoice_details($invoice_number)
    {
        $this->db->select('
            cberp_invoices.id,
            cberp_invoices.invoice_number,
            cberp_invoices.invoicedate,
            cberp_invoices.invoiceduedate,
            cberp_invoices.total,
            cberp_invoices.payment_recieved_amount
        ');
        $this->db->from('cberp_invoices');
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_invoice_return_details($invoice_number)
    {
        $this->db->select('
            cberp_stock_returns.id,
            cberp_stock_returns.tid,
            cberp_stock_returns.invoicedate,
            cberp_stock_returns.invoiceduedate,
            cberp_stock_returns.total
        ');
        $this->db->from('cberp_stock_returns');
        $this->db->where('cberp_stock_returns.tid', $invoice_number);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_invoice_details_bank_trans_number($trans_ref_number)
    {
        $this->db->select('
            cberp_invoices.id AS invoiceid,cberp_invoices.invoice_number,cberp_invoices.csd,cberp_invoices.invoicedate,cberp_invoices.invoiceduedate,cberp_invoices.total,cberp_invoices.payment_recieved_amount, cberp_invoices.status as invoicestatus,cberp_payment_transaction_link.transaction_number,cberp_payment_transaction_link.bank_transaction_number,cberp_bank_transactions.trans_account_id,cberp_bank_transactions.trans_chart_of_account_id,cberp_bank_transactions.trans_date,cberp_bank_transactions.trans_amount,cberp_bank_transactions.trans_ref_number
        ');
        $this->db->from('cberp_bank_transactions');
        $this->db->join(
            'cberp_payment_transaction_link',
            'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number'
        );
        $this->db->join(
            'cberp_invoices',
            'cberp_invoices.invoice_number = cberp_payment_transaction_link.trans_type_number'
        );
        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_transaction_ai_details($trans_num)
    // public function get_transaction_ai_details($invoice_id, $trans_num)
    {
        $this->db->select('*');
        $this->db->from('cberp_payments');
        // $this->db->where('cberp_payments.invoice_id', $invoice_id);
        $this->db->where('cberp_payments.trans_num', $trans_num);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_bank_transaction_link_details($trans_ref_number)
    {
        $this->db->select('
            cberp_payment_transaction_link.trans_type,
            cberp_payment_transaction_link.trans_type_number,
            cberp_payment_transaction_link.transaction_number,
            cberp_payment_transaction_link.bank_transaction_number,
            cberp_bank_ac.id as accountid,
            bankaccount.acn AS bankcode,
            bankaccount.holder AS bankname,
            coa_account.holder AS coaname,
            coa_account.acn AS coacode,
            cberp_employees.name as employee,
            cberp_bank_transactions.trans_ref_number,
            cberp_bank_transactions.trans_date,
            cberp_bank_transactions.trans_amount,
            cberp_bank_transactions.trans_payment_method
            
        ');
        $this->db->from('cberp_bank_transactions');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_payment_transaction_link.created_by');
        $this->db->join('cberp_accounts AS bankaccount', 'bankaccount.acn = cberp_bank_transactions.trans_account_id');
        $this->db->join('cberp_accounts AS coa_account', 'coa_account.acn = cberp_bank_transactions.trans_chart_of_account_id');
        $this->db->join('cberp_bank_ac', 'cberp_bank_ac.code = cberp_bank_transactions.trans_account_id');
        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);
        
        $query = $this->db->get();
        return $query->row_array();

    }

    public function get_opening_balance_details($trans_ref_number)
    {
        $this->db->select('cberp_payment_transaction_link.transaction_number,cberp_payment_transaction_link.bank_transaction_number,cberp_bank_transactions.trans_account_id,cberp_bank_transactions.trans_chart_of_account_id,cberp_bank_transactions.trans_date,cberp_bank_transactions.trans_amount,cberp_bank_transactions.trans_ref_number,cberp_bank_transactions.trans_reference
        ');
        $this->db->from('cberp_bank_transactions');
        $this->db->join(
            'cberp_payment_transaction_link',
            'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number'
        );
        // $this->db->join(
        //     'cberp_invoices',
        //     'cberp_invoices.invoice_number = cberp_payment_transaction_link.trans_type_number'
        // );
        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_deposit_details($trans_ref_number)
    {
        $this->db->select('cberp_payment_transaction_link.transaction_number,cberp_payment_transaction_link.bank_transaction_number,cberp_bank_transactions.trans_account_id,cberp_bank_transactions.trans_chart_of_account_id,cberp_bank_transactions.trans_date,cberp_bank_transactions.trans_amount,cberp_bank_transactions.trans_ref_number,cberp_bank_transactions.trans_reference,cberp_bank_transactions.trans_type,cberp_customers.name as customer,cberp_customers.phone,cberp_customers.email,cberp_customers.city,cberp_customers.region,cberp_customers.address,cberp_suppliers.name AS supplier,cberp_suppliers.phone AS supplierphone,cberp_suppliers.email AS supplieremail,cberp_suppliers.city AS suppliercity,cberp_suppliers.region AS supplierregion,cberp_suppliers.address AS supplieraddress');
        $this->db->from('cberp_bank_transactions');
        $this->db->join(
            'cberp_payment_transaction_link',
            'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number'
        );
        $this->db->join(
            'cberp_suppliers',
            'cberp_suppliers.supplier_id = cberp_bank_transactions.trans_supplier_id','left'
        );
        $this->db->join(
            'cberp_customers',
            'cberp_customers.customer_id = cberp_bank_transactions.trans_customer_id','left'
        );
        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_purchase_receipt_details_by_refernce_number($trans_ref_number)
    {
        

        $this->db->select(
            'cberp_payment_transaction_link.trans_type_number,
            cberp_payment_transaction_link.transaction_number,
            cberp_payment_transaction_link.bank_transaction_number,
            cberp_suppliers.supplier_id AS supplierid,
            cberp_suppliers.name AS suppliername,
            cberp_suppliers.phone AS supplierphone,
            cberp_suppliers.email AS supplieremail,
            cberp_suppliers.city AS suppliercity,
            cberp_suppliers.region AS supplierregion,
            cberp_suppliers.address AS supplieraddress,
            cberp_purchase_receipts.bill_amount,
            cberp_purchase_receipts.purchase_paid_amount,
            cberp_purchase_receipts.payment_status,
            cberp_purchase_receipts.id as receiptid,
            cberp_purchase_receipts.purchase_reciept_number,
            cberp_purchase_receipts.purchase_receipt_date'
        );
        $this->db->from('cberp_bank_transactions');

        $this->db->join('cberp_payment_transaction_link','cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number','inner');

        $this->db->join('cberp_purchase_receipts','cberp_purchase_receipts.purchase_reciept_number = cberp_payment_transaction_link.trans_type_number','inner');

        $this->db->join('cberp_employees','cberp_employees.id = cberp_purchase_receipts.received_by','inner');

        $this->db->join('cberp_purchase_orders','cberp_purchase_orders.purchase_number   = cberp_purchase_receipts.purchase_number','inner');

        $this->db->join('cberp_suppliers','cberp_suppliers.supplier_id = cberp_purchase_orders.customer_id','inner');

        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);

        $query = $this->db->get();
        // die($this->db->last_query());
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


                    $this->db->update('cberp_transactions',['credit'=>$debitamount,'debit'=>$creditamount],['transaction_number'=>$transaction_number,'acid'=>$acn]);
                }
                else
                {
                    $this->db->set('lastbal', "lastbal + $creditamount", FALSE);
                    $this->db->where('acn', $acn);
                    $this->db->update('cberp_accounts');
                    $this->db->update('cberp_transactions',['credit'=>$debitamount,'debit'=>$creditamount],['transaction_number'=>$transaction_number,'acid'=>$acn]);
                }
            }
        }
    }

    
    public function get_expense_claim_details_by_refernce_number($trans_ref_number)
    {
        

        $this->db->select(
            'cberp_payment_transaction_link.trans_type_number,
            cberp_payment_transaction_link.transaction_number,
            cberp_payment_transaction_link.bank_transaction_number,
            cberp_suppliers.supplier_id AS supplierid,
            cberp_suppliers.name AS suppliername,
            cberp_suppliers.phone AS supplierphone,
            cberp_suppliers.email AS supplieremail,
            cberp_suppliers.city AS suppliercity,
            cberp_suppliers.region AS supplierregion,
            cberp_suppliers.address AS supplieraddress,
            cberp_expense_claims.claim_number,
            cberp_expense_claims.claim_date,
            cberp_expense_claims.claim_due_date,
            cberp_expense_claims.claim_subtotal,
            cberp_expense_claims.claim_discount,
            cberp_expense_claims.claim_discount_amount,
            cberp_expense_claims.claim_total,
            cberp_expense_claims.payment_recieved_amount,
            cberp_expense_claims.payment_status,
            cberp_expense_claims.discount_type'
        );
        $this->db->from('cberp_bank_transactions');

        $this->db->join('cberp_payment_transaction_link','cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number','inner');

        $this->db->join('cberp_expense_claims','cberp_expense_claims.claim_number = cberp_payment_transaction_link.trans_type_number','inner');

        $this->db->join('cberp_employees','cberp_employees.id = cberp_expense_claims.employee_id','inner');

        $this->db->join('cberp_suppliers','cberp_suppliers.supplier_id = cberp_expense_claims.supplier_id','inner');

        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_purchase_return_details_by_refernce_number($trans_ref_number)
    {
        

        $this->db->select(
           'cberp_payment_transaction_link.trans_type_number,
            cberp_payment_transaction_link.transaction_number,
            cberp_payment_transaction_link.bank_transaction_number,
            cberp_suppliers.supplier_id AS supplierid,
            cberp_suppliers.name AS suppliername,
            cberp_suppliers.phone AS supplierphone,
            cberp_suppliers.email AS supplieremail,
            cberp_suppliers.city AS suppliercity,
            cberp_suppliers.region AS supplierregion,
            cberp_suppliers.address AS supplieraddress,
            cberp_stock_returns.total,
            cberp_stock_returns.payment_recieved_amount,
            cberp_stock_returns.payment_status,
            cberp_stock_returns.id as receiptid,
            cberp_stock_returns.invoicedate'
        );
        $this->db->from('cberp_bank_transactions');

        $this->db->join('cberp_payment_transaction_link','cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number','inner');

        $this->db->join('cberp_stock_returns','cberp_stock_returns.tid = cberp_payment_transaction_link.trans_type_number','inner');

        $this->db->join('cberp_employees','cberp_employees.id = cberp_stock_returns.eid','inner');

        $this->db->join('cberp_suppliers','cberp_suppliers.supplier_id = cberp_stock_returns.csd','inner');

        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);

        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    public function get_purchase_return_details_by_refernce_number_new($trans_ref_number)
    {
        

        $this->db->select(
           'cberp_payment_transaction_link.trans_type_number,
            cberp_payment_transaction_link.transaction_number,
            cberp_payment_transaction_link.bank_transaction_number,
            cberp_suppliers.supplier_id AS supplierid,
            cberp_suppliers.name AS suppliername,
            cberp_suppliers.phone AS supplierphone,
            cberp_suppliers.email AS supplieremail,
            cberp_suppliers.city AS suppliercity,
            cberp_suppliers.region AS supplierregion,
            cberp_suppliers.address AS supplieraddress,
            cberp_purchase_reciept_returns.total,
            cberp_purchase_reciept_returns.payment_recieved_amount,
            cberp_purchase_reciept_returns.payment_status,
            cberp_purchase_reciept_returns.id as receiptid,
            cberp_purchase_reciept_returns.receipt_return_number,
            cberp_purchase_reciept_returns.return_date'
        );
        $this->db->from('cberp_bank_transactions');

        $this->db->join('cberp_payment_transaction_link','cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number','inner');

        $this->db->join('cberp_purchase_reciept_returns','cberp_purchase_reciept_returns.receipt_return_number = cberp_payment_transaction_link.trans_type_number','inner');

        $this->db->join('cberp_employees','cberp_employees.id = cberp_purchase_reciept_returns.created_by','inner');

        $this->db->join('cberp_suppliers','cberp_suppliers.supplier_id = cberp_purchase_reciept_returns.supplier_id','inner');

        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);

        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    
    public function last_invoice_payment_receipt_number()
    {
        // $this->configurations = $this->session->userdata('configurations');
        // $prefix = $this->configurations['invoiceprefix']; 
        $prefix =  get_prefix_73();
        $prefix = $prefix['invoicereceipt_prefix'];
        $this->db->select('receipt_number');
        $this->db->from('cberp_invoice_payments');
        $this->db->order_by('created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $last_invoice_number = $query->row()->receipt_number;
            $parts = explode('/', $last_invoice_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $prefix.$next_number;
        } else {
            return $prefix.'1001';
        }
    }
    public function last_invoice_return_receipt_number()
    {
        // $this->configurations = $this->session->userdata('configurations');
        // $prefix = $this->configurations['invoiceprefix']; 
        $prefix =  get_prefix_73();
        $prefix = $prefix['invoicereturnreceipt_prefix'];
        $this->db->select('receipt_number');
        $this->db->from('cberp_invoice_return_payments');
        $this->db->order_by('created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $last_invoice_number = $query->row()->receipt_number;
            $parts = explode('/', $last_invoice_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $prefix.$next_number;
        } else {
            return $prefix.'1001';
        }
    }

     public function last_purchase_payment_receipt_number()
    {
        // $this->configurations = $this->session->userdata('configurations');
        // $prefix = $this->configurations['invoiceprefix']; 
        $prefix =  get_prefix_73();
        $prefix = $prefix['purcahepayment_prefix'];
        $this->db->select('receipt_number');
        $this->db->from('cberp_purchase_receipt_payments');
        $this->db->order_by('created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $last_invoice_number = $query->row()->receipt_number;
            $parts = explode('/', $last_invoice_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $prefix.$next_number;
        } else {
            return $prefix.'1001';
        }
    }
}
