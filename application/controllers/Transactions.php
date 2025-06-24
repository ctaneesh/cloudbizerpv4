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

defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
        $this->load->model('invoices_model');
        $this->load->model('transactions_model', 'transactions');
        $this->load->model('purchase_model', 'purchase');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        $this->load->library("Custom");
        $this->li_a = 'accounts';
    }

    public function index()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $data['permissions'] = load_permissions('Accounts','Transactions','View Transactions');
        $head['title'] = "Transaction";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
          
        $condition = "";
        $data['enquirycounts'] = $this->transactions->get_dynamic_count();
        $this->load->view('transactions/index',$data);
        $this->load->view('fixed/footer');

    }

    public function add()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $data['dual'] = $this->custom->api_config(65);

        $data['cat'] = $this->transactions->categories();
        $data['accounts'] = $this->transactions->acc_list();
        $head['title'] = "Add Transaction";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('transactions/create', $data);
        $this->load->view('fixed/footer');

    }

    public function transfer()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }

        $data['cat'] = $this->transactions->categories();
        $data['accounts'] = $this->transactions->acc_list();
        $head['title'] = "New Transfer";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('transactions/transfer', $data);
        $this->load->view('fixed/footer');

    }

    public function payinvoice()
    {

        // if (!$this->aauth->premission(1)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $amount2 = 0;
        $tid = $this->input->post('tid');
        $amount = rev_amountExchange_s($this->input->post('amount', true), 0, $this->aauth->get_user()->loc);
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $acid = $this->input->post('account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = datefordatabase($paydate);

        $this->db->select('holder');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $acid);
        $query = $this->db->get();
        $account = $query->row_array();

        if ($pmethod == 'Balance') {

            $customer = $this->transactions->check_balance($cid);
            if (rev_amountExchange_s($customer['balance'], 0, $this->aauth->get_user()->loc) >= $amount) {

                $this->db->set('balance', "balance-$amount", FALSE);
                $this->db->where('id', $cid);
                $this->db->update('cberp_customers');
            } else {

                $amount = rev_amountExchange_s($customer['balance'], 0, $this->aauth->get_user()->loc);
                $this->db->set('balance', 0, FALSE);
                $this->db->where('id', $cid);
                $this->db->update('cberp_customers');
            }
        }

        $data = array(
            'acid' => $acid,
            'account' => $account['holder'],
            'type' => 'Income',
            'cat' => 'Sales',
            'credit' => $amount,
            'payer' => $cname,
            'payerid' => $cid,
            'method' => $pmethod,
            'date' => $paydate,
            'eid' => $this->aauth->get_user()->id,
            'tid' => $tid,
            'note' => $note,
            'loc' => $this->aauth->get_user()->loc
        );

        $this->db->insert('cberp_transactions', $data);
        $tttid = $this->db->insert_id();

        $this->db->select('total,csd,pamnt');
        $this->db->from('cberp_invoices');
        $this->db->where('id', $tid);
        $query = $this->db->get();
        $invresult = $query->row();

        $totalrm = $invresult->total - $invresult->pamnt;

        if ($totalrm > $amount) {
            $this->db->set('pmethod', $pmethod);
            $this->db->set('pamnt', "pamnt+$amount", FALSE);

            $this->db->set('status', 'partial');
            $this->db->where('id', $tid);
            $this->db->update('cberp_invoices');


            //account update
            $this->db->set('lastbal', "lastbal+$amount", FALSE);
            $this->db->where('id', $acid);
            $this->db->update('cberp_accounts');
            $paid_amount = $invresult->pamnt + $amount;
            $status = 'Partial';
            $totalrm = $totalrm - $amount;
        } else {
            if ($totalrm < $amount) {
                $diff = $totalrm - $amount;
                $diff = abs($diff);
                $amount2 = $amount;
                $amount = $totalrm;
                $this->db->set('balance', "balance+$diff", FALSE);
                $this->db->where('id', $cid);
                $this->db->update('cberp_customers');
                $this->db->set('credit', "credit-$diff", FALSE);
                $this->db->where('id', $tttid);
                $this->db->update('cberp_transactions');

            }
            $this->db->set('pmethod', $pmethod);
            $this->db->set('pamnt', "pamnt+$totalrm", FALSE);
            $this->db->set('status', 'paid');
            $this->db->where('id', $tid);
            $this->db->update('cberp_invoices');
            //account update
            $this->db->set('lastbal', "lastbal+$totalrm", FALSE);
            $this->db->where('id', $acid);
            $this->db->update('cberp_accounts');
            $totalrm = 0;
            $status = 'Paid';
        }
        $amount += $amount2;

        $activitym = "<tr><td>" . '<a href="' . base_url('invoices') . '/view_payslip?id=' . $tttid . '&inv=' . $tid . '" class="btn btn-crud btn-blue btn-sm"><span class="fa fa-print" aria-hidden="true"></span></a> ' . substr($paydate, 0, 10) . "</td><td>$pmethod</td><td>" . amountExchange_s($amount, 0, $this->aauth->get_user()->loc) . "</td><td>$note</td></tr>";
        $dual = $this->custom->api_config(65);
        if ($dual['key1']) {

            $this->db->select('holder');
            $this->db->from('cberp_accounts');
            $this->db->where('id', $dual['key2']);
            $query = $this->db->get();
            $account = $query->row_array();

            $data['credit'] = 0;
            $data['debit'] = $amount;
            $data['type'] = 'Expense';
            $data['acid'] = $dual['key2'];
            $data['account'] = $account['holder'];
            $data['note'] = 'Debit ' . $data['note'];

            $this->db->insert('cberp_transactions', $data);

            //account update
            $this->db->set('lastbal', "lastbal-$amount", FALSE);
            $this->db->where('id', $dual['key2']);
            $this->db->update('cberp_accounts');
        }
        echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('Transaction has been added'), 'pstatus' => $this->lang->line($status), 'activity' => $activitym, 'amt' => $totalrm, 'ttlpaid' => amountExchange_s($amount, 0, $this->aauth->get_user()->loc)));

                $alert = $this->custom->api_config(66);
        if ($alert['key1'] == 1) {
            $this->load->model('communication_model');
            $subject = $cname . ' ' . $this->lang->line('Transaction has been');
            $body = $subject . '<br> ' . $this->lang->line('Credit') . ' ' . $this->lang->line('Amount') . ' ' . $amount . '<br> ' . $this->lang->line('Debit') . ' ' . $this->lang->line('Amount') . ' 0  <br> ID# ' . $tttid;
            $out = $this->communication_model->send_corn_email($alert['url'], $alert['url'], $subject, $body, false, '');
        }
    }

    public function paypurchase_old()
    {

        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }

        $tid = $this->input->post('tid', true);
        $amount = $this->input->post('amount', true);
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $acid = $this->input->post('account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = datefordatabase($paydate);
        $this->db->select('holder');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $acid);
        $query = $this->db->get();
        $account = $query->row_array();
        $data = array(
            'acid' => $acid,
            'account' => $account['holder'],
            'type' => 'Expense',
            'cat' => 'Purchase',
            'debit' => $amount,
            'payer' => $cname,
            'payerid' => $cid,
            'method' => $pmethod,
            'date' => $paydate,
            'eid' => $this->aauth->get_user()->id,
            'tid' => $tid,
            'note' => $note,
            'ext' => 1,
            'loc' => $this->aauth->get_user()->loc
        );
        $this->db->insert('cberp_transactions', $data);

        
        $this->db->insert_id();
        $this->db->select('total,csd,pamnt');
        $this->db->from('cberp_purchase_orders');
        $this->db->where('id', $tid);
        $query = $this->db->get();
        $invresult = $query->row();
        $totalrm = $invresult->total - $invresult->pamnt;
        if ($totalrm > $amount) {
            $this->db->set('pmethod', $pmethod);
            $this->db->set('pamnt', "pamnt+$amount", FALSE);
            $this->db->set('status', 'partial');
            $this->db->where('id', $tid);
            $this->db->update('cberp_purchase_orders');
            //account update
            $this->db->set('lastbal', "lastbal-$amount", FALSE);
            $this->db->where('id', $acid);
            $this->db->update('cberp_accounts');
            $paid_amount = $invresult->pamnt + $amount;
            $status = 'Partial';
            $totalrm = $totalrm - $amount;
        } else {
            $this->db->set('pmethod', $pmethod);
            $this->db->set('pamnt', "pamnt+$amount", FALSE);
            $this->db->set('status', 'paid');
            $this->db->where('id', $tid);
            $this->db->update('cberp_purchase_orders');
            //acount update
            $this->db->set('lastbal', "lastbal-$amount", FALSE);
            $this->db->where('id', $acid);
            $this->db->update('cberp_accounts');
            $totalrm = 0;
            $status = 'Paid';
            $paid_amount = $amount;
        }

        $dual = $this->custom->api_config(65);
        if ($dual['key1']) {

            $this->db->select('holder');
            $this->db->from('cberp_accounts');
            $this->db->where('id', $dual['url']);
            $query = $this->db->get();
            $account = $query->row_array();

            $data['debit'] = 0;
            $data['credit'] = $amount;
            $data['type'] = 'Income';
            $data['acid'] = $dual['url'];
            $data['account'] = $account['holder'];
            $data['note'] = 'Credit ' . $data['note'];

            $this->db->insert('cberp_transactions', $data);

            //account update
            $this->db->set('lastbal', "lastbal+$amount", FALSE);
            $this->db->where('id', $dual['url']);
            $this->db->update('cberp_accounts');
        }
        $activitym = "<tr><td>" . substr($paydate, 0, 10) . "</td><td>$pmethod</td><td>$amount</td><td>$note</td></tr>";


        echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('Transaction has been added'), 'pstatus' => $this->lang->line($status), 'activity' => $activitym, 'amt' => $totalrm, 'ttlpaid' => $paid_amount));
    }

    //erp2024 add newfunction for paypurchase 01-10-2024 starts
    public function paypurchase()
    {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        // $existing_transaction_number = get_payment_trans_number($tid);
        // $transaction_number = ($existing_transaction_number) ? $existing_transaction_number :get_latest_trans_number();
        $received_amount = 0;
        $transaction_number = get_latest_trans_number();
        $tid = $this->input->post('tid', true);
        $receipt_number = $this->input->post('receipt_number', true);        
        $last_receipt_number = $this->transactions->last_purchase_payment_receipt_number(); 
        $receipt_id = $this->input->post('receipt_id', true);
        $purchase_id = $this->input->post('purchase_id', true);    
        $purchase_number = $this->input->post('purchase_number', true);    
        $amount = $this->input->post('amount', true);
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $acid = $this->input->post('account_type_id', true);        
        $coa_account_id = $acid;
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = datefordatabase($paydate);

        $this->db->select('holder');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $acid);
        $query = $this->db->get();
        $account = $query->row_array();

        // #erp2024 01-10-2024 new insertion for cberp_payments table
        $dataai = [];
                    
        // $dataai['payer'] = $cname;
        $dataai['payerid'] = $cid;
        $dataai['note'] = $note;
        $dataai['payment_method'] = $pmethod;
        $dataai['record_from']     = 'Purchase';
        $dataai['amount']         = $amount;
        $dataai['transfered_account_id'] = $acid;
        // $dataai['transfered_account_name'] = $account['holder'];
        $dataai['trans_type'] = 'Purchase';
        $postedchequeflg = 0;
        $status = 'post dated cheque';
        $data_payments = [
            'receipt_number' => $last_receipt_number,
            'transaction_number' => $transaction_number,
            'payment_amount' => $amount,
            'payment_method' => $pmethod,
            'chart_of_account_1' => $bank_account_id,                        
            'chart_of_account_2' => $coa_account_id,                        
            'note' => $note,                        
            'created_by' => $this->session->userdata('id'),
            'created_date' => date('Y-m-d H:i:s')
        ];
        if($pmethod=="Cheque")
        {
            $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            $dataai['cheque_number'] = $this->input->post('cheque_number', true);
            $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
            $data_payments['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            $data_payments['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            $data_payments['cheque_number'] = $this->input->post('cheque_number', true);
            $data_payments['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
            $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;     
                   
        }
        else if($pmethod=="Card")
        {
            $dataai['card_number'] = $this->input->post('card_number', true);
            $dataai['cvc'] = $this->input->post('cvc', true);
            $dataai['card_holder'] = $this->input->post('card_holder', true);
            $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
        }
        else if($pmethod=="Bank")
        {
            $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
            $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
            $dataai['account_number'] = $this->input->post('account_number', true);
            $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
            $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
            $data_payments['account_bank_name'] = $this->input->post('account_bank_name', true);
            $data_payments['account_bank_address'] = $this->input->post('account_bank_address', true);
            $data_payments['account_number'] = $this->input->post('account_number', true);
            $data_payments['account_holder_name'] = $this->input->post('account_holder_name', true);
            $data_payments['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
        }
        else{}

         $this->db->insert('cberp_purchase_receipt_payments', $data_payments); 
         $this->db->insert('cberp_purchase_receipt_payments_details', ['receipt_number'=>$last_receipt_number,'purchase_reciept_number'=>$receipt_number,'paid_amount'=>$amount]); 
     
        // #erp2024 01-10-2024 new insertion for cberp_payments table
        $this->db->select('order_total,customer_id,paid_amount');
        $this->db->from('cberp_purchase_orders');
        $this->db->where('purchase_number', $purchase_number);
        $query = $this->db->get();
        $purchaseresult = $query->row();
        $purchase_totalrm = $purchaseresult->order_total - $purchaseresult->paid_amount; 

        $this->db->select('bill_amount,purchase_paid_amount');
        $this->db->from('cberp_purchase_receipts');
        $this->db->where('purchase_reciept_number', $receipt_number);
        $query = $this->db->get();
        $invresult = $query->row();
       
        $totalrm = $invresult->bill_amount - $invresult->purchase_paid_amount; 
        $received_amount = abs($amount - $totalrm);

        if($totalrm)
        {

            $bank_tansaction_number = get_transnumber();
            $banktranslink_data = [                            
                'trans_type' => 'Purchase',
                'trans_type_number' => $receipt_number,
                'transaction_number'=>$transaction_number,
                'bank_transaction_number'=>$bank_tansaction_number,
                'created_dt' => date('Y-m-d H:i:s'),
                'created_by'=> $this->session->userdata('id')
            ];
            $this->db->insert('cberp_payment_transaction_link', $banktranslink_data);

            $prev_payable_data = [
                'acid' => $coa_account_id,
                'type' => 'Liability',
                'cat' => 'Purchase',
                'debit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->insert('cberp_transactions',$prev_payable_data);
            $this->db->set('lastbal', 'lastbal + ' .$amount, FALSE);
            $this->db->where('acn', $coa_account_id);
            $this->db->update('cberp_accounts'); 


            $bank_data = [
                'acid' => $bank_account_id,
                'type' => 'Asset',
                'cat' => 'Purchase',
                'credit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
                'invoice_number'=>$invoice_number
            ];
            $this->db->insert('cberp_transactions',$bank_data);
            $this->db->set('lastbal', 'lastbal - ' .$amount, FALSE);
            $this->db->where('acn', $bank_account_id);
            $this->db->update('cberp_accounts');


            $banktrans_data = [
                'trans_type' => 'Expense',
                'trans_amount' => $amount,
               'trans_date' => date('Y-m-d H:i:s'),
                'trans_number'=>$bank_tansaction_number,
                'trans_supplier_id'=> $cid,
                'trans_payment_method'=> $pmethod,
                'trans_account_id'=>$bank_account_id,
                'trans_chart_of_account_id'=>$coa_account_id,
                'from_trans_number'=>$transaction_number,            
                'trans_ref_number'=>get_banktrans_reference_number(),
                'transfered_by' => $this->session->userdata('id')
            ];
            $this->db->insert('cberp_bank_transactions',$banktrans_data);


            $dataai['transaction_number'] = $transaction_number;
            $this->db->insert('cberp_payments', $dataai);
            
            if ($totalrm > $amount) {            
                $this->db->set('payment_transaction_number', $transaction_number);
                $this->db->set('payment_status', 'Partial');
                $this->db->set('purchase_paid_date', date('Y-m-d'));
                $this->db->set('purchase_paid_amount', "purchase_paid_amount+$amount", FALSE);
                $this->db->where('purchase_reciept_number', $receipt_number);
                $this->db->update('cberp_purchase_receipts');
                // history_table_log('purchase_receipt_log','reciept_id',$receipt_id,'Payment Update');
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history('Purchasereceipt',$receipt_number,'Payment Update', $_POST['changedFields']);
                //erp2024 06-01-2025 detailed history log ends 
            } 
            else {            
                $this->db->set('payment_transaction_number', $transaction_number);
                $this->db->set('payment_status', 'Paid');
                $this->db->set('purchase_paid_date', date('Y-m-d'));
                $this->db->set('purchase_paid_amount', "purchase_paid_amount+$amount", FALSE);
                $this->db->where('purchase_reciept_number', $receipt_number);
                $this->db->update('cberp_purchase_receipts');
                // history_table_log('purchase_receipt_log','reciept_id',$receipt_id,'Payment Update');
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history('Purchasereceipt',$receipt_number,'Payment Update', $_POST['changedFields']);
                //erp2024 06-01-2025 detailed history log ends 
            }

            if ($purchase_totalrm > $amount) {            
                $status = 'partial';
                $this->db->set('payment_method', $pmethod);
                $this->db->set('paid_amount', "paid_amount+$amount", FALSE);
                $this->db->set('payment_status', 'partial');
                $this->db->where('purchase_number', $purchase_number);
                $this->db->update('cberp_purchase_orders');
            } 
            else {            
                $status = 'paid';
                $this->db->set('payment_method', $pmethod);
                $this->db->set('paid_amount', "paid_amount+$amount", FALSE);
                $this->db->set('payment_status', 'paid');
                $this->db->where('purchase_number', $purchase_number);
                $this->db->update('cberp_purchase_orders');
            }
            
            $activitym = "<tr><td>" . substr($paydate, 0, 10) . "</td><td>$pmethod</td><td>$amount</td><td>$note</td></tr>";
        }
        
        echo json_encode(array('status' => 'Success'));
        // echo json_encode(array('status' => 'Success', 'message' =>
        //     $this->lang->line('Transaction has been added'), 'pstatus' => $this->lang->line($status), 'activity' => $activitym, 'amt' => $totalrm, 'ttlpaid' => $paid_amount));
    }

    //erp2024 add newfunction for paypurchase 01-10-2024 ends
    public function cancelinvoice()
    {
        // if (!$this->aauth->premission(1)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }


        $tid = intval($this->input->post('tid'));
        $this->db->set('pamnt', "0.00", FALSE);
        $this->db->set('total', "0.00", FALSE);
        $this->db->set('items', 0);
        $this->db->set('status', 'canceled');
        $this->db->where('id', $tid);
        $this->db->update('cberp_invoices');
        //reverse
        $this->db->select('credit,debit,acid');
        $this->db->from('cberp_transactions');
        $this->db->where('tid', $tid);
        $query = $this->db->get();
        $revresult = $query->result_array();
        foreach ($revresult as $trans) {
            $amt = $trans['credit'] - $trans['debit'];
            $this->db->set('lastbal', "lastbal-$amt", FALSE);
            $this->db->where('id', $trans['acid']);
            $this->db->update('cberp_accounts');
        }
        $this->db->select('pid,qty');
        $this->db->from('cberp_invoice_items');
        $this->db->where('tid', $tid);
        $query = $this->db->get();
        $prevresult = $query->result_array();
        foreach ($prevresult as $prd) {
            $amt = $prd['qty'];
            $this->db->set('qty', "qty+$amt", FALSE);
            $this->db->where('pid', $prd['pid']);
            $this->db->update('cberp_products');
        }
        $this->db->delete('cberp_transactions', array('tid' => $tid));
        $data = array('type' => 9, 'rid' => $tid);
        $this->db->delete('cberp_metadata', $data);
        echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('Invoice canceled')));
    }


    public function cancelpurchase()
    {
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $tid = intval($this->input->post('tid'));
        $this->db->set('pamnt', "0.00", FALSE);
        $this->db->set('status', 'canceled');
        $this->db->where('id', $tid);
        $this->db->update('cberp_purchase_orders');
        //reverse
        $this->db->select('debit,credit,acid');
        $this->db->from('cberp_transactions');
        $this->db->where('tid', $tid);
        $this->db->where('ext', 1);
        $query = $this->db->get();
        $revresult = $query->result_array();
        foreach ($revresult as $trans) {
            $amt = $trans['debit'] - $trans['credit'];
            $this->db->set('lastbal', "lastbal+$amt", FALSE);
            $this->db->where('id', $trans['acid']);
            $this->db->update('cberp_accounts');
        }
        $this->db->select('pid,qty');
        $this->db->from('cberp_purchase_order_items');
        $this->db->where('tid', $tid);
        $query = $this->db->get();
        $prevresult = $query->result_array();
        foreach ($prevresult as $prd) {
            $amt = $prd['qty'];
            $this->db->set('qty', "qty-$amt", FALSE);
            $this->db->where('pid', $prd['pid']);
            $this->db->update('cberp_products');
        }
        $this->db->delete('cberp_transactions', array('tid' => $tid, 'ext' => 1));
        echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('Purchase canceled!')));
    }

    public function translist()
    {
        // if (!$this->aauth->premission(5)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $ttype = $this->input->get('type');
        $list = $this->transactions->get_datatables($ttype);
        $data = array();
        // $no = $_POST['start'];
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $pid = $prd->id;
            $row[] = $no;
            $row[] = dateformat($prd->date);
            $row[] = '<a href="' . base_url() . 'transactions/view?id=' . $pid . '" title="View"  target="_blank">'.$prd->account.'</a>';
            $row[] = number_format($prd->debit, 2);
            // $row[] = amountExchange($prd->debit, 0, $this->aauth->get_user()->loc);
            $row[] = number_format($prd->credit, 2);
            $row[] = $prd->payer;
            $row[] = $this->lang->line($prd->method);
            $row[] = '<a href="' . base_url() . 'transactions/print_t?id=' . $pid . '" class="btn btn-crud btn-secondary btn-sm"  title="Print" target="_blank"><span class="fa fa-print"></span></a>&nbsp;<a  href="#" data-object-id="' . $pid . '" class="btn btn-crud btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->transactions->count_all($ttype),
            "recordsFiltered" => $this->transactions->count_filtered($ttype),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    // Category
    public function categories()
    {
        $this->li_a = 'misc_settings';
        if ($this->aauth->get_user()->roleid < 5) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }

        $data['catlist'] = $this->transactions->categories();
        $head['title'] = "Category";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('transactions/cat', $data);
        $this->load->view('fixed/footer');
    }

    public function createcat()
    {
        if ($this->aauth->get_user()->roleid < 5) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }

        $head['title'] = "Category";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('transactions/cat_create');
        $this->load->view('fixed/footer');
    }

    public function editcat()
    {

        if ($this->aauth->get_user()->roleid < 5) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }

        $head['title'] = "Category";
        $head['usernm'] = $this->aauth->get_user()->username;

        $id = $this->input->get('id');

        $data['cat'] = $this->transactions->cat_details($id);

        $this->load->view('fixed/header', $head);
        $this->load->view('transactions/trans-cat-edit', $data);
        $this->load->view('fixed/footer');

    }

    public function save_createcat()
    {

        if ($this->aauth->get_user()->roleid < 5) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }

        $name = $this->input->post('catname');

        if ($this->transactions->addcat($name)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function editcatsave()
    {
        if ($this->aauth->get_user()->roleid < 5) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }

        $id = $this->input->post('catid');
        $name = $this->input->post('cat_name');

        if ($this->transactions->cat_update($id, $name)) {

            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'Error!'));
        }


    }

    public function delete_cat()
    {
        if ($this->aauth->get_user()->roleid < 5) {
            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        }

        $id = $this->input->post('deleteid');
        if ($id) {
            $this->db->delete('cberp_trans_cat', array('id' => $id));
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => 'Error!'));
        }
    }

    public function save_trans()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $dual = $this->custom->api_config(65);

        $credit = 0;
        $debit = 0;
        $payer_id = $this->input->post('payer_id', true);
        $payer_ty = $this->input->post('ty_p', true);
        $payer_name = $this->input->post('payer_name', true);
        $pay_acc = $this->input->post('pay_acc', true);
        $date = $this->input->post('date', true);
        $amount = numberClean($this->input->post('amount', true));
        $pay_type = $this->input->post('pay_type', true);
        if ($pay_type == 'Income') {
            $credit = $amount;
        } elseif ($pay_type == 'Expense') {
            $debit = $amount;
        }
        $pay_cat = $this->input->post('pay_cat');
        $paymethod = $this->input->post('paymethod');
        $note = $this->input->post('note');
        $date = datefordatabase($date);
        if ($amount > 0) {
            if ($this->transactions->addtrans($payer_id, $payer_name, $pay_acc, $date, $debit, $credit, $pay_type, $pay_cat, $paymethod, $note, $this->aauth->get_user()->id, $this->aauth->get_user()->loc, $payer_ty)) {
                $lid = $this->db->insert_id();

                if ($dual['key1']) {
                    $pay_acc = $this->input->post('f_pay_acc', true);
                    $pay_cat = $this->input->post('f_pay_cat');
                    $paymethod = $this->input->post('f_paymethod');
                    $note = $this->input->post('f_note');
                    if ($pay_type == 'Income') {
                        $debit = $amount;
                        $credit = 0;
                        $pay_type_r = 'Expense';
                    } elseif ($pay_type == 'Expense') {
                        $credit = $amount;
                        $debit = 0;
                        $pay_type_r = 'Income';
                    }

                    $this->transactions->addtrans($payer_id, $payer_name, $pay_acc, $date, $debit, $credit, $pay_type_r, $pay_cat, $paymethod, $note, $this->aauth->get_user()->id, $this->aauth->get_user()->loc, $payer_ty);
                }

                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Transaction has been') . "  <a href='" . base_url() . "transactions/add' class='btn btn-secondary btn-sm ' title='Add'><span class='fa fa-plus-circle' aria-hidden='true'></span> </a> <a href='" . base_url() . 'transactions/view?id=' . $lid . "' class='btn btn-secondary btn-sm' title='View' target='_blank'><span class='fa fa-eye'></span></a> <a href='" . base_url() . "transactions' class='btn btn-secondary btn-sm' title='Transactions'><span class='fa fa-list-alt aria-hidden='true'></span></a>"));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                'Error!'));
        }

        $alert = $this->custom->api_config(66);
        if ($alert['key1'] == 1) {
            $this->load->model('communication_model');
            $subject = $payer_name . ' ' . $this->lang->line('Transaction has been');
            $body = $subject . '<br> ' . $this->lang->line('Credit') . ' ' . $this->lang->line('Amount') . ' ' . $credit . '<br> ' . $this->lang->line('Debit') . ' ' . $this->lang->line('Amount') . ' ' . $debit . '<br> ID# ' . $lid;
            $out = $this->communication_model->send_corn_email($alert['url'], $alert['url'], $subject, $body, false, '');
        }


    }

    public function save_transfer()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }

        $pay_acc = $this->input->post('pay_acc');
        $pay_acc2 = $this->input->post('pay_acc2');
        $amount = (float)$this->input->post('amount', true);

        if ($amount > 0) {
            if ($this->transactions->addtransfer($pay_acc, $pay_acc2, $amount, $this->aauth->get_user()->id, $this->aauth->get_user()->loc)) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    "Transfer has been successfully done! <a href='" . base_url() . "transactions/transfer' class='btn btn-secondary btn-sm'><span class='icon-plus-circle' aria-hidden='true'></span> " . $this->lang->line('New') . "  </a> <a href='" . base_url() . "accounts' class='btn btn-secondary btn-sm'><span class='fa fa-view' aria-hidden='true'></span>" . $this->lang->line('View') . "</a>"));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                'Error!'));
        }


    }


    public function delete_i()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }

        $id = $this->input->post('deleteid');
        if ($id) {


            echo json_encode($this->transactions->delt($id));
            $alert = $this->custom->api_config(66);

        } else {
            echo json_encode(array('status' => 'Error', 'message' => 'Error!'));
        }
    }

    public function income()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $data['permissions'] = load_permissions('Accounts','Transactions','Income');
        $head['title'] = "Income Transaction";
        $head['usernm'] = $this->aauth->get_user()->username;
        $condition = " WHERE type='Income'";
        $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_transactions','date','credit',$condition);
        $this->load->view('fixed/header', $head);
        $this->load->view('transactions/income',$data);
        $this->load->view('fixed/footer');

    }

    public function expense()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $data['permissions'] = load_permissions('Accounts','Transactions','Expense');
        $head['title'] = "Expense Transaction";
        $head['usernm'] = $this->aauth->get_user()->username;   
        $condition = " WHERE type='Expense'";
        $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_transactions','date','debit',$condition);
        $this->load->view('fixed/header', $head);
        $this->load->view('transactions/expense',$data);
        $this->load->view('fixed/footer');

    }

    public function view()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $head['title'] = "View Transaction";
        $head['usernm'] = $this->aauth->get_user()->username;
        $id = $this->input->get('id');
        $data['trans'] = $this->transactions->view($id);

        if ($data['trans']['payerid'] > 0) {
            $data['cdata'] = $this->transactions->cview($data['trans']['payerid'], $data['trans']['ext']);
        } else {
            $data['cdata'] = array('address' => 'Not Registered', 'city' => '', 'phone' => '', 'email' => '');
        }
        $this->load->view('fixed/header', $head);
        if ($data['trans']['id']) $this->load->view('transactions/view', $data);
        $this->load->view('fixed/footer');

    }


    public function print_t()
    {
        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $head['title'] = "View Transaction";
        $head['usernm'] = $this->aauth->get_user()->username;
        $id = $this->input->get('id');
        $data['trans'] = $this->transactions->view($id);
        if ($data['trans']['payerid'] > 0) {
            $data['cdata'] = $this->transactions->cview($data['trans']['payerid'], $data['trans']['ext']);
        } else {
            $data['cdata'] = array('address' => 'Not Registered', 'city' => '', 'phone' => '', 'email' => '');
        }


        ini_set('memory_limit', '64M');

        $html = $this->load->view('transactions/view-print', $data, true);

        //PDF Rendering
        $this->load->library('pdf');

        $pdf = $this->pdf->load_en();

        $pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;"><tr><td width="33%"></td><td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td><td width="33%" style="text-align: right; ">#' . $id . '</td></tr></table>');

        if ($data['trans']['id']) $pdf->WriteHTML($html);

        if ($this->input->get('d')) {

            $pdf->Output('Trans_#' . $id . '.pdf', 'D');
        } else {
            $pdf->Output('Trans_#' . $id . '.pdf', 'I');
        }


    }

    public function pay_multipleinvoices(){

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        // if (!$this->aauth->premission(1)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        //cberp_payments
        $remaining_amount = 0;
        $invoice_numbers = $this->input->post('tid');         
        // $existing_transaction_number = get_payment_trans_number($invoice_numbers);
        // $transaction_number = ($existing_transaction_number) ? $existing_transaction_number :get_latest_trans_number();
        $transaction_number = get_latest_trans_number(); 
        $receipt_number = $this->transactions->last_invoice_payment_receipt_number(); 
        $received_amount = rev_amountExchange_s($this->input->post('amount', true), 0, $this->aauth->get_user()->loc);          
        $payment_recieved = $received_amount; 
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $coa_account_id = $this->input->post('account_type_id', true);
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = datefordatabase($paydate);
        $selectedInvoice_numbers=$this->input->post('invoice_ids');
        $invoice_type=$this->input->post('invoice_type');
        $customerid=$this->input->post('customerid');

        $this->db->select('grand_total as total,subtotal,invoice_number');
        $this->db->from('cberp_invoices');
        $this->db->where_in('invoice_number', $selectedInvoice_numbers);
        $query = $this->db->get();
        $result = $query->result_array();
        // $this->db->set('payment_type', $pmethod);
        // $this->db->where_in('invoice_number', $selectedInvoice_numbers);
        // $this->db->update('cberp_invoices');

        
        $this->db->select('holder,acn');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $coa_account_id);
        $acc_query = $this->db->get();
        $account = $acc_query->row_array();
        $transaction_id = "";
        if(!empty($result)){
            $postedchequeflg = 0;
            
            $data_payments = [
                    'receipt_number' => $receipt_number,
                    'transaction_number' => $transaction_number,
                    'payment_amount' => $received_amount,
                    'payment_method' => $pmethod,
                    'chart_of_account_1' => $bank_account_id,                        
                    'chart_of_account_2' => $coa_account_id,                        
                    'note' => $note,                        
                    'created_by' => $this->session->userdata('id'),
                    'created_date' => date('Y-m-d H:i:s')
            ];
            $status = 'post dated cheque';
            if($pmethod=="Cheque")
            {
                $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
                $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
                $dataai['cheque_number'] = $this->input->post('cheque_number', true);
                $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
                $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;     

                $data_payments['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
                $data_payments['cheque_account_number'] = $this->input->post('cheque_account_number', true);
                $data_payments['cheque_number'] = $this->input->post('cheque_number', true);
                $data_payments['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
                $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($data_payments['cheque_date'])) ? 1 : 0;     
                        
            }
            else if($pmethod=="Card")
            {
                // $dataai['card_number'] = $this->input->post('card_number', true);
                // $dataai['cvc'] = $this->input->post('cvc', true);
                // $dataai['card_holder'] = $this->input->post('card_holder', true);
                // $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
            }
            else if($pmethod=="Bank")
            {
                $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
                $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
                $dataai['account_number'] = $this->input->post('account_number', true);
                $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
                $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);

                $data_payments['account_bank_name'] = $this->input->post('account_bank_name', true);
                $data_payments['account_bank_address'] = $this->input->post('account_bank_address', true);
                $data_payments['account_number'] = $this->input->post('account_number', true);
                $data_payments['account_holder_name'] = $this->input->post('account_holder_name', true);
                $data_payments['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
            }
            else{}                   
            $this->db->insert('cberp_invoice_payments', $data_payments); 
            $epsilon = 0.0001;
            foreach($result as $key=>$row)
            {
                if($row['invoice_number']==$selectedInvoice_numbers[$key])
                {     
                      

                    
                    $bank_tansaction_number = get_transnumber();
                    $invoice_numbers = $row['invoice_number'];             
                    $invoice_number =  $this->invoices_model->get_invoice_number($row['invoice_number']);
                       
                    // history_table_log('cberp_invoice_log','invoice_id',$invoice_numbers,'Payment Update');
                    // erp2025 09-01-2025 starts
                    detailed_log_history('Invoice',$invoice_numbers,'Payment Update', $_POST['changedFields']);	
                  
                    // erp2025 09-01-2025 starts
                    if($invoice_type=='Deliverynote')
                    {
                        $deliverynotes =  $this->invoices_model->delnote_by_invoice_number_with_status($invoice_number);
                            
                        $i=0;
                        if($deliverynotes)
                        {
                            foreach ($deliverynotes as $key => $value) {
                               
                                // Round values to 2 decimal places for comparison
                                $total_amount = round($value['total_amount'], 2);
                                $payment_recieved = round($payment_recieved, 2);
                            
                                // Calculate the difference
                                $difference = $total_amount - $payment_recieved;
                            
                                // Determine payment status using switch case
                                switch (true) {
                                    case ($payment_recieved == $total_amount < $payment_recieved):
                                        $payment_status = "Paid";
                                        break;
                                    case ($payment_recieved > 0 && $total_amount < $payment_recieved):
                                        $payment_status = "Paid";
                                        break;
                                    case ($payment_recieved > 0 && $total_amount == $payment_recieved):
                                        $payment_status = "Paid";
                                        break;
                                    case ($payment_recieved > 0 && $difference > 0.1):
                                        $payment_status = "Partial";
                                        break;
                                    case ($payment_recieved > 0 && $difference <= 0.1):
                                        $payment_status = "Paid";
                                        break;
                                    default:
                                        $payment_status = "Due";
                                        break;
                                }                            
                                // Update the payment status in the database
                                $this->db->update('cberp_delivery_notes', ['payment_status' => $payment_status], ['delivery_note_number' => $value['delivery_note_number']]);
                                // echo "<pre>"; print_r($payment_recieved); 
                                // echo $this->db->last_query()."\n<br>";
                                // Update remaining payment received for the next iteration
                                $payment_recieved = $payment_recieved - $total_amount;
                                
                                
                            }
                            
                            
                        }
                    }
                    // echo "<pre>"; print_r($payment_recieved);  die();
                    if ($pmethod == 'Balance') {
                        $customer = $this->transactions->check_balance($cid);
                        if (rev_amountExchange_s($customer['balance'], 0, $this->aauth->get_user()->loc) >= $received_amount) {

                            $this->db->set('balance', "balance-$received_amount", FALSE);
                            $this->db->where('id', $cid);
                            $this->db->update('cberp_customers');
                        } else {

                            $received_amount = rev_amountExchange_s($customer['balance'], 0, $this->aauth->get_user()->loc);
                            $this->db->set('balance', 0, FALSE);
                            $this->db->where('id', $cid);
                            $this->db->update('cberp_customers');
                        }
                    }
                    // #erp2024 new insertion for cberp_payments table payment_recieved_amount
                    $dataai = [];
                    
                    // $dataai['payer'] = $cname;
                    // $dataai['payerid'] = $cid;
                    $dataai['note'] = $note;
                    $dataai['payment_method'] = $pmethod;
                    $dataai['invoice_number'] = $row['invoice_number'];
                    $dataai['amount']         = $received_amount;
                    $dataai['transfered_account_id'] = $coa_account_id;
                    // $dataai['transfered_account_name'] = $account['holder'];

                   

                    $data = array(
                        'acid' => $coa_account_id,
                        // 'account' => $account['holder'],
                        'type' => 'Asset',
                        'cat' => 'Sales',
                        'debit' => $row['total'],
                        // 'credit' => $received_amount,
                        'payer' => $cname,
                        'payerid' => $cid,
                        'method' => $pmethod,
                        'date' => $paydate,
                        'eid' => $this->aauth->get_user()->id,
                        'tid' => $row['invoice_number'],
                        // 'tid' => $invoice_numbers,
                        'note' => $note,
                        'loc' => $this->aauth->get_user()->loc
                    );
                    
                    if($postedchequeflg == 0)
                    {
                        
                        // $this->db->delete('cberp_transactions',['transaction_number'=> $transaction_number]); 
                        // $this->db->delete('cberp_bank_transactions',['from_trans_number'=> $transaction_number]); 
                        $this->db->select('grand_total as total,customer_id,paid_amount as payment_recieved_amount');
                        $this->db->from('cberp_invoices');
                        $this->db->where('invoice_number', $row['invoice_number']);
                        $query = $this->db->get();
                        $invresult = $query->row();
                        $totalrm = $invresult->total - $invresult->payment_recieved_amount;
                        // echo "Invoice: " . $row['id'] . " Amount = " . $totalrm . "\n<br>";
                        $custdata = $this->transactions->check_customer_account_details($cid);
                        $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;
                       

                        if (abs($received_amount - $totalrm) < $epsilon || $received_amount > $totalrm) {
                            // Payment fully covers the remaining amount for this invoice
                            $paid_amount = $totalrm;
                            // $paid_amount = $invresult->pamnt + $totalrm;
                            $balance_amount_to_pay = (($received_amount - $totalrm) == 0) ? ($invresult->payment_recieved_amount + $received_amount) : ($invresult->payment_recieved_amount + $received_amount);
                          
                            if(($received_amount - $totalrm) == 0)
                            {
                                $invresult->payment_recieved_amount + $received_amount;
                            }
                            else{
                                $balance_amount_to_pay = $totalrm;
                            }

                            // Deduct the amount paid from the received amount
                            $received_amount = round($received_amount - $totalrm, 2);
                            $payment_recieved = $received_amount;
                            $payment_status = "paid";
                            insert_transaction('credit', 'Invoice', $paid_amount, $coa_account_id, $transaction_number, $invoice_number);
                            update_account_balance($coa_account_id, $paid_amount, 'subtract');

                            insert_transaction('debit', 'Invoice', $paid_amount, $bank_account_id, $transaction_number, $invoice_number,$customerid);
                            update_account_balance($bank_account_id, $paid_amount, 'add');

                            insert_bank_transaction('Income', $paid_amount, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_tansaction_number);

                            history_table_with_foreginkey_log('cberp_invoice_payment_log','payment_id',$this->db->insert_id(),'invoice_id',$invoice_numbers,'Create');

                            update_customer_credit($cid, $paid_amount,$cust_avalable_credit_limit);
                            update_invoice($invoice_numbers, $pmethod,  $balance_amount_to_pay, $payment_status);
                             $this->db->insert('cberp_invoice_payments_details', ['receipt_number'=>$receipt_number,'invoice_number'=>$row['invoice_number'],'paid_amount'=>$paid_amount]); 
                            insert_payment_transaction_link($invoice_number, $transaction_number, $bank_tansaction_number);

                        } else {
                            // Partial payment for this invoice
                            // ini_set('display_errors', 1);
                            // ini_set('display_startup_errors', 1);
                            // error_reporting(E_ALL);
                            if($received_amount > 0)
                            {
                                $paid_amount = $invresult->payment_recieved_amount + $received_amount;
                                $payment_status = 'partial';
                                insert_transaction('credit', 'Invoice', $received_amount, $coa_account_id, $transaction_number, $invoice_number);
                                update_account_balance($coa_account_id, $received_amount, 'subtract');

                                insert_transaction('debit', 'Invoice', $received_amount, $bank_account_id, $transaction_number, $invoice_number,$customerid);
                                update_account_balance($bank_account_id, $received_amount, 'add');

                                insert_bank_transaction('Income', $received_amount, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_tansaction_number);
                                history_table_with_foreginkey_log('cberp_invoice_payment_log','payment_id',$this->db->insert_id(),'invoice_id',$invoice_numbers,'Create');
                                update_customer_credit($cid, $received_amount,$cust_avalable_credit_limit);
                                update_invoice($invoice_numbers, $pmethod, $paid_amount, $payment_status);
                                
                                 $this->db->insert('cberp_invoice_payments_details', ['receipt_number'=>$receipt_number,'invoice_number'=>$row['invoice_number'],'paid_amount'=>$received_amount]); 
                                insert_payment_transaction_link($invoice_number, $transaction_number, $bank_tansaction_number);
                            }
                            $received_amount = 0; 
                        }

                        // $dataai['transaction_number'] = $transaction_number;
                        // $this->db->insert('cberp_payments', $dataai);

                        // die($this->db->last_query());
                        // $logdata = array(
                        //     'invoice_number' => $row['invoice_number'],
                        //     'transaction_id' => $transaction_number,
                        //     'transactionai_id' => $this->db->insert_id(),
                        //     'created_by' => $this->session->userdata('id'),
                        //     'created_date' => date('Y-m-d'),
                        //     'created_time' => date('H:i:s'),
                        //     'ip_address'=> $this->getClientIpAddress(),
                        //     'payment_status'=> $status,
                        // );
                        // $this->db->insert('invoice_payment_log_ai', $logdata);
                        
                    }
                    else{
                        $this->db->set(['status'=>'post dated cheque','pmethod'=>$pmethod]);
                        $this->db->where('invoice_number', $invoice_number);
                        $this->db->update('cberp_invoices');
                    }
                   
                    // ========================================================================
                }



            }
           
        }
        $response = array('status' => 'Success', 'message' => $this->lang->line('Transaction has been added'));
        echo json_encode($response);
        
    }

    public function confirm_deposit(){

        // if (!$this->aauth->premission(1)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $invoice_id = $this->input->post('invoice_id');
        $trans_ai_id = $this->input->post('trans_ai_id', true);
        $bankdepositdate = date('Y-m-d', strtotime($this->input->post('bankdepositdate', true)));
        $transation_ai_details = $this->transactions->transactions_ai_details($trans_ai_id);
        $cname = $transation_ai_details['payer'];
        $cid = $transation_ai_details['payerid'];
        $subtotal = $transation_ai_details['amount'];
        $pmethod = $transation_ai_details['payment_method'];
        $paydate = $bankdepositdate;
        $trans_ai_accid = $transation_ai_details['transfered_account_id'];
        $transfered_account_name = $transation_ai_details['transfered_account_name'];
        $note = $transation_ai_details['note'];
   
        $data = array(
            'acid' => $trans_ai_accid,
            'account' => $transfered_account_name,
            'type' => 'Income',
            'cat' => 'Sales',
            'credit' => $subtotal,
            'payer' => $cname,
            'payerid' => $cid,
            'method' => $pmethod,
            'date' => $paydate,
            'eid' => $this->aauth->get_user()->id,
            'tid' => $invoice_id,
            'note' => $note,
            'loc' => $this->aauth->get_user()->loc
        );
        $this->db->insert('cberp_transactions', $data);
        $tttid = $this->db->insert_id();
        $transaction_id = $tttid;                    
        $dataai['transaction_id'] = $tttid;

        $this->db->set('payment_recieved_date', $paydate);
        $this->db->set('payment_recieved_amount', $subtotal);
        $this->db->set('pamnt', $subtotal);
        $this->db->set('status', 'paid');
        $this->db->where('id', $invoice_id);
        $this->db->update('cberp_invoices');


        $logdata = array(
            'invoiceid' => $invoice_id,
            'transaction_id' => $transaction_id,
            'transactionai_id' => $trans_ai_id,
            'created_by' => $this->session->userdata('id'),
            'created_date' => date('Y-m-d'),
            'created_time' => date('H:i:s'),
            'ip_address'=> $this->getClientIpAddress(),
            'payment_status'=> 'paid',
        );
        $this->db->insert('invoice_payment_log_ai', $logdata);

        $this->db->set('lastbal', "lastbal+$subtotal", FALSE);
        $this->db->where('id', $trans_ai_accid);
        $this->db->update('cberp_accounts');

        $custdata = $this->transactions->check_customer_account_details($cid);

        $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;
        $addamount = $cust_avalable_credit_limit + $subtotal;
        $this->db->set('avalable_credit_limit', $addamount);
        $this->db->where('id', $cid);
        $this->db->update('cberp_customers');
        $response = array('status' => 'Success', 'message' => 'Payment Deposit has been confirmed');
        echo json_encode($response);
        
    }

    public function getClientIpAddress() {
        $ipAddress = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // Check if IP is from a shared internet connection
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Check if IP is passed from a proxy
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $ipList = explode(',', $ipAddress);
            $ipAddress = trim($ipList[0]); // Take the first IP in case there are multiple proxies
        } else {
            // Get the standard remote IP address
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        return $ipAddress;
    }
    

    public function account_transactions(){

        $bankcode = $this->input->get('code');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = $bankcode." -  Transactions";
        $this->load->view('fixed/header', $head);        
        $data['account_master_details'] = $this->transactions->load_account_details_by_code($bankcode);
        $data['transaction_records'] = $this->transactions->load_account_transactions_by_code($bankcode);   
        // echo '<pre>'; print_r($data['transaction_records']); die();
        $this->load->view('transactions/account_transactions', $data);
        $this->load->view('fixed/footer');
    }

    public function banking_transaction(){
        $data['permissions'] = load_permissions('Accounts','Banking','Transactions');
        $trans_ref_number = $this->input->get('ref');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Bank Transaction - #".$trans_ref_number;
        $this->load->view('fixed/header', $head);  
        $data['transaction_records'] = $this->transactions->get_bank_transaction_details($trans_ref_number);
        $transaction_link_data = $this->transactions->get_bank_transaction_link_details($trans_ref_number);
        // echo "<pre>"; print_r($transaction_link_data);
        // die();
        switch ($transaction_link_data['trans_type']) 
        {
            case 'Opening':
                $data['transaction_link_data'] = $transaction_link_data;
                $data['transaction_details'] = $this->transactions->get_opening_balance_details($data['transaction_records']['trans_ref_number']);
                $this->load->view('transactions/bank_opening_balance', $data);
                break;

            case 'Deposit':
                //for add new income
                $data['transaction_link_data'] = $transaction_link_data;
                $data['deposit_details'] = $this->transactions->get_deposit_details($data['transaction_records']['trans_ref_number']);
                // echo "<pre>"; print_r($data['deposit_details']); die();
                $this->load->view('transactions/bank_deposit', $data);
                break;
        
            case 'Invoice':
                $data['invoice'] = $this->transactions->get_invoice_details($data['transaction_records']['trans_type_number']);
               
                $this->load->view('transactions/bank_invoice_transactions', $data);
                break;
        
            case 'Purchase':
                $data['trans_ref_number'] = $trans_ref_number;
                $data['purchase'] = $this->transactions->get_purchase_receipt_details_by_refernce_number($trans_ref_number);
                //   echo "<pre>"; print_r($data['purchase']); die();
                $this->load->view('transactions/bank_purchase_transactions', $data);                
                break;


            case 'Purchase Return':
                
                $data['trans_ref_number'] = $trans_ref_number;
                $data['purchase'] = $this->transactions->get_purchase_return_details_by_refernce_number_new($trans_ref_number);
                // echo "<pre>"; print_r($data['purchase']); die();
                $this->load->view('transactions/bank_purchase_return_transactions', $data);                
                break;

            case 'Expense Claim':
                $data['trans_ref_number'] = $trans_ref_number;
               
                $data['expenseclaim'] = $this->transactions->get_expense_claim_details_by_refernce_number($trans_ref_number);
                $this->load->view('transactions/expense_claims_transactions', $data);                
                break;

            case 'Invoice Return':
                $data['invoice'] = $this->transactions->get_invoice_return_details($data['transaction_records']['trans_type_number']);
                $this->load->view('transactions/bank_invoice_return_transactions', $data);
                break;  
        
            default:
                break;
        }
        
       
        
        $this->load->view('fixed/footer');
    }

    public function transaction_edit()
    {

        $trans_ref_number = $this->input->get('ref');
        $invoice_data = $this->transactions->get_invoice_details_bank_trans_number($trans_ref_number);
        $invoiceid = $invoice_data['invoiceid'];
        $invoice_number = $invoice_data['invoice_number'];
        $customerid = $invoice_data['csd'];
        $data['transaction_data'] = $invoice_data;
        
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        
        $data['invoice'] = $this->invoices_model->invoice_details($invoiceid, $this->limited);
        // $data['attach'] = $this->invoices_model->attach($invoiceid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = "Customer Payment for Invoice -  " . $data['invoice']['tid'];
        $this->load->view('fixed/header', $head);
        $data['accountheaders'] = $this->accounts_model->load_coa_account_headers();
        $data['accounttypes'] = $this->accounts_model->load_coa_account_types();
        $data['accountlist'] = $this->accounts_model->load_account_list();
       
        $accountchild=[];
        foreach($data['accountlist'] as $single){
            $accountchild[$single['coa_header_id']][] = $single;
        } 
        $data['accountlists'] = $accountchild;
        $data['bankaccounts'] = bank_account_list();
        $data['transaction_ai'] = $this->transactions->get_transaction_ai_details($invoice_data['transaction_number']);
        // echo "<pre>"; print_r($data['invoice']); die();
        $this->load->view('transactions/bank_transaction_edit', $data);
        $this->load->view('fixed/footer');
    }

    public function update_transaction(){

        // if (!$this->aauth->premission(1)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $remaining_amount = 0;
        $tid = $this->input->post('invoiceid');         
        $transaction_number = $this->input->post('transaction_number', true);
        $bank_transaction_number = $this->input->post('bank_transaction_number', true);
        $trans_ai_id = $this->input->post('trans_ai_id', true);
        $received_amount = rev_amountExchange_s($this->input->post('amount', true), 0, $this->aauth->get_user()->loc);  
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $coa_account_id = $this->input->post('account_type_id', true);
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = datefordatabase($paydate);

        
        $invoice_number   =  $this->input->post('invoice_number');
        $trans_ref_number =  $this->input->post('trans_ref_number');
        $old_amount =  $this->input->post('old_amount');
        $totalinvoiceamount =  $this->input->post('totalinvoiceamount');
        $totaldueamt =  $this->input->post('totaldueamt');        
        $paid_amount = $this->input->post('paid_amount', true); 

        //erp2024 09-12-2024         
        $invoice_type=$this->input->post('invoice_type');        
        $payment_recieved = $received_amount;

        $selectedInvoice_ids=$this->input->post('invoiceid');
        $this->db->select('total,id,payment_recieved_amount');
        $this->db->from('cberp_invoices');
        $this->db->where('id', $selectedInvoice_ids);
        $query = $this->db->get();
        $result = $query->result_array();
        
        
        
        $this->db->select('holder');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $coa_account_id);
        $acc_query = $this->db->get();
        $account = $acc_query->row_array();
        $transaction_id = "";
        if(!empty($result)){
            $epsilon = 0.0001;
            foreach($result as $key=>$row)
            {
                if($row['id']==$selectedInvoice_ids)
                {         


                    $invoice_number =  $this->invoices_model->get_invoice_number($row['id']);
                    if($invoice_type=='Deliverynote')
                    {
                        $deliverynotes =  $this->invoices_model->delnote_by_invoice_number_with_status($invoice_number);
                        
                        $i=0;
                        if($deliverynotes)
                        {
                            foreach ($deliverynotes as $key => $value) {
                               
                                // Round values to 2 decimal places for comparison
                                $total_amount = round($value['total_amount'], 2);
                                $payment_recieved = round($payment_recieved, 2);
                            
                                // Calculate the difference
                                $difference = $total_amount - $payment_recieved;
                            
                                // Determine payment status using switch case
                                switch (true) {
                                    case ($payment_recieved == $total_amount < $payment_recieved):
                                        $payment_status = "Paid";
                                        break;
                                    case ($payment_recieved > 0 && $total_amount < $payment_recieved):
                                        $payment_status = "Paid";
                                        break;
                                    case ($payment_recieved > 0 && $total_amount == $payment_recieved):
                                        $payment_status = "Paid";
                                        break;
                                    case ($payment_recieved > 0 && $difference > 0.1):
                                        $payment_status = "Partial";
                                        break;
                                    case ($payment_recieved > 0 && $difference <= 0.1):
                                        $payment_status = "Paid";
                                        break;
                                    default:
                                        $payment_status = "Due";
                                        break;
                                }                            
                                // Update the payment status in the database
                                $this->db->update('cberp_delivery_notes', ['payment_status' => $payment_status], ['delevery_note_id' => $value['delevery_note_id']]);
                                // Update remaining payment received for the next iteration
                                $payment_recieved = $payment_recieved - $total_amount;
                                detailed_log_history('Deliverynote',$value['delevery_note_id'],'Payment Update', $_POST['changedFields']);
                                
                            }
                            
                            
                        }
                    }
                    // echo "<pre>"; print_r($deliverynotes); die();
                    if ($pmethod == 'Balance') {
                        $customer = $this->transactions->check_balance($cid);
                        if (rev_amountExchange_s($customer['balance'], 0, $this->aauth->get_user()->loc) >= $received_amount) {

                            $this->db->set('balance', "balance-$received_amount", FALSE);
                            $this->db->where('id', $cid);
                            $this->db->update('cberp_customers');
                        } else {

                            $received_amount = rev_amountExchange_s($customer['balance'], 0, $this->aauth->get_user()->loc);
                            $this->db->set('balance', 0, FALSE);
                            $this->db->where('id', $cid);
                            $this->db->update('cberp_customers');
                        }
                    }
                    // #erp2024 new insertion for cberp_payments table
                    $nullupdate = [
                        'card_number' => NULL,
                        'cvc' => NULL,
                        'card_holder' => NULL,
                        'card_expiry_date' => NULL,
                        'cheque_pay_from' => NULL,
                        'cheque_account_number' => NULL,
                        'cheque_number' => NULL,
                        'cheque_date' => NULL,
                        'account_bank_name' => NULL,
                        'account_bank_address' => NULL,
                        'account_number' => NULL,
                        'account_holder_name' => NULL,
                        'account_ifsc_code' => NULL
                    ];
                    $dataai = [];
                    
                    $dataai['payer'] = $cname;
                    $dataai['payerid'] = $cid;
                    $dataai['note'] = $note;
                    $dataai['payment_method'] = $pmethod;
                    $dataai['invoice_id']     = $row['id'];
                    $dataai['amount']         = $received_amount;
                    $dataai['transfered_account_id'] = $coa_account_id;
                    $dataai['transfered_account_name'] = $account['holder'];
                    $postedchequeflg = 0;
                    $status = 'post dated cheque';
                    if($pmethod=="Cheque")
                    {
                        $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
                        $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
                        $dataai['cheque_number'] = $this->input->post('cheque_number', true);
                        $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
                        $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;     
                               
                    }
                    else if($pmethod=="Card")
                    {
                        $dataai['card_number'] = $this->input->post('card_number', true);
                        $dataai['cvc'] = $this->input->post('cvc', true);
                        $dataai['card_holder'] = $this->input->post('card_holder', true);
                        $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
                    }
                    else if($pmethod=="Bank")
                    {
                        $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
                        $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
                        $dataai['account_number'] = $this->input->post('account_number', true);
                        $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
                        $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
                    }
                    else{}
                    $data = array(
                        'acid' => $coa_account_id,
                        'type' => 'Asset',
                        'cat' => 'Sales',
                        'debit' => $row['total'],
                        'payer' => $cname,
                        'payerid' => $cid,
                        'method' => $pmethod,
                        'date' => $paydate,
                        'eid' => $this->aauth->get_user()->id,
                        'tid' => $row['id'],
                        'note' => $note,
                        'loc' => $this->aauth->get_user()->loc
                    );
                    
                    if($postedchequeflg == 0)
                    {
                 
                        $this->db->set('payment_recieved_amount', "payment_recieved_amount-$old_amount", FALSE);
                        $this->db->set('pamnt', "pamnt-$old_amount", FALSE);
                        $this->db->set('status', "partial");
                        $this->db->where('id', $tid);
                        $this->db->update('cberp_invoices');

             
                        
                        $totalpaid = ($received_amount - $old_amount) + $paid_amount;
                        if ($totalpaid == $totalinvoiceamount) {
                            $status = 'paid';
                        } elseif (($totalinvoiceamount == $paid_amount) && ($received_amount == $old_amount)) {
                            $status = 'paid'; 
                        } else {
                            $status = 'partial'; 
                        }

                        $this->db->set('status', $status);
                        $this->db->set('payment_recieved_date', date('Y-m-d'));
                        $this->db->set('payment_recieved_amount', "payment_recieved_amount+$received_amount", FALSE);
                        $this->db->set('pamnt', "pamnt+$received_amount", FALSE);
                        $this->db->where('id', $tid);
                        $this->db->update('cberp_invoices');
                        
                        $this->db->select('total,csd,pamnt');
                        $this->db->from('cberp_invoices');
                        $this->db->where('id', $row['id']);
                        $query = $this->db->get();
                        $invresult = $query->row();
                        $totalrm = $invresult->total - $invresult->pamnt;
                        $custdata = $this->transactions->check_customer_account_details($cid);
                        $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;

                        $this->invoices_model->reset_credit_accounts($transaction_number);
                        $this->invoices_model->reset_debit_accounts($transaction_number);  
                        reset_customer_credit($cid, $old_amount,$cust_avalable_credit_limit);
                        $custdata = $this->transactions->check_customer_account_details($cid);
                        $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;
                        $this->db->delete('cberp_transactions',['transaction_number'=>$transaction_number]);

                        $payment_recieved = $received_amount;

                        insert_transaction('credit', 'Invoice', $received_amount, $coa_account_id, $transaction_number, $invoice_number);
                        update_account_balance($coa_account_id, $received_amount, 'subtract');

                        insert_transaction('debit', 'Invoice', $received_amount, $bank_account_id, $transaction_number, $invoice_number);
                        update_account_balance($bank_account_id, $received_amount, 'add');

                        update_bank_transaction('Income', $received_amount, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_transaction_number,$trans_ref_number);

                        update_customer_credit($cid, $received_amount,$cust_avalable_credit_limit);
                        $dataai['trans_num'] = $transaction_number;
                        $this->db->update('cberp_payments', $nullupdate,['id'=>$trans_ai_id]);
                        $this->db->update('cberp_payments', $dataai,['id'=>$trans_ai_id]);

                        $logdata = array(
                            'invoiceid' => $row['id'],
                            'transaction_id' => $transaction_number,
                            'transactionai_id' => $this->db->insert_id(),
                            'created_by' => $this->session->userdata('id'),
                            'created_date' => date('Y-m-d'),
                            'created_time' => date('H:i:s'),
                            'ip_address'=> $this->getClientIpAddress(),
                            'payment_status'=> $status,
                        );
                        $this->db->insert('invoice_payment_log_ai', $logdata);
                        detailed_log_history('Invoice',$selectedInvoice_ids,'Payment Update', $_POST['changedFields']);
                        $response = array('status' => 'Success', 'message' => $this->lang->line('Transaction has been added'));
                        echo json_encode($response);
                        die();

                       
                        
                        
                        if($received_amount > 0)
                        {
                            
                            
                        }

                        //reset invoice payment amount
                        if($invresult->pamnt > 0)
                        {
                            $invoice_data1 = [];
                            // $substracted_amt = ($received_amount - $invresult->pamnt);
                            $substracted_amt = ($received_amount - $old_amount);
                            $totalreceived = $substracted_amt + $invresult->pamnt;
                            if($totalreceived == $totalinvoiceamount)
                            {
                                $invoice_data1['status'] = 'paid'; 
                            }
                            else{
                                $invoice_data1['status'] = 'partial'; 
                            }

                                                      
                     
                            $this->db->select('total,csd,pamnt');
                            $this->db->from('cberp_invoices');
                            $this->db->where('id', $row['id']);
                            $query = $this->db->get();
                            $invresult = $query->row();
                            $totalrm = $invresult->total - $invresult->pamnt;
                        }
                        // echo $this->db->last_query();
                        // echo "invoice amt ". $totalinvoiceamount. "<br>";
                        // echo "pamount ". $invresult->pamnt. "<br>";
                        // echo "recievd ". $received_amount. "<br>";
                        // echo "<br> substract ".$substracted_amt. "<br>";
                        // echo "<br> reduced_amount ".$reduced_amount. "<br>";
                        // echo "<br> added ".$totalreceived. "<br>";
                        // die();
                        //reset account balance

                        


                        // die();
                        // if (abs($received_amount - $totalrm) < $epsilon || $received_amount > $totalrm) {
                        //     echo "Full";
                        //     // Payment fully covers the remaining amount for this invoice
                            
                            
                        //     // $paid_amount = $invresult->pamnt + $totalrm;
                        //     if (($received_amount - $totalrm) == 0) {
                        //         $balance_amount_to_pay = $invresult->pamnt + $received_amount;
                        //     } else {
                        //         $balance_amount_to_pay = $received_amount;
                        //     }
                        //     if($totalrm != 0)
                        //     {                                   
                        //         $paid_amount = $totalrm;                               
                        //         // update_invoice($tid, $pmethod, $balance_amount_to_pay, $payment_status);
                        //     }
                        //     else{
                        //         $paid_amount =  $received_amount;
                        //     }
                        //     // Deduct the amount paid from the received amount
                        //     $received_amount = round($received_amount - $totalrm, 2);

                        //     $payment_status = "paid";

                        //     // Debug information
                        //     // echo "Payment Status: " . $payment_status . "<br>";
                        //     // echo "Paid Amount: " . $paid_amount . "<br>";
                        //     // echo "Balance Amount to Pay: " . $balance_amount_to_pay . "<br>";
                        //     // echo "Remaining Received Amount: " . $received_amount . "<br>";
                        //     // echo "Remaining table amount: " . $totalrm . "<br>";
                        //     // die();


                        //     insert_transaction('credit', 'Invoice', $balance_amount_to_pay, $coa_account_id, $transaction_number, $invoice_number);
                        //     update_account_balance($coa_account_id, $balance_amount_to_pay, 'subtract');

                        //     insert_transaction('debit', 'Invoice', $balance_amount_to_pay, $bank_account_id, $transaction_number, $invoice_number);
                        //     update_account_balance($bank_account_id, $balance_amount_to_pay, 'add');

                        //     update_bank_transaction('Income', $balance_amount_to_pay, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_transaction_number,$trans_ref_number);

                        //     update_customer_credit($cid, $balance_amount_to_pay,$cust_avalable_credit_limit);
                        //     // if($totalrm != 0)
                        //     // {   
                        //     //     update_invoice($tid, $pmethod,  $balance_amount_to_pay, $payment_status);
                        //     // } 

                        // } else {
                        //     echo "Partial";
                        //     // Partial payment for this invoice
                        //     // ini_set('display_errors', 1);
                        //     // ini_set('display_startup_errors', 1);
                        //     // error_reporting(E_ALL);
                        //     if($received_amount > 0)
                        //     {
                        //         $paid_amount = $invresult->pamnt + $received_amount;
                        //         $payment_status = 'partial';
                        //         //  $payment_status ="\n<br>";
                        //         // echo $paid_amount."\n<br>";
                        //         // die();
                                
                        //         insert_transaction('credit', 'Invoice', $received_amount, $coa_account_id, $transaction_number, $invoice_number);
                        //         update_account_balance($coa_account_id, $received_amount, 'subtract');

                        //         insert_transaction('debit', 'Invoice', $received_amount, $bank_account_id, $transaction_number, $invoice_number);
                        //         update_account_balance($bank_account_id, $received_amount, 'add');

                        //         update_bank_transaction('Income', $received_amount, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_transaction_number,$trans_ref_number);

                        //         update_customer_credit($cid, $received_amount,$cust_avalable_credit_limit);
                        //         // update_invoice($tid, $pmethod, $paid_amount, $payment_status);
                                
                              
                        //     }
                        //     $received_amount = 0; 
                        // }
                        
                    }
                    else{
                        $this->db->set(['status'=>'post dated cheque','pmethod'=>$pmethod]);
                        $this->db->where('id', $tid);
                        $this->db->update('cberp_invoices');
                    }
                   
                    // ========================================================================
                }



            }
           
        }
        $response = array('status' => 'Success', 'message' => $this->lang->line('Transaction has been added'));
        echo json_encode($response);
        
    }
    public function update_transactionold(){

        // if (!$this->aauth->premission(1)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $remaining_amount = 0;
        $tid = $this->input->post('invoiceid');         
        $transaction_number = $this->input->post('transaction_number', true);
        $bank_transaction_number = $this->input->post('bank_transaction_number', true);
        $trans_ai_id = $this->input->post('trans_ai_id', true);
        $received_amount = rev_amountExchange_s($this->input->post('amount', true), 0, $this->aauth->get_user()->loc);  
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $coa_account_id = $this->input->post('account_type_id', true);
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = datefordatabase($paydate);

        
        $invoice_number   =  $this->input->post('invoice_number');
        $trans_ref_number =  $this->input->post('trans_ref_number');
        $old_amount =  $this->input->post('old_amount');
        $totalinvoiceamount =  $this->input->post('totalinvoiceamount');
        $totaldueamt =  $this->input->post('totaldueamt');


        $selectedInvoice_ids=$this->input->post('invoiceid');
        $this->db->select('total,id,payment_recieved_amount');
        $this->db->from('cberp_invoices');
        $this->db->where('id', $selectedInvoice_ids);
        $query = $this->db->get();
        $result = $query->result_array();
        
     
        
        $this->db->select('holder');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $coa_account_id);
        $acc_query = $this->db->get();
        $account = $acc_query->row_array();
        $transaction_id = "";
        if(!empty($result)){
            $epsilon = 0.0001;
            foreach($result as $key=>$row)
            {
                if($row['id']==$selectedInvoice_ids)
                {         


                    if ($pmethod == 'Balance') {
                        $customer = $this->transactions->check_balance($cid);
                        if (rev_amountExchange_s($customer['balance'], 0, $this->aauth->get_user()->loc) >= $received_amount) {

                            $this->db->set('balance', "balance-$received_amount", FALSE);
                            $this->db->where('id', $cid);
                            $this->db->update('cberp_customers');
                        } else {

                            $received_amount = rev_amountExchange_s($customer['balance'], 0, $this->aauth->get_user()->loc);
                            $this->db->set('balance', 0, FALSE);
                            $this->db->where('id', $cid);
                            $this->db->update('cberp_customers');
                        }
                    }
                    // #erp2024 new insertion for cberp_payments table
                    $nullupdate = [
                        'card_number' => NULL,
                        'cvc' => NULL,
                        'card_holder' => NULL,
                        'card_expiry_date' => NULL,
                        'cheque_pay_from' => NULL,
                        'cheque_account_number' => NULL,
                        'cheque_number' => NULL,
                        'cheque_date' => NULL,
                        'account_bank_name' => NULL,
                        'account_bank_address' => NULL,
                        'account_number' => NULL,
                        'account_holder_name' => NULL,
                        'account_ifsc_code' => NULL
                    ];
                    $dataai = [];
                    
                    $dataai['payer'] = $cname;
                    $dataai['payerid'] = $cid;
                    $dataai['note'] = $note;
                    $dataai['payment_method'] = $pmethod;
                    $dataai['invoice_id']     = $row['id'];
                    $dataai['amount']         = $received_amount;
                    $dataai['transfered_account_id'] = $coa_account_id;
                    $dataai['transfered_account_name'] = $account['holder'];
                    $postedchequeflg = 0;
                    $status = 'post dated cheque';
                    if($pmethod=="Cheque")
                    {
                        $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
                        $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
                        $dataai['cheque_number'] = $this->input->post('cheque_number', true);
                        $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
                        $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;     
                               
                    }
                    else if($pmethod=="Card")
                    {
                        $dataai['card_number'] = $this->input->post('card_number', true);
                        $dataai['cvc'] = $this->input->post('cvc', true);
                        $dataai['card_holder'] = $this->input->post('card_holder', true);
                        $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
                    }
                    else if($pmethod=="Bank")
                    {
                        $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
                        $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
                        $dataai['account_number'] = $this->input->post('account_number', true);
                        $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
                        $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
                    }
                    else{}
                    $data = array(
                        'acid' => $coa_account_id,
                        'type' => 'Asset',
                        'cat' => 'Sales',
                        'debit' => $row['total'],
                        'payer' => $cname,
                        'payerid' => $cid,
                        'method' => $pmethod,
                        'date' => $paydate,
                        'eid' => $this->aauth->get_user()->id,
                        'tid' => $row['id'],
                        'note' => $note,
                        'loc' => $this->aauth->get_user()->loc
                    );
                    
                    if($postedchequeflg == 0)
                    {
                        
                        $this->db->select('total,csd,pamnt');
                        $this->db->from('cberp_invoices');
                        $this->db->where('id', $row['id']);
                        $query = $this->db->get();
                        $invresult = $query->row();
                        $totalrm = $invresult->total - $invresult->pamnt;
                        $custdata = $this->transactions->check_customer_account_details($cid);
                        $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;
                        if($received_amount > 0)
                        {
                            $dataai['trans_num'] = $transaction_number;
                            $this->db->update('cberp_payments', $nullupdate,['id'=>$trans_ai_id]);
                            $this->db->update('cberp_payments', $dataai,['id'=>$trans_ai_id]);
                            $logdata = array(
                                'invoiceid' => $row['id'],
                                'transaction_id' => $transaction_number,
                                'transactionai_id' => $this->db->insert_id(),
                                'created_by' => $this->session->userdata('id'),
                                'created_date' => date('Y-m-d'),
                                'created_time' => date('H:i:s'),
                                'ip_address'=> $this->getClientIpAddress(),
                                'payment_status'=> $status,
                            );
                            // $this->db->insert('invoice_payment_log_ai', $logdata);
                        }

                        //reset invoice payment amount
                        if($invresult->pamnt > 0)
                        {
                            $invoice_data1 = [];
                            // $substracted_amt = ($received_amount - $invresult->pamnt);
                            $substracted_amt = ($received_amount - $old_amount);
                            $totalreceived = $substracted_amt + $invresult->pamnt;
                            if($totalreceived == $totalinvoiceamount)
                            {
                                $invoice_data1['status'] = 'paid'; 
                            }
                            else{
                                $invoice_data1['status'] = 'partial'; 
                            }

                                                      
                            $invoice_data1['payment_recieved_amount'] = $totalreceived; 
                            $invoice_data1['pamnt'] = $totalreceived; 
                            $this->db->update('cberp_invoices',$invoice_data1,['id'=>$tid]);
                            $this->db->select('total,csd,pamnt');
                            $this->db->from('cberp_invoices');
                            $this->db->where('id', $row['id']);
                            $query = $this->db->get();
                            $invresult = $query->row();
                            $totalrm = $invresult->total - $invresult->pamnt;
                        }
                        // echo $this->db->last_query();
                        // echo "invoice amt ". $totalinvoiceamount. "<br>";
                        // echo "pamount ". $invresult->pamnt. "<br>";
                        // echo "recievd ". $received_amount. "<br>";
                        // echo "<br> substract ".$substracted_amt. "<br>";
                        // echo "<br> reduced_amount ".$reduced_amount. "<br>";
                        // echo "<br> added ".$totalreceived. "<br>";
                        // die();
                        //reset account balance

                        $this->invoices_model->reset_credit_accounts($transaction_number);
                        $this->invoices_model->reset_debit_accounts($transaction_number);  
                        reset_customer_credit($cid, $old_amount,$cust_avalable_credit_limit);
                        $custdata = $this->transactions->check_customer_account_details($cid);
                        $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;
                        $this->db->delete('cberp_transactions',['transaction_number'=>$transaction_number]);


                        // die();
                        if (abs($received_amount - $totalrm) < $epsilon || $received_amount > $totalrm) {
                            echo "Full";
                            // Payment fully covers the remaining amount for this invoice
                            
                            
                            // $paid_amount = $invresult->pamnt + $totalrm;
                            if (($received_amount - $totalrm) == 0) {
                                $balance_amount_to_pay = $invresult->pamnt + $received_amount;
                            } else {
                                $balance_amount_to_pay = $received_amount;
                            }
                            if($totalrm != 0)
                            {                                   
                                $paid_amount = $totalrm;                               
                                // update_invoice($tid, $pmethod, $balance_amount_to_pay, $payment_status);
                            }
                            else{
                                $paid_amount =  $received_amount;
                            }
                            // Deduct the amount paid from the received amount
                            $received_amount = round($received_amount - $totalrm, 2);

                            $payment_status = "paid";

                            // Debug information
                            // echo "Payment Status: " . $payment_status . "<br>";
                            // echo "Paid Amount: " . $paid_amount . "<br>";
                            // echo "Balance Amount to Pay: " . $balance_amount_to_pay . "<br>";
                            // echo "Remaining Received Amount: " . $received_amount . "<br>";
                            // echo "Remaining table amount: " . $totalrm . "<br>";
                            // die();


                            insert_transaction('credit', 'Invoice', $balance_amount_to_pay, $coa_account_id, $transaction_number, $invoice_number);
                            update_account_balance($coa_account_id, $balance_amount_to_pay, 'subtract');

                            insert_transaction('debit', 'Invoice', $balance_amount_to_pay, $bank_account_id, $transaction_number, $invoice_number);
                            update_account_balance($bank_account_id, $balance_amount_to_pay, 'add');

                            update_bank_transaction('Income', $balance_amount_to_pay, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_transaction_number,$trans_ref_number);

                            update_customer_credit($cid, $balance_amount_to_pay,$cust_avalable_credit_limit);
                            // if($totalrm != 0)
                            // {   
                            //     update_invoice($tid, $pmethod,  $balance_amount_to_pay, $payment_status);
                            // } 

                        } else {
                            echo "Partial";
                            // Partial payment for this invoice
                            // ini_set('display_errors', 1);
                            // ini_set('display_startup_errors', 1);
                            // error_reporting(E_ALL);
                            if($received_amount > 0)
                            {
                                $paid_amount = $invresult->pamnt + $received_amount;
                                $payment_status = 'partial';
                                //  $payment_status ="\n<br>";
                                // echo $paid_amount."\n<br>";
                                // die();
                                
                                insert_transaction('credit', 'Invoice', $received_amount, $coa_account_id, $transaction_number, $invoice_number);
                                update_account_balance($coa_account_id, $received_amount, 'subtract');

                                insert_transaction('debit', 'Invoice', $received_amount, $bank_account_id, $transaction_number, $invoice_number);
                                update_account_balance($bank_account_id, $received_amount, 'add');

                                update_bank_transaction('Income', $received_amount, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_transaction_number,$trans_ref_number);

                                update_customer_credit($cid, $received_amount,$cust_avalable_credit_limit);
                                // update_invoice($tid, $pmethod, $paid_amount, $payment_status);
                                
                              
                            }
                            $received_amount = 0; 
                        }
                        
                    }
                    else{
                        // $this->db->set(['status'=>'post dated cheque','pmethod'=>$pmethod]);
                        // $this->db->where('id', $tid);
                        // $this->db->update('cberp_invoices');
                    }
                   
                    // ========================================================================
                }



            }
           
        }
        // $response = array('status' => 'Success', 'message' => $this->lang->line('Transaction has been added'));
        // echo json_encode($response);
        
    }

    //erp2024 add newfunction for paypurchase edit 26-11-2024
    public function paypurchase_edit()
    {


        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }

        $received_amount = 0;
        $transaction_number = get_latest_trans_number();
        $tid = $this->input->post('tid', true);
        $receipt_number = $this->input->post('receipt_number', true);
        $receipt_id = $this->input->post('receipt_id', true);
        $purchase_number = $this->input->post('purchase_number', true);    
        $purchase_id = $this->input->post('purchase_id', true);    
        $amount = $this->input->post('amount', true);
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $acid = $this->input->post('account_type_id', true);        
        $coa_account_id = $acid;
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = datefordatabase($paydate);


        // erp2024 26-11-2024 new items
        $trans_ai_id = $this->input->post('trans_ai_id', true);
        $transaction_number = $this->input->post('transaction_number', true);
        $bank_tansaction_number = $this->input->post('bank_transaction_number', true);
        $old_amount = $this->input->post('old_amount', true);
        $totaldueamt = $this->input->post('totaldueamt', true);
        $totalinvoiceamount = $this->input->post('totalinvoiceamount', true);
        $paid_amount = $this->input->post('paid_amount', true);
        // erp2024 26-11-2024 new items

        // $this->db->select('holder');
        // $this->db->from('cberp_accounts');
        // $this->db->where('id', $acid);
        // $query = $this->db->get();
        // $account = $query->row_array();

        // #erp2024 01-10-2024 new insertion for cberp_payments table
        $nullupdate = [
            'card_number' => NULL,
            'cvc' => NULL,
            'card_holder' => NULL,
            'card_expiry_date' => NULL,
            'cheque_pay_from' => NULL,
            'cheque_account_number' => NULL,
            'cheque_number' => NULL,
            'cheque_date' => NULL,
            'account_bank_name' => NULL,
            'account_bank_address' => NULL,
            'account_number' => NULL,
            'account_holder_name' => NULL,
            'account_ifsc_code' => NULL
        ];
        $dataai = [];
                    
        $dataai['payer'] = $cname;
        $dataai['payerid'] = $cid;
        $dataai['note'] = $note;
        $dataai['payment_method'] = $pmethod;
        $dataai['record_from']     = 'Purchase';
        $dataai['amount']         = $amount;
        $dataai['transfered_account_id'] = $acid;
        $dataai['trans_type'] = 'Purchase';
        $postedchequeflg = 0;
        $status = 'post dated cheque';
        if($pmethod=="Cheque")
        {
            $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            $dataai['cheque_number'] = $this->input->post('cheque_number', true);
            $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
            $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;     
                    
        }
        else if($pmethod=="Card")
        {
            $dataai['card_number'] = $this->input->post('card_number', true);
            $dataai['cvc'] = $this->input->post('cvc', true);
            $dataai['card_holder'] = $this->input->post('card_holder', true);
            $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
        }
        else if($pmethod=="Bank")
        {
            $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
            $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
            $dataai['account_number'] = $this->input->post('account_number', true);
            $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
            $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
        }
        else{}
        // #erp2024 01-10-2024 new insertion for cberp_payments table
        $this->db->select('order_total,customer_id,paid_amount');
        $this->db->from('cberp_purchase_orders');
        $this->db->where('id', $tid);
        $query = $this->db->get();
        $purchaseresult = $query->row();
        $purchase_totalrm = $purchaseresult->order_total - $purchaseresult->paid_amount; 

        $this->db->select('bill_amount,purchase_paid_amount');
        $this->db->from('cberp_purchase_receipts');        
        $this->db->where('purchase_reciept_number', $receipt_number);
        $query = $this->db->get();
        $invresult = $query->row();
        
        $totalrm = $invresult->bill_amount - $invresult->purchase_paid_amount; 
        $received_amount = abs($amount - $totalrm);
       

        if($amount)
        {

            $this->db->set('payment_method', $pmethod);
            $this->db->set('paid_amount', "GREATEST(paid_amount-$old_amount)", FALSE);
            $this->db->set('payment_status', 'partial');
            $this->db->where('purchase_number', $purchase_number);
            $this->db->update('cberp_purchase_orders');
            
            $this->db->set('payment_status', 'Partial');
            $this->db->set('purchase_paid_date', date('Y-m-d'));
            // $this->db->set('purchase_paid_amount', "purchase_paid_amount-$old_amount", FALSE);
            $this->db->set('purchase_paid_amount', "GREATEST(purchase_paid_amount - $old_amount, 0)", FALSE);

            $this->db->where('id', $receipt_id);
            $this->db->update('cberp_purchase_receipts');
            // die();
            $totalpaid = ($amount - $old_amount) + $paid_amount;
            if ($totalpaid == $totalinvoiceamount) {
                $status = 'paid';
            } elseif (($totalinvoiceamount == $paid_amount) && ($amount == $old_amount)) {
                $status = 'paid';
            } else {
                $status = 'partial';
            }
          
            $banktranslink_data = [                            
                'trans_type' => 'Purchase',
                'trans_type_number' => $receipt_number,
                'transaction_number'=>$transaction_number,
                'bank_transaction_number'=>$bank_tansaction_number,
                'created_dt' => date('Y-m-d H:i:s'),
                'created_by'=> $this->session->userdata('id')
            ];
            $this->db->update('cberp_payment_transaction_link', $banktranslink_data,['trans_type_number' => $receipt_number,'transaction_number'=>$transaction_number,'bank_transaction_number'=>$bank_tansaction_number]);
            //reset 
            $this->purchase->reset_credit_accounts($transaction_number);
            $this->purchase->reset_debit_accounts($transaction_number);        
            $this->db->delete('cberp_transactions',['transaction_number'=>$transaction_number]);
            $prev_payable_data = [
                'acid' => $coa_account_id,
                'type' => 'Liability',
                'cat' => 'Purchase',
                'debit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->insert('cberp_transactions',$prev_payable_data);
            $this->db->set('lastbal', 'lastbal + ' .$amount, FALSE);
            $this->db->where('acn', $coa_account_id);
            $this->db->update('cberp_accounts'); 


            $bank_data = [
                'acid' => $bank_account_id,
                'type' => 'Asset',
                'cat' => 'Purchase',
                'credit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
                'invoice_number'=>$invoice_number
            ];
            $this->db->insert('cberp_transactions',$bank_data);
            $this->db->set('lastbal', 'lastbal - ' .$amount, FALSE);
            $this->db->where('acn', $bank_account_id);
            $this->db->update('cberp_accounts');

            
            $banktrans_data = [
                'trans_type' => 'Expense',
                'trans_amount' => $amount,
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_number'=>$bank_tansaction_number,
                'trans_supplier_id'=> $cid,
                'trans_payment_method'=> $pmethod,
                'trans_account_id'=>$bank_account_id,
                'trans_chart_of_account_id'=>$coa_account_id,
                'transfered_by' => $this->session->userdata('id')
            ];
            $this->db->update('cberp_bank_transactions',$banktrans_data,['trans_number'=>$bank_tansaction_number]);


            $dataai['trans_num'] = $transaction_number;
            $this->db->update('cberp_payments', $nullupdate,['id'=>$trans_ai_id]);
            $this->db->update('cberp_payments', $dataai,['id'=>$trans_ai_id]);
                
            $this->db->set('payment_status', ucfirst($status));
            $this->db->set('purchase_paid_date', date('Y-m-d'));
            $this->db->set('purchase_paid_amount', "purchase_paid_amount+$amount", FALSE);
            // $this->db->set('payment_recieved_amount', "payment_recieved_amount+$amount", FALSE);
            $this->db->where('id', $receipt_id);
            $this->db->update('cberp_purchase_receipts');
            // die($this->db->last_query());

            $this->db->set('payment_method', $pmethod);
            $this->db->set('paid_amount', "paid_amount+$amount", FALSE);
            $this->db->set('payment_status', $status);
            $this->db->where('purchase_number', $purchase_number);
            $this->db->update('cberp_purchase_orders');
        }    

        echo json_encode(array('status' => 'Success'));
    }
    //erp2024 add newfunction for paypurchase edit 26-11-2024 ends
    

    // erp2024 payment transaction for expense claims 28-11-2024 starts
    public function payexpenseclaim_edit()
    {
       
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $received_amount = 0;
        $tid = $this->input->post('tid', true);
        $claim_number = $this->input->post('claim_number', true);
        $receipt_id = $this->input->post('receipt_id', true); 
        $amount = $this->input->post('amount', true);
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $acid = $this->input->post('account_type_id', true);        
        $coa_account_id = $acid;
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = datefordatabase($paydate);
   
        $this->db->select('holder');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $acid);
        $query = $this->db->get();
        $account = $query->row_array();
   

        $transaction_number = $this->input->post('transaction_number', true);
        $bank_tansaction_number = $this->input->post('bank_transaction_number', true);
        $trans_ai_id = $this->input->post('trans_ai_id', true);
        $old_amount = $this->input->post('old_amount', true);
        $totaldueamt = $this->input->post('totaldueamt', true);
        $totalinvoiceamount = $this->input->post('totalinvoiceamount', true);
        $paid_amount = $this->input->post('paid_amount', true);
        // #erp2024 01-10-2024 new insertion for cberp_payments table
        $nullupdate = [
            'card_number' => NULL,
            'cvc' => NULL,
            'card_holder' => NULL,
            'card_expiry_date' => NULL,
            'cheque_pay_from' => NULL,
            'cheque_account_number' => NULL,
            'cheque_number' => NULL,
            'cheque_date' => NULL,
            'account_bank_name' => NULL,
            'account_bank_address' => NULL,
            'account_number' => NULL,
            'account_holder_name' => NULL,
            'account_ifsc_code' => NULL
        ];
        $dataai = [];
                    
        $dataai['payer'] = $cname;
        $dataai['payerid'] = $cid;
        $dataai['note'] = $note;
        $dataai['payment_method'] = $pmethod;
        $dataai['record_from']     = 'Expense Claim';
        $dataai['amount']         = $amount;
        $dataai['transfered_account_id'] = $acid;
        // $dataai['transfered_account_name'] = $account['holder'];
        $dataai['trans_type'] = 'Expense Claim';
        $postedchequeflg = 0;
        $status = 'post dated cheque';
        if($pmethod=="Cheque")
        {
            $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            $dataai['cheque_number'] = $this->input->post('cheque_number', true);
            $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
            $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;     
                   
        }
        else if($pmethod=="Card")
        {
            $dataai['card_number'] = $this->input->post('card_number', true);
            $dataai['cvc'] = $this->input->post('cvc', true);
            $dataai['card_holder'] = $this->input->post('card_holder', true);
            $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
        }
        else if($pmethod=="Bank")
        {
            $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
            $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
            $dataai['account_number'] = $this->input->post('account_number', true);
            $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
            $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
        }
        else{}
        // #erp2024 01-10-2024 new insertion for cberp_payments table
   
        $this->db->select('claim_total as bill_amount, payment_recieved_amount');
        $this->db->from('cberp_expense_claims'); 
        $this->db->where('claim_number', $claim_number);
        $query = $this->db->get();
        $invresult = $query->row(); 
        
       
        $totalrm = $invresult->bill_amount - $invresult->payment_recieved_amount; 
        $received_amount = abs($amount - $totalrm);
   
        if($amount)
        {
   
            $this->db->set('payment_status', 'Partial');
            $this->db->set('payment_recieved_date', date('Y-m-d'));
            // $this->db->set('payment_recieved_amount', "payment_recieved_amount-$old_amount", FALSE);
            $this->db->set('payment_recieved_amount', "GREATEST(payment_recieved_amount-$old_amount)", FALSE);
            $this->db->where('id', $receipt_id);
            $this->db->update('cberp_expense_claims');

            $totalpaid = ($amount - $old_amount) + $paid_amount;
            if ($totalpaid == $totalinvoiceamount) {
                $status = 'Paid';
            } elseif (($totalinvoiceamount == $paid_amount) && ($amount == $old_amount)) {
                $status = 'Paid';
            } else {
                $status = 'Partial';
            }

            $banktranslink_data = [                            
                'trans_type' => 'Expense Claim',
                'trans_type_number' => $receipt_number,
                'transaction_number'=>$transaction_number,
                'bank_transaction_number'=>$bank_tansaction_number,
                'created_dt' => date('Y-m-d H:i:s'),
                'created_by'=> $this->session->userdata('id')
            ];
            $this->db->update('cberp_payment_transaction_link', $banktranslink_data,['trans_type_number' => $receipt_number,'transaction_number'=>$transaction_number,'bank_transaction_number'=>$bank_tansaction_number]);

            $this->purchase->reset_credit_accounts($transaction_number);
            $this->purchase->reset_debit_accounts($transaction_number);        
            $this->db->delete('cberp_transactions',['transaction_number'=>$transaction_number]);

            $prev_payable_data = [
                'acid' => $coa_account_id,
                'type' => 'Liability',
                'cat' => 'Purchase',
                'debit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->insert('cberp_transactions',$prev_payable_data);
            $this->db->set('lastbal', 'lastbal + ' .$amount, FALSE);
            $this->db->where('acn', $coa_account_id);
            $this->db->update('cberp_accounts'); 
   
   
            $bank_data = [
                'acid' => $bank_account_id,
                'type' => 'Asset',
                'cat' => 'Purchase',
                'credit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
                'invoice_number'=>$invoice_number
            ];
            $this->db->insert('cberp_transactions',$bank_data);
            $this->db->set('lastbal', 'lastbal - ' .$amount, FALSE);
            $this->db->where('acn', $bank_account_id);
            $this->db->update('cberp_accounts');
   
            
            $banktrans_data = [
                'trans_type' => 'Expense',
                'trans_amount' => $amount,
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_number'=>$bank_tansaction_number,
                'trans_supplier_id'=> $cid,
                'trans_payment_method'=> $pmethod,
                'trans_account_id'=>$bank_account_id,
                'trans_chart_of_account_id'=>$coa_account_id,
                'transfered_by' => $this->session->userdata('id')
                
            ];
            $this->db->update('cberp_bank_transactions',$banktrans_data,['trans_number'=>$bank_tansaction_number]);
   
   
            $dataai['trans_num'] = $transaction_number;
            $this->db->update('cberp_payments', $nullupdate,['id'=>$trans_ai_id]);
            $this->db->update('cberp_payments', $dataai,['id'=>$trans_ai_id]);

            $this->db->set('payment_status', $status);
            $this->db->set('payment_recieved_date', date('Y-m-d'));
            $this->db->set('payment_recieved_amount', "payment_recieved_amount+$amount", FALSE);
            $this->db->where('id', $receipt_id);
            $this->db->update('cberp_expense_claims');

            
        }   
        echo json_encode(array('status' => 'Success'));
    }
    public function payexpenseclaim()
    {
       
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $received_amount = 0;
        $transaction_number = get_latest_trans_number();
        $tid = $this->input->post('tid', true);
        $claim_number = $this->input->post('claim_number', true);
        $receipt_id = $this->input->post('receipt_id', true); 
        $amount = $this->input->post('amount', true);
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $acid = $this->input->post('account_type_id', true);        
        $coa_account_id = $acid;
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = datefordatabase($paydate);
   
        $this->db->select('holder');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $acid);
        $query = $this->db->get();
        $account = $query->row_array();
   
        // #erp2024 01-10-2024 new insertion for cberp_payments table
        $dataai = [];
                    
        $dataai['payer'] = $cname;
        $dataai['payerid'] = $cid;
        $dataai['note'] = $note;
        $dataai['payment_method'] = $pmethod;
        $dataai['record_from']     = 'Expense Claim';
        $dataai['amount']         = $amount;
        $dataai['transfered_account_id'] = $acid;
        // $dataai['transfered_account_name'] = $account['holder'];
        $dataai['trans_type'] = 'Expense Claim';
        $postedchequeflg = 0;
        $status = 'post dated cheque';
        if($pmethod=="Cheque")
        {
            $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            $dataai['cheque_number'] = $this->input->post('cheque_number', true);
            $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
            $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;     
                   
        }
        else if($pmethod=="Card")
        {
            $dataai['card_number'] = $this->input->post('card_number', true);
            $dataai['cvc'] = $this->input->post('cvc', true);
            $dataai['card_holder'] = $this->input->post('card_holder', true);
            $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
        }
        else if($pmethod=="Bank")
        {
            $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
            $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
            $dataai['account_number'] = $this->input->post('account_number', true);
            $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
            $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
        }
        else{}
        // #erp2024 01-10-2024 new insertion for cberp_payments table
   
        $this->db->select('claim_total as bill_amount, payment_recieved_amount');
        $this->db->from('cberp_expense_claims'); 
        $this->db->where('claim_number', $claim_number);
        $query = $this->db->get();
        $invresult = $query->row(); 
        
       
        $totalrm = $invresult->bill_amount - $invresult->payment_recieved_amount; 
        $received_amount = abs($amount - $totalrm);
   
        if($totalrm)
        {
   
            $bank_tansaction_number = get_transnumber();
            $banktranslink_data = [                            
                'trans_type' => 'Expense Claim',
                'trans_type_number' => $claim_number,
                'transaction_number'=>$transaction_number,
                'bank_transaction_number'=>$bank_tansaction_number,
                'created_dt' => date('Y-m-d H:i:s'),
                'created_by'=> $this->session->userdata('id')
            ];
            $this->db->insert('cberp_payment_transaction_link', $banktranslink_data);
   
            $prev_payable_data = [
                'acid' => $coa_account_id,
                'type' => 'Liability',
                'cat' => 'Purchase',
                'debit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->insert('cberp_transactions',$prev_payable_data);
            $this->db->set('lastbal', 'lastbal + ' .$amount, FALSE);
            $this->db->where('acn', $coa_account_id);
            $this->db->update('cberp_accounts'); 
   
   
            $bank_data = [
                'acid' => $bank_account_id,
                'type' => 'Asset',
                'cat' => 'Purchase',
                'credit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->insert('cberp_transactions',$bank_data);
            $this->db->set('lastbal', 'lastbal - ' .$amount, FALSE);
            $this->db->where('acn', $bank_account_id);
            $this->db->update('cberp_accounts');
   
   
            $banktrans_data = [
                'trans_type' => 'Expense',
                'trans_amount' => $amount,
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_number'=>$bank_tansaction_number,
                'trans_supplier_id'=> $cid,
                'trans_payment_method'=> $pmethod,
                'trans_account_id'=>$bank_account_id,
                'trans_chart_of_account_id'=>$coa_account_id,
                'from_trans_number'=>$transaction_number,            
                'trans_ref_number'=>get_banktrans_reference_number(),
                'transfered_by' => $this->session->userdata('id')
            ];
            $this->db->insert('cberp_bank_transactions',$banktrans_data);
   
   
            $dataai['trans_num'] = $transaction_number;
            $this->db->insert('cberp_payments', $dataai);
            
            if ($totalrm > $amount) {            
                // $this->db->set('payment_transaction_number', $transaction_number);
                $this->db->set('payment_status', 'Partial');
                $this->db->set('payment_recieved_date', date('Y-m-d'));
                $this->db->set('payment_recieved_amount', "payment_recieved_amount+$amount", FALSE);
                $this->db->where('id', $receipt_id);
                $this->db->update('cberp_expense_claims');
            } 
            else {            
                // $this->db->set('payment_transaction_number', $transaction_number);
                $this->db->set('payment_status', 'Paid');
                $this->db->set('payment_recieved_date', date('Y-m-d'));
                $this->db->set('payment_recieved_amount', "payment_recieved_amount+$amount", FALSE);
                $this->db->where('id', $receipt_id);
                $this->db->update('cberp_expense_claims');
            }

            
            
        }
   
        echo json_encode(array('status' => 'Success'));
        // echo json_encode(array('status' => 'Success', 'message' =>
        //     $this->lang->line('Transaction has been added'), 'pstatus' => $this->lang->line($status), 'activity' => $activitym, 'amt' => $totalrm, 'ttlpaid' => $paid_amount));
    }
    // erp2024 payment transaction for expense claims 28-11-2024 ends

    //erp2024 11-12-2024
    
    public function invoice_return_payment(){

        // if (!$this->aauth->premission(1)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // } $note
        //  ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $remaining_amount = 0;
        $invoice_retutn_number = $this->input->post('invoice_retutn_number');  
        $shortnote = $this->input->post('shortnote');   
        $return_number = $this->input->post('return_number');   
        $receipt_number = $this->transactions->last_invoice_return_receipt_number(); 
        

        $transaction_number =  get_latest_trans_number(); 
        // $transaction_number = $this->input->post('transaction_number'); 
        $bank_tansaction_number = get_transnumber();
        $received_amount = rev_amountExchange_s($this->input->post('amount', true), 0, $this->aauth->get_user()->loc);          
        $payment_recieved = $received_amount;
        $pmethod = $this->input->post('pmethod', true);
        $coa_account_id = $this->input->post('account_type_id', true);
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $customer_id = $this->input->post('customer_id', true);
        $cname = $this->input->post('cname', true);
        $paydate = $this->input->post('paydate', true);
        

        $paydate = datefordatabase($paydate);
        $invoice_number = $this->input->post('inv_id');   
        $transok = true;
        $this->load->library("Common");
        $this->db->trans_start();
        $dataai = [];
                    
        $dataai['payer'] = $cname;
        $dataai['payerid'] = $cid;
        $dataai['note'] = $shortnote;
        $dataai['payment_method'] = $pmethod;
        $dataai['invoice_number']     = $invoice_number;
        $dataai['amount']         = $received_amount;
        $dataai['transfered_account_id'] = $coa_account_id;
        $dataai['record_from'] = 'Invoice Return';
        $dataai['note'] = $shortnote;
        $postedchequeflg = 0;
        $status = 'post dated cheque';        
        $data_payments = [
            'receipt_number' => $receipt_number,
            'transaction_number' => $transaction_number,
            'payment_amount' => $received_amount,
            'payment_method' => $pmethod,
            'chart_of_account_1' => $bank_account_id,                        
            'chart_of_account_2' => $coa_account_id,                        
            'note' => $shortnote,                        
            'created_by' => $this->session->userdata('id'),
            'created_date' => date('Y-m-d H:i:s')
        ];
        if($pmethod=="Cheque")
        {
            $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            $dataai['cheque_number'] = $this->input->post('cheque_number', true);
            $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
            $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;    
            $data_payments['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            $data_payments['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            $data_payments['cheque_number'] = $this->input->post('cheque_number', true);
            $data_payments['cheque_date'] = datefordatabase($this->input->post('cheque_date', true)); 
                
        }
        else if($pmethod=="Card")
        {
            $dataai['card_number'] = $this->input->post('card_number', true);
            $dataai['cvc'] = $this->input->post('cvc', true);
            $dataai['card_holder'] = $this->input->post('card_holder', true);
            $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
        }
        else if($pmethod=="Bank")
        {
            $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
            $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
            $dataai['account_number'] = $this->input->post('account_number', true);
            $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
            $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);

            $data_payments['account_bank_name'] = $this->input->post('account_bank_name', true);
            $data_payments['account_bank_address'] = $this->input->post('account_bank_address', true);
            $data_payments['account_number'] = $this->input->post('account_number', true);
            $data_payments['account_holder_name'] = $this->input->post('account_holder_name', true);
            $data_payments['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
        }
        else{}
         $this->db->insert('cberp_invoice_return_payments', $data_payments);
          $data_payment_details = [
            'receipt_number' => $receipt_number,
            'paid_amount' => $received_amount,
            'invoice_reurn_number' => $invoice_retutn_number
        ];
        $this->db->insert('cberp_invoice_return_payments_details', $data_payment_details);
        $data = array(
            'acid' => $coa_account_id,
            'type' => 'Asset',
            'cat' => 'Sales',
            'debit' => $received_amount,
            'payer' => $cname,
            'payerid' => $cid,
            'method' => $pmethod,
            'date' => $paydate,
            'eid' => $this->aauth->get_user()->id,
            // 'tid' => $invoice_retutn_number,
            'loc' => $this->aauth->get_user()->loc,
        );
        // $dataai['transaction_number'] = $transaction_number;
        // $this->db->insert('cberp_payments', $dataai);

        
        $this->db->update('cberp_stock_returns',['payment_status'=>'Paid','payment_recieved_amount'=>$received_amount,'payment_recieved_date'=>date('Y-m-d H:i:s')],['invoice_retutn_number'=>$invoice_retutn_number]);
        
        insert_return_transaction('debit', 'Invoice Return', $received_amount, $coa_account_id, $transaction_number);
        update_account_balance($coa_account_id, $received_amount, 'add');

        insert_return_transaction('credit', 'Invoice Return', $received_amount, $bank_account_id, $transaction_number,$customer_id);
        update_account_balance($bank_account_id, $received_amount, 'subtract');

        insert_return_payment_transaction_link('Invoice Return',$return_number, $transaction_number, $bank_tansaction_number);
        // die($this->db->last_query());
        insert_bank_transaction('Invoice Return', $received_amount, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_tansaction_number);
        
        // history_table_with_foreginkey_log('cberp_invoice_payment_return_log','payment_id',$this->db->insert_id(),'invoice_retutn_number',$invoice_retutn_number,'Create');
        // erp2025 09-01-2025 starts
         detailed_log_history('Invoice',$invoice_number,'Payment Returned', $_POST['changedFields']);	
         detailed_log_history('Invoicereturn',$invoice_retutn_number,'Payment Returned', $_POST['changedFields']);	
        // erp2025 09-01-2025 ends

        // $logdata = array(
        //     'invoiceid' => $returnid,
        //     'transaction_id' => $transaction_number,
        //     'transactionai_id' => $this->db->insert_id(),
        //     'created_by' => $this->session->userdata('id'),
        //     'created_date' => date('Y-m-d'),
        //     'created_time' => date('H:i:s'),
        //     'ip_address'=> $this->getClientIpAddress(),
        //     'payment_status'=> $status,
        // );
        // $this->db->insert('invoice_payment_log_ai', $logdata);
        // ========================================================================
        $response = array('status' => 'Success', 'message' => $this->lang->line('Transaction has been added'));
        echo json_encode($response);
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
        
    }
    public function invoice_return_payment_edit(){

        // if (!$this->aauth->premission(1)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $remaining_amount = 0;
        $tid = $this->input->post('tid');   
        $returnid = $this->input->post('tid');   
        $notes = $this->input->post('notes');   
        $return_number = $this->input->post('return_number');   
        $receipt_number = $this->transactions->last_invoice_return_receipt_number(); 
        
        $transaction_number = $this->input->post('transcation_number');
        $bank_transaction_number = $this->input->post('banktransaction_number');
        $reference_number = $this->input->post('reference_number');
        $customer_id = $this->input->post('customer_id');
        $old_received_amount = rev_amountExchange_s($this->input->post('old_received_amount', true), 0, $this->aauth->get_user()->loc);

        $received_amount = rev_amountExchange_s($this->input->post('amount', true), 0, $this->aauth->get_user()->loc);          
        $payment_recieved = $received_amount;
        $pmethod = $this->input->post('pmethod', true);
        $coa_account_id = $this->input->post('account_type_id', true);
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $paydate = $this->input->post('paydate', true);
        $paydate = datefordatabase($paydate);

        
        $dataai = [];
                    
        $dataai['payer'] = $cname;
        $dataai['payerid'] = $cid;
        $dataai['note'] = $note;
        $dataai['payment_method'] = $pmethod;
        $dataai['invoice_id']     = $row['id'];
        $dataai['amount']         = $received_amount;
        $dataai['transfered_account_id'] = $coa_account_id;
        $postedchequeflg = 0;
        $status = 'post dated cheque';
        if($pmethod=="Cheque")
        {
            $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            $dataai['cheque_number'] = $this->input->post('cheque_number', true);
            $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
            $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;   

            // $data_payments['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            // $data_payments['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            // $data_payments['cheque_number'] = $this->input->post('cheque_number', true);
            // $data_payments['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
                
        }
        else if($pmethod=="Card")
        {
            $dataai['card_number'] = $this->input->post('card_number', true);
            $dataai['cvc'] = $this->input->post('cvc', true);
            $dataai['card_holder'] = $this->input->post('card_holder', true);
            $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
        }
        else if($pmethod=="Bank")
        {
            $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
            $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
            $dataai['account_number'] = $this->input->post('account_number', true);
            $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
            $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);

            // $data_payments['account_bank_name'] = $this->input->post('account_bank_name', true);
            // $data_payments['account_bank_address'] = $this->input->post('account_bank_address', true);
            // $data_payments['account_number'] = $this->input->post('account_number', true);
            // $data_payments['account_holder_name'] = $this->input->post('account_holder_name', true);
            // $data_payments['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
        }
        else{}
        
        $data = array(
            'acid' => $coa_account_id,
            'type' => 'Asset',
            'cat' => 'Sales',
            'debit' => $row['total'],
            'payer' => $cname,
            'payerid' => $cid,
            'method' => $pmethod,
            'date' => $paydate,
            'eid' => $this->aauth->get_user()->id,
            'tid' => $row['id'],
            'note' => $note,
            'loc' => $this->aauth->get_user()->loc
        );
       

        $updated_amount = $received_amount-$old_received_amount;

        $coa_account_id_old = $this->input->post('account_type_id_old', true);
        $bank_account_id_old = $this->input->post('bank_account_old', true);

        $this->db->delete('cberp_transactions', ['transaction_number'=>$transaction_number]);
        $this->db->update('cberp_payments', $dataai,['transaction_number'=>$transaction_number]);

        $this->db->update('cberp_stock_returns',['payment_status'=>'Paid',],['id'=>$returnid]);
        insert_return_transaction('debit', 'Invoice Return', $received_amount, $coa_account_id, $transaction_number);
        history_table_with_foreginkey_log('cberp_invoice_payment_return_log','payment_id',$this->db->insert_id(),'invoice_return_id',$returnid,'Create');
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Invoicereturn',$returnid,'Payment Updated', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
        update_account_balance($coa_account_id_old, $old_received_amount, 'subtract');
        update_account_balance($coa_account_id, $received_amount, 'add');

        insert_return_transaction('credit', 'Invoice Return', $received_amount, $bank_account_id, $transaction_number,$customer_id);
        update_account_balance($bank_account_id_old, $old_received_amount, 'add');
        update_account_balance($bank_account_id, $received_amount, 'subtract');
        
        update_bank_transaction('Invoice Return', $received_amount, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_transaction_number,$reference_number);
   
       
        // $creditlimits = get_customer_credit_limit($customer_id);
        // update_customer_credit($customer_id, $updated_amount,$creditlimits['avalable_credit_limit']);


        // $logdata = array(
        //     'invoiceid' => $returnid,
        //     'transaction_id' => $transaction_number,
        //     'transactionai_id' => $this->db->insert_id(),
        //     'created_by' => $this->session->userdata('id'),
        //     'created_date' => date('Y-m-d'),
        //     'created_time' => date('H:i:s'),
        //     'ip_address'=> $this->getClientIpAddress(),
        //     'payment_status'=> $status,
        // );
        // $this->db->insert('invoice_payment_log_ai', $logdata);
        // ========================================================================
        $response = array('status' => 'Success', 'message' => $this->lang->line('Transaction has been added'));
        echo json_encode($response);
        
    }

    //purchase return payment erp2024 05-02-2025
   public function purchasereturn_receivepayment()
   {

        // cberp_payment_transaction_link
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        // $existing_transaction_number = get_payment_trans_number($tid);
        // $transaction_number = ($existing_transaction_number) ? $existing_transaction_number :get_latest_trans_number();
        $received_amount = 0;
        $transaction_number = get_latest_trans_number();
        $tid = $this->input->post('tid', true);
        $receipt_return_number = $this->input->post('receipt_return_number', true);
        $purchase_return_number = $this->input->post('receipt_number', true);
        $purchase_return_id = $this->input->post('purchase_return_id', true);
        $purchase_id = $this->input->post('purchase_id', true);    
        $amount = $this->input->post('amount', true);
        $paydate = $this->input->post('paydate', true);
        $note = $this->input->post('shortnote', true);
        $pmethod = $this->input->post('pmethod', true);
        $acid = $this->input->post('account_type_id', true);        
        $coa_account_id = $acid;
        $bank_account_id = $this->input->post('bank_account', true);
        $cid = $this->input->post('cid', true);
        $cname = $this->input->post('cname', true);
        $module_number = $this->input->post('module_number', true);
        $paydate = datefordatabase($paydate);

        $this->db->select('holder');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $acid);
        $query = $this->db->get();
        $account = $query->row_array();

        // #erp2024 01-10-2024 new insertion for cberp_payments table
        $dataai = [];
                    
        $dataai['payer'] = $cname;
        $dataai['payerid'] = $cid;
        $dataai['note'] = $note;
        $dataai['payment_method'] = $pmethod;
        $dataai['record_from']     = 'Purchase Return';
        $dataai['amount']         = $amount;
        $dataai['transfered_account_id'] = $acid;
        // $dataai['transfered_account_name'] = $account['holder'];
        $dataai['trans_type'] = 'Purchase';
        $postedchequeflg = 0;
        $status = 'post dated cheque';
        if($pmethod=="Cheque")
        {
            $dataai['cheque_pay_from'] = $this->input->post('cheque_pay_from', true);
            $dataai['cheque_account_number'] = $this->input->post('cheque_account_number', true);
            $dataai['cheque_number'] = $this->input->post('cheque_number', true);
            $dataai['cheque_date'] = datefordatabase($this->input->post('cheque_date', true));
            $postedchequeflg = (strtotime(date('Y-m-d')) < strtotime($dataai['cheque_date'])) ? 1 : 0;     
                    
        }
        else if($pmethod=="Card")
        {
            $dataai['card_number'] = $this->input->post('card_number', true);
            $dataai['cvc'] = $this->input->post('cvc', true);
            $dataai['card_holder'] = $this->input->post('card_holder', true);
            $dataai['card_expiry_date'] = datefordatabase($this->input->post('card_expiry_date', true));
        }
        else if($pmethod=="Bank")
        {
            $dataai['account_bank_name'] = $this->input->post('account_bank_name', true);
            $dataai['account_bank_address'] = $this->input->post('account_bank_address', true);
            $dataai['account_number'] = $this->input->post('account_number', true);
            $dataai['account_holder_name'] = $this->input->post('account_holder_name', true);
            $dataai['account_ifsc_code'] = $this->input->post('account_ifsc_code', true);
        }
        else{}
        // #erp2024 01-10-2024 new insertion for cberp_payments table
        $this->db->select('total,supplier_id,payment_recieved_amount');
        $this->db->from('cberp_purchase_reciept_returns');
        $this->db->where('receipt_return_number', $receipt_return_number);
        $query = $this->db->get();
        $purchaseresult = $query->row();
        $totalrm = $purchaseresult->total - $purchaseresult->payment_recieved_amount; 
        $received_amount = abs($amount - $totalrm);

        if($totalrm)
        {

            $bank_tansaction_number = get_transnumber();
            $banktranslink_data = [                            
                'trans_type' => 'Purchase Return',
                'trans_type_number' => $receipt_return_number,
                'transaction_number'=>$transaction_number,
                'bank_transaction_number'=>$bank_tansaction_number,
                'created_dt' => date('Y-m-d H:i:s'),
                'created_by'=> $this->session->userdata('id')
            ];
            $this->db->insert('cberp_payment_transaction_link', $banktranslink_data);

            //  die($this->db->last_query());
            $prev_payable_data = [
                'acid' => $coa_account_id,
                'type' => 'Asset',
                'cat' => 'Purchase Return',
                'credit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->insert('cberp_transactions',$prev_payable_data);
            
            $this->db->set('lastbal', 'lastbal - ' .$amount, FALSE);
            $this->db->where('acn', $coa_account_id);
            $this->db->update('cberp_accounts'); 


            $bank_data = [
                'acid' => $bank_account_id,
                'type' => 'Asset',
                'cat' => 'Purchase Return',
                'debit' => $amount,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->insert('cberp_transactions',$bank_data);
            $this->db->set('lastbal', 'lastbal + ' .$amount, FALSE);
            $this->db->where('acn', $bank_account_id);
            $this->db->update('cberp_accounts');


            $banktrans_data = [
                'trans_type' => 'Asset',
                'trans_amount' => $amount,
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_number'=>$bank_tansaction_number,
                'trans_supplier_id'=> $cid,
                'trans_payment_method'=> $pmethod,
                'trans_account_id'=>$bank_account_id,
                'trans_chart_of_account_id'=>$coa_account_id,
                'from_trans_number'=>$transaction_number,            
                'trans_ref_number'=>get_banktrans_reference_number(),
                'transfered_by' => $this->session->userdata('id')
            ];
            $this->db->insert('cberp_bank_transactions',$banktrans_data);


            $dataai['trans_num'] = $transaction_number;
            $this->db->insert('cberp_payments', $dataai);
            
            if ($totalrm > $amount) {            
                // $this->db->set('payment_transaction_number', $transaction_number);
                $this->db->set('payment_status', 'Partial');
                $this->db->set('payment_recieved_date', date('Y-m-d H:i:s'));
                $this->db->set('payment_recieved_amount', "payment_recieved_amount+$amount", FALSE);
                $this->db->where('receipt_return_number', $receipt_return_number);
                $this->db->update('cberp_purchase_reciept_returns');
                //  die($this->db->last_query());
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history($module_number,$receipt_return_number,'Payment Update', $_POST['changedFields']);
                //erp2024 06-01-2025 detailed history log ends 
            } 
            else {            
                // $this->db->set('payment_transaction_number', $transaction_number);
                $this->db->set('payment_status', 'Paid');
                $this->db->set('payment_recieved_date', date('Y-m-d H:i:s'));
                $this->db->set('payment_recieved_amount', "payment_recieved_amount+$amount", FALSE);
                $this->db->where('receipt_return_number', $receipt_return_number);
                $this->db->update('cberp_purchase_reciept_returns');
                //  die($this->db->last_query());
                //erp2024 06-01-2025 detailed history log starts
                detailed_log_history($module_number,$receipt_return_number,'Payment Update', $_POST['changedFields']);
                //erp2024 06-01-2025 detailed history log ends 
            }
                
            //  $activitym = "<tr><td>" . substr($paydate, 0, 10) . "</td><td>$pmethod</td><td>$amount</td><td>$note</td></tr>";
     }
     
     echo json_encode(array('status' => 'Success'));
     // echo json_encode(array('status' => 'Success', 'message' =>
     //     $this->lang->line('Transaction has been added'), 'pstatus' => $this->lang->line($status), 'activity' => $activitym, 'amt' => $totalrm, 'ttlpaid' => $paid_amount));
   }
}
