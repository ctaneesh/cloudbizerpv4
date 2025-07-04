<?php
/**
 * Cloud Biz Erp  Accounting,  Invoicing  and CRM Software
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
require_once APPPATH . 'third_party/vendor/autoload.php';


use Omnipay\Omnipay;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

class Billing extends CI_Controller
{
    private $configurations;
    public function __construct()
    {
        parent::__construct();
        $this->config->set_item('csrf_protection', FALSE);
        $this->load->model('invoices_model', 'invocies');
        $this->load->model('billing_model', 'billing');
        $this->load->library("Aauth");
        $this->load->library("Custom");
        $this->load->library('session');
        $this->configurations = $this->session->userdata('configurations');
    }

    public function view()
    {
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);

        if (!$this->input->get()) {
            exit();
        }
        
        $tid = $this->input->get('id');
        $token = $this->input->get('token');
        $data['crm'] = $this->input->get('crm', true) ?: '';

		

        $validtoken = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
		//echo $token;
		//exit();

        if (hash_equals($token, $validtoken)) {

            $this->load->model('accounts_model');


            $data['id'] = $tid;
            $data['token'] = $token;

            $data['invoice'] = $this->invocies->invoice_details($tid, '', false);
            $data['acclist'] = $this->accounts_model->accountslist(false . $data['invoice']['loc']);
            $data['online_pay'] = $this->billing->online_pay_settings();
            $data['products'] = $this->invocies->invoice_products($tid);
            $data['activity'] = $this->invocies->invoice_transactions($tid);
            $data['attach'] = $this->invocies->attach($tid);
            if (CUSTOM) $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['customer_id'], 1, 1);
            $data['gateway'] = $this->billing->gateway_list('Yes');


            $data['employee'] = $this->invocies->employee($data['invoice']['employee_id']);

            $head['usernm'] = '';
            $head['title'] = "Invoice " . $data['invoice']['invoice_number'];
            $this->load->view('billing/header', $head);
            $this->load->view('billing/view', $data);
            $this->load->view('billing/footer');
        }

    }


    public function quoteview()
    {
        if (!$this->input->get()) {
            exit();
        }
        $tid = intval($this->input->get('id'));
        $token = $this->input->get('token');
        $data['crm'] = $this->input->get('crm', true) ?: '';
        $validtoken = hash_hmac('ripemd160', 'q' . $tid, $this->config->item('encryption_key'));
        if (hash_equals($token, $validtoken)) {
            $this->load->model('quote_model', 'quote');
            $this->load->model('accounts_model');
            $data['acclist'] = $this->accounts_model->accountslist();
            $tid = intval($this->input->get('id'));
            $data['id'] = $tid;
            $data['token'] = $token;
            $data['invoice'] = $this->quote->quote_details($tid);
            $data['attach'] = $this->quote->attach($tid);
            $data['products'] = $this->quote->quote_products($tid);
            $data['employee'] = $this->quote->employee($data['invoice']['eid']);
            $head['title'] = "Quote " . $data['invoice']['quote_number'];                    
            $data['configurations'] = $this->configurations;
            $head['usernm'] = '';
            $this->load->view('billing/header', $head);
            $this->load->view('billing/quoteview', $data);
            $this->load->view('billing/footer');
        }

    }

    public function purchase()
    {
        if (!$this->input->get()) {
            exit();
        }
        $tid = intval($this->input->get('id'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', 'p' . $tid, $this->config->item('encryption_key'));
        if (hash_equals($token, $validtoken)) {
            $this->load->model('purchase_model', 'purchase');
            $this->load->model('accounts_model');
            $data['acclist'] = $this->accounts_model->accountslist();
            $data['attach'] = $this->purchase->attach($tid);
            $tid = intval($this->input->get('id'));
            $data['id'] = $tid;
            $data['token'] = $token;
            $data['invoice'] = $this->purchase->purchase_details($tid);
            // $data['online_pay'] = $this->purchase->online_pay_settings();
            $data['products'] = $this->purchase->purchase_products($tid);
            $data['activity'] = $this->purchase->purchase_transactions($tid);
            $head['title'] = "Purchase " . $data['invoice']['tid'];
            $data['employee'] = $this->purchase->employee($data['invoice']['eid']);
            $head['usernm'] = '';
            $this->load->view('billing/header', $head);
            $this->load->view('billing/purchase', $data);
            $this->load->view('billing/footer');
        }
    }

    public function stockreturn()
    {
        if (!$this->input->get()) {
            exit();
        }
        $tid = intval($this->input->get('id'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', 's' . $tid, $this->config->item('encryption_key'));
        // if (hash_equals($token, $validtoken)) {
            $this->load->model('stockreturn_model', 'stockreturn');
            $this->load->model('accounts_model');
            $data['acclist'] = $this->accounts_model->accountslist();
            $data['attach'] = $this->stockreturn->attach($tid);
            $tid = intval($this->input->get('id'));
            $data['id'] = $tid;
            $data['token'] = $token;
            $data['invoice'] = $this->stockreturn->purchase_details($tid);
            // $data['online_pay'] = $this->purchase->online_pay_settings();
            $data['products'] = $this->stockreturn->purchase_products($tid);
            $data['activity'] = $this->stockreturn->purchase_transactions($tid);
            $head['title'] = "Order " . $data['invoice']['tid'];
            $data['employee'] = $this->stockreturn->employee($data['invoice']['eid']);
            $head['usernm'] = '';
            $this->load->view('billing/header', $head);
            $this->load->view('billing/stockreturn', $data);
            $this->load->view('billing/footer');
        // }
    }


    public function gateway()
    {
        if (!$this->input->get()) {
            exit();
        }
        $tid = intval($this->input->post('tid'));
        $token = $this->input->post('token');
        $amount = $this->input->post('p_amount');
        $pay_gateway = $this->input->post('pay_gateway');
        $validtoken = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
        if (hash_equals($token, $validtoken)) {
            switch ($pay_gateway) {
                case 1 :
                    $this->card();
                    break;
            }
        }
    }

    public function printinvoice()
    {
        if (!$this->input->get()) {
            exit();
        }
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 
        $invoice_number = ($this->input->get('id'));
        $receipt_number = ($this->input->get('receipt_number'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', $invoice_number, $this->config->item('encryption_key'));
        $data['receipt_details'] = "";
        
        if (hash_equals($token, $validtoken)) {
            $data['id'] = $invoice_number;
            $data['receipt_number'] = $receipt_number;
            $data['invoice'] = $this->invocies->invoice_details($invoice_number);
            if($receipt_number)
            {  
                 $data['receipt_details'] = $this->invocies->payment_receipt_details($invoice_number,$receipt_number);
            }     
                   
            $data['refdetails'] = $this->invocies->sales_reference_bydelnoteid($data['invoice']['delivery_note_number']);
            
            // if($data['invoice']['status']=="post dated cheque"){
            //     $data['checkdate'] = $this->invocies->payment_method_details($data['invoice']['iid']);
            // }
           
            $data['title'] = "Invoice " . $data['invoice']['invoice_number'];
            $data['products'] = $this->invocies->invoice_products($invoice_number);
            // echo "<pre>"; print_r($data['products']); die();
            $data['employee'] = $this->invocies->employee($data['invoice']['created_by']);
            if (CUSTOM) {
                $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['customer_id'], 1, 1);
                $data['i_custom_fields'] = $this->custom->view_fields_data($invoice_number, 2, 1);
            }


            $data['round_off'] = $this->custom->api_config(4);
            if ($data['invoice']['regular_pos_flag'] == 1) {
                $pref = prefix(7);
            } elseif ($data['invoice']['regular_pos_flag'] > 1) {
                $pref = prefix(3);
            } else {
                $pref = $this->config->item('prefix');
            }
            $data['general'] = array('title' => $this->lang->line('Invoice'), 'person' => $this->lang->line('Customer'), 'prefix' => $pref, 't_type' => 0);
            ini_set('memory_limit', '64M');
            if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
                $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
            } else {
                $html = $this->load->view('print_files/invoice-a4_v' . INVV, $data, true);
                //    $html=str_replace("strong","span",$html);
                //     $html=str_replace("<h","<span",$html);
            }
            
            //PDF Rendering
            $this->load->library('pdf');
            if (INVV == 1) {
                $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
                //  $header=str_replace("<h","<span",$header);
                $pdf = $this->pdf->load_split(array('margin_top' => 40));
                $pdf->SetHTMLHeader($header);
            }
            if (INVV == 2) {
                $pdf = $this->pdf->load_split(array('margin_top' => 5));
            }
            $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');
            $pdf->WriteHTML($html);
            if ($this->input->get('d')) {
                $pdf->Output('Invoice_#' . $data['invoice']['tid'] . '.pdf', 'D');
            } else {
                $pdf->Output('Invoice_#' . $data['invoice']['tid'] . '.pdf', 'I');
            }
        }
    }


    

    public function printinvoice_preview()
    {
        if (!$this->input->get()) {
            exit();
        }
        $tid = intval($this->input->get('id'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
        if (hash_equals($token, $validtoken)) {
            $data['id'] = $tid;
            $data['invoice'] = $this->invocies->invoice_details($tid);
            
            $data['refdetails'] = $this->invocies->sales_reference_bydelnoteid($data['invoice']['delevery_note_id']);
            // $invoice['status']
            // echo "<pre>"; print_r($data['invoice']); die();
            if($data['invoice']['status']=="post dated cheque"){
                $data['checkdate'] = $this->invocies->payment_method_details($data['invoice']['iid']);
            }
            // echo "<pre>"; print_r($data['invoice']['iid']); die();
            $data['title'] = "Invoice " . $data['invoice']['tid'];
            $data['products'] = $this->invocies->invoice_products($tid);
            $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
            if (CUSTOM) {
                $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['cid'], 1, 1);
                $data['i_custom_fields'] = $this->custom->view_fields_data($tid, 2, 1);
            }


            $data['round_off'] = $this->custom->api_config(4);
            if ($data['invoice']['i_class'] == 1) {
                $pref = prefix(7);
            } elseif ($data['invoice']['i_class'] > 1) {
                $pref = prefix(3);
            } else {
                $pref = $this->config->item('prefix');
            }
            $data['general'] = array('title' => $this->lang->line('Invoice'), 'person' => $this->lang->line('Customer'), 'prefix' => $pref, 't_type' => 0);
            ini_set('memory_limit', '64M');
            if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
                $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
            } else {
                $html = $this->load->view('print_files/invoice-preview-a4_v' . INVV, $data, true);
            }
            
            //PDF Rendering
            $this->load->library('pdf');
            if (INVV == 1) {
                $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
                //  $header=str_replace("<h","<span",$header);
                $pdf = $this->pdf->load_split(array('margin_top' => 40));
                $pdf->SetHTMLHeader($header);
            }
            if (INVV == 2) {
                $pdf = $this->pdf->load_split(array('margin_top' => 5));
            }
            $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');
            $pdf->WriteHTML($html);
            if ($this->input->get('d')) {
                $pdf->Output('Invoice_#' . $data['invoice']['tid'] . '.pdf', 'D');
            } else {
                $pdf->Output('Invoice_#' . $data['invoice']['tid'] . '.pdf', 'I');
            }
        }
    }

    public function printquote()
    {

        
        if (!$this->input->get()) {
            exit();
        }
        $tid = intval($this->input->get('id'));
        $token = $this->input->get('token');

        $validtoken = hash_hmac('ripemd160', 'q' . $tid, $this->config->item('encryption_key'));
        // echo "<pre>"; print_r($validtoken); echo "<br>\n"; print_r($token); die();
        // if (hash_equals($token, $validtoken)) {
            $this->load->model('quote_model', 'quote');
            $data['id'] = $tid;
            $data['title'] = "Quote $tid";
            $data['invoice'] = $this->quote->quote_details($tid);
            //   echo "<pre>"; print_r($data['invoice']); die();
            $data['products'] = $this->quote->quote_products($tid);
            $data['employee'] = $this->quote->employee($data['invoice']['eid']);
            // echo "<pre>";
            // print_r($data['employee']);
            // die();
            $data['round_off'] = $this->custom->api_config(4);
            $data['general'] = array('title' => $this->lang->line('Quote'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(1), 't_type' => 1);

            ini_set('memory_limit', '64M');

            if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
                $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
            } else {
                $html = $this->load->view('print_files/invoice-quote-a4_v' . INVV, $data, true);
            }

            //PDF Rendering
            $this->load->library('pdf');
            if (INVV == 1) {
                $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
                $pdf = $this->pdf->load_split(array('margin_top' => 40));
                $pdf->SetHTMLHeader($header);
            }
            if (INVV == 2) {
                $pdf = $this->pdf->load_split(array('margin_top' => 5));
            }
            $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');

            $pdf->WriteHTML($html);

            if ($this->input->get('d')) {

                $pdf->Output('Quote_#' . $tid . '.pdf', 'D');
            } else {
                $pdf->Output('Quote_#' . $tid . '.pdf', 'I');
            }


        // }


    }


    public function printorder()
    {
        if (!$this->input->get()) {
            exit();
        }
        $tid = ($this->input->get('id'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', 'p' . $tid, $this->config->item('encryption_key'));

        // if (hash_equals($token, $validtoken)) {
            $this->load->model('purchase_model', 'purchase');

            $data['id'] = $tid;
            $data['title'] = "Invoice $tid";
            $data['invoice'] = $this->purchase->purchase_details($tid);
            $data['products'] = $this->purchase->purchase_products($tid);
            $preparedby = ($data['invoice']['assign_to']) ? $data['invoice']['assign_to'] : $data['invoice']['created_by'];
            $data['employee'] = $this->purchase->employee($preparedby);
            $data['round_off'] = $this->custom->api_config(4);
            $data['prefix'] = get_prefix();  
            $data['general'] = array('title' => $this->lang->line('Purchase Order'), 'person' => $this->lang->line('Supplier'), 'prefix' => prefix(2), 't_type' => 0);
            ini_set('memory_limit', '64M');
            if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
                $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
            } else {
                $html = $this->load->view('print_files/purchase-ordre-a4_v' . INVV, $data, true);
            }

            //PDF Rendering
            $this->load->library('pdf');
            if (INVV == 1) {
                $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
                $pdf = $this->pdf->load_split(array('margin_top' => 40));
                $pdf->SetHTMLHeader($header);
            }
            if (INVV == 2) {
                $pdf = $this->pdf->load_split(array('margin_top' => 5));
            }
            $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');

            $pdf->WriteHTML($html);

            if ($this->input->get('d')) {

                $pdf->Output('Purchase_#' . $tid . '.pdf', 'D');
            } else {
                $pdf->Output('Purchase_#' . $tid . '.pdf', 'I');
            }


        // }

    }

    public function printstockreturn()
    {
        if (!$this->input->get()) {
            exit();
        }
        $tid = intval($this->input->get('id'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', 's' . $tid, $this->config->item('encryption_key'));
        // if (hash_equals($token, $validtoken)) {
            $this->load->model('stockreturn_model', 'stockreturn');
            $data['id'] = $tid;
            $data['title'] = "Invoice $tid";
            $data['invoice'] = $this->stockreturn->purchase_details($tid);
            $data['products'] = $this->stockreturn->purchase_products($tid);
            $data['employee'] = $this->stockreturn->employee($data['invoice']['eid']);
            $data['round_off'] = $this->custom->api_config(4);
            $ty = $this->input->get('ty');
            // echo "<pre>"; print_r($data['invoice']); die();
            if ($ty < 2) {
                if ($data['invoice']['i_class'] == 1) {
                    $data['general'] = array('title' => $this->lang->line('Stock Return'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(4), 't_type' => 0);
                } else {
                    $data['general'] = array('title' => $this->lang->line('Stock Return'), 'person' => $this->lang->line('Supplier'), 'prefix' => prefix(4), 't_type' => 0);
                }
            } else {
                $data['general'] = array('title' => $this->lang->line('Credit Note'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(4), 't_type' => 0);
            }


            ini_set('memory_limit', '64M');
            if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
                $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
            } else {
                $html = $this->load->view('print_files/stock-return-a4_v' . INVV, $data, true);
            }
            //PDF Rendering
            $this->load->library('pdf');
            if (INVV == 1) {
                $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
                $pdf = $this->pdf->load_split(array('margin_top' => 40));
                $pdf->SetHTMLHeader($header);
            }
            if (INVV == 2) {
                $pdf = $this->pdf->load_split(array('margin_top' => 5));
            }
            $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');
            $pdf->WriteHTML($html);
            if ($this->input->get('d')) {
                $pdf->Output('Stockreturn_order#' . $tid . '.pdf', 'D');
            } else {
                $pdf->Output('Stockreturn_order#' . $tid . '.pdf', 'I');
            }
        // }
    }
    public function printpurchasereturnreturn()
    {
        if (!$this->input->get()) {
            exit();
        }
        $tid = ($this->input->get('id'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', 's' . $tid, $this->config->item('encryption_key'));
        // if (hash_equals($token, $validtoken)) {
            $this->load->model('Purchase_reciept_return_model', 'purchasereturn');     
            $data['id'] = $tid;
            $data['title'] = "Invoice $tid";
            $data['invoice'] = $this->purchasereturn->purchase_details($tid);
            $data['products'] = $this->purchasereturn->purchase_products($tid);

            $data['employee'] = $this->purchasereturn->employee($data['invoice']['eid']);
            $data['round_off'] = $this->custom->api_config(4);
            $ty = $this->input->get('ty');
           
            if ($ty < 2) {
                if ($data['invoice']['i_class'] == 1) {
                    $data['general'] = array('title' => $this->lang->line('Stock Return'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(4), 't_type' => 0);
                } else {
                    $data['general'] = array('title' => $this->lang->line('Stock Return'), 'person' => $this->lang->line('Supplier'), 'prefix' => prefix(4), 't_type' => 0);
                }
            } else {
                $data['general'] = array('title' => $this->lang->line('Credit Note'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(4), 't_type' => 0);
            }


            ini_set('memory_limit', '64M');
            if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
                $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
            } else {
                $html = $this->load->view('print_files/purchase-return-a4_v' . INVV, $data, true);
            }
            //PDF Rendering
            $this->load->library('pdf');
            if (INVV == 1) {
                $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
                $pdf = $this->pdf->load_split(array('margin_top' => 40));
                $pdf->SetHTMLHeader($header);
            }
            if (INVV == 2) {
                $pdf = $this->pdf->load_split(array('margin_top' => 5));
            }
            $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');
            $pdf->WriteHTML($html);
            if ($this->input->get('d')) {
                $pdf->Output('Stockreturn_order#' . $tid . '.pdf', 'D');
            } else {
                $pdf->Output('Stockreturn_order#' . $tid . '.pdf', 'I');
            }
        // }
    }


    public function card()
    {
        if (!$this->input->get()) {
            exit();
        }
        $data['redirect_u'] = '';
        if (isset($_COOKIE['pos_set'])) {
            $data['redirect_u'] = $_COOKIE['pos_set'];
            setcookie("pos_set", null, -1, '/');
        }
        $online_pay = $this->billing->online_pay_settings();
        if ($online_pay['enable'] == 0) {
            exit();
        }
        $data['tid'] = $this->input->get('id');
        $data['token'] = $this->input->get('token');
        $data['itype'] = $this->input->get('itype');
        $data['gid'] = $this->input->get('gid');
        if ($data['itype'] == 'inv') {
            $validtoken = hash_hmac('ripemd160', $data['tid'], $this->config->item('encryption_key'));
            if (hash_equals($data['token'], $validtoken)) {
                $data['invoice'] = $this->invocies->invoice_details($data['tid'], '', false);
                $data['company'] = location($data['invoice']['loc']);
            } else {
                exit();
            }
        }
        switch ($data['gid']) {
            case 1:
                $fname = 'stripe';
                break;
            case 2:
                $fname = 'authorize';
                break;
            case 3:
                $fname = 'pinpay';
                break;
            case 4:
                $fname = 'paypal';
                break;
            case 5:
                $fname = 'securepay';
                break;
            case 6:
                $fname = 'checkout';
                break;
            case 7:
                $fname = 'payumoney';
                break;
            case 8:
                $fname = 'razor';
                break;
            default :
                $fname = 'stripe';
                break;
        }
        $online_pay = $this->billing->online_pay_settings();
        $data['gateway'] = $this->billing->gateway($data['gid']);
        if ($online_pay['enable'] == 1) {
            $this->load->view('billing/header');
            $this->load->view('gateways/card_' . $fname, $data);
            $this->load->view('billing/footer');
        } else {
            echo '<h3>' . $this->lang->line('Online Payment Service') . '</h3>';
        }
    }

    public function process_card()
    {
        if (!$this->input->post()) {
            exit();
        }
        $tid = $this->input->post('id', true);
        $itype = $this->input->post('itype', true);
        $gateway = $this->input->post('gateway', true);

        $amount = number_format($this->input->post('amount', true), 2, '.', '');

        if ($itype == 'inv') {
            $customer = $this->invocies->invoice_details($tid, null, false);
            if (!$customer['tid']) {
                exit();
            }
        }
        $hash = $this->input->post('token', true);

        $cardNumber = $this->input->post('cardNumber', true);
        $cardExpiry = $this->input->post('cardExpiry', true);
        $cardCVC = $this->input->post('cardCVC', true);
        $nmonth = substr($cardExpiry, 0, 2);
        $nyear = '20' . substr($cardExpiry, 5, 2);
        $note = 'Card Payment for #' . $customer['tid'];
        $pmethod = 'Card';
        $amount_o = $amount;
        if ($customer['multi'] > 0) {
            $multi_currency = $this->invocies->currency_d($customer['multi']);
            //    $amount =  $amount;
            $gateway_data['currency'] = $multi_currency['code'];
            $note .= ' (Currency Conversion Applied)';
        }
        if ($customer['loc'] > 0) {
            $multi_currency = $this->invocies->currency_d($customer['multi'], $customer['loc']);
            //        $amount =  $amount;
            $gateway_data['currency'] = $multi_currency['code'];
            $note .= ' (Currency Conversion Applied)';
        }
        $validtoken = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
        $gateway_data = $this->billing->gateway($gateway);
        $surcharge = ($amount * $gateway_data['surcharge']) / 100;
        $amount_t = $amount + $surcharge;
        $amount = number_format($amount_t, 2, '.', '');
        if (hash_equals($hash, $validtoken)) {
            switch ($gateway) {
                case 1:

                    $response = $this->stripe($this->input->post('paymentMethodId', true), number_format($amount, 0, '', ''), $gateway_data, $tid, $customer, '', $this->input->post('paymentIntentId', true));
                    break;
                case 2:
                    $response = $this->authorizenet($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data, $customer);
                    break;
                case 3:
                    $response = $this->pinpay($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data, $customer);
                    break;
                case 4:
                    $response = $this->paypal($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data, $customer);
                    break;
                case 5:
                    $response = $this->securepay($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data);
                    break;
                case 6:
                    $response = $this->twocheckout($this->input->post('auth_token', true), $amount, $tid, $gateway_data, $customer);
                    break;
            }
            // Process response

            if ($gateway > 1) {
                if ($response->isSuccessful()) {

                    $amount_o = rev_amountExchange_s($amount_o, $customer['multi'], $customer['loc']);
                    if ($this->billing->paynow($tid, $amount_o, $note, $pmethod, $customer['loc'])) {
                        header('Content-Type: application/json');
                        echo json_encode(array('status' => 'Success', 'message' =>
                            $this->lang->line('Thank you for the payment') . " <a href='" . base_url('billing/view?id=' . $tid . '&token=' . $hash) . "' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> " . $this->lang->line('View') . "</a>"));
                    }
                } elseif ($response->isRedirect()) {
                    // Redirect to offsite payment gateway
                    $response->redirect();
                } else {
                    // Payment failed
                    echo json_encode(array('status' => 'Error', 'message' =>
                        $this->lang->line('Payment failed')));
                }
            } elseif ($gateway == 1 and @$response['status'] == 'succeeded') {
                $amount_o = rev_amountExchange_s(($amount - $surcharge) / 100, $customer['multi'], $customer['loc']);
                if ($this->billing->paynow($tid, $amount_o, $note, $pmethod, $customer['loc'])) {
                    header('Content-Type: application/json');
                    echo json_encode(array('status' => 'Success', 'clientSecret' => $response['clientSecret'], 'message' =>
                        $this->lang->line('Thank you for the payment') . " <a href='" . base_url('billing/view?id=' . $tid . '&token=' . $hash) . "' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> " . $this->lang->line('View') . "</a>"));

                }
            } elseif ($gateway == 1 and @$response['status'] == 'error') {
                header('Content-Type: application/json');
                echo json_encode(array('error' => $response['message']));
            }

        }
    }


    private function stripe($token, $amount, $gateway_data, $tid, $customer, $currency = '', $token_id = '')
    {
        require_once APPPATH . 'third_party/stripe-php/vendor/autoload.php';
        \Stripe\Stripe::setApiKey($gateway_data['key1']);
        try {
            if ($token) {
                // Create new PaymentIntent with a PaymentMethod ID from the client.
                $intent = \Stripe\PaymentIntent::create([
                    "amount" => $amount,
                    "currency" => $gateway_data['currency'],
                    "payment_method" => $token,
                    "confirmation_method" => "manual",
                    "confirm" => true,
                    // If a mobile client passes `useStripeSdk`, set `use_stripe_sdk=true`
                    // to take advantage of new authentication features in mobile SDKs
                    "use_stripe_sdk" => true,

                ]);
                switch ($intent->status) {
                    case "succeeded":

                        return array('status' => 'succeeded', 'paid_amount' => $intent->amount, 'clientSecret' => $intent->client_secret);
                        break;
                }
                // After create, if the PaymentIntent's status is succeeded, fulfill the order.
            } else if ($token_id) {
                // Confirm the PaymentIntent to finalize payment after handling a required action
                // on the client.

                $intent = \Stripe\PaymentIntent::retrieve($token_id);
                $intent->confirm();
                // After confirm, if the PaymentIntent's status is succeeded, fulfill the order.
                switch ($intent->status) {
                    case "succeeded":

                        return array('status' => 'succeeded', 'paid_amount' => $intent->amount, 'clientSecret' => $intent->client_secret);
                        break;
                }

            }

            $output = $this->generateResponse($intent);

            echo json_encode($output);
        } catch (Stripe\Exception\CardException $e) {
            return array('status' => 'error', 'paid_amount' => 0, 'message' => $e->getMessage());

        }

    }


    private function authorizenet($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data, $customer)
    {
        $gateway = Omnipay::create('AuthorizeNet_AIM');
        $gateway->setApiLoginId($gateway_data['key2']);
        $gateway->setTransactionKey($gateway_data['key1']);
        $gateway->setDeveloperMode($gateway_data['dev_mode']);
        $meta = array(
            'Name' => $customer['name'],
            'email' => $customer['email']
        );
        try {
            return $gateway->purchase(
                array(
                    'card' => array(
                        'number' => $cardNumber,
                        'expiryMonth' => $nmonth,
                        'expiryYear' => $nyear,
                        'cvv' => $cardCVC
                    ),
                    'amount' => $amount,
                    'currency' => $gateway_data['currency'],
                    'description' => 'Paid for ' . $customer['name'] . ' INV#' . $tid,
                    'metadata' => $meta

                )
            )->send();
        } catch (Exception $e) {
            return 0;
        }
    }


    private function pinpay($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data, $customer)
    {
        $gateway = \Omnipay\Omnipay::create('Pin');

        // Initialise the gateway
        $gateway->initialize(array(
            'secretKey' => $gateway_data['key1'],
            'testMode' => $gateway_data['dev_mode'], // Or false when you are ready for live transactions
        ));

        // Create a credit card object
        // This card can be used for testing.
        // See https://pin.net.au/docs/api/test-cards for a list of card
        // numbers that can be used for testing.
        $card = new \Omnipay\Common\CreditCard(array(
            'firstName' => $customer['name'],
            'lastName' => 'Customer',
            'number' => $cardNumber,
            'expiryMonth' => $nmonth,
            'expiryYear' => $nyear,
            'cvv' => $cardCVC,
            'email' => $customer['email'],
            'billingAddress1' => $customer['address'],
            'billingCountry' => $customer['country'],
            'billingCity' => $customer['city'],
            'billingPostcode' => $customer['postbox'],
            'billingState' => $customer['region'],
        ));

        // Do a purchase transaction on the gateway
        $transaction = $gateway->purchase(array(
            'description' => 'Payment for INV#' . $tid,
            'amount' => $amount,
            'currency' => $gateway_data['currency'],
            'clientIp' => $_SERVER['REMOTE_ADDR'],
            'card' => $card,
        ));
        return $transaction->send();

    }


    private function securepay($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data)
    {


        $gateway = \Omnipay\Omnipay::create('SecurePay_SecureXML');
        $gateway->setMerchantId($gateway_data['key1']);
        $gateway->setTransactionPassword($gateway_data['key2']);
        $gateway->setTestMode($gateway_data['dev_mode']);
        // Create a credit card object
        $card = new \Omnipay\Common\CreditCard(
            [
                'number' => $cardNumber,
                'expiryMonth' => $nmonth,
                'expiryYear' => $nyear,
                'cvv' => $cardCVC,
            ]
        );
        // Perform a purchase test
        $transaction = $gateway->purchase(
            [
                'amount' => $amount,
                'currency' => $gateway_data['currency'],
                'transactionId' => 'invoice_' . $tid,
                'card' => $card,
            ]
        );

        return $transaction->send();
    }

    private function twocheckout($auth_token, $amount, $tid, $gateway_data, $customer)
    {


        $gateway = Omnipay::create('TwoCheckoutPlus_Token');
        $gateway->setAccountNumber($gateway_data['extra']);
        $gateway->setTestMode($gateway_data['dev_mode']);
        $gateway->setPrivateKey($gateway_data['key2']);

        $formData = array(
            'firstName' => $customer['name'],
            'email' => $customer['email'],
            'billingAddress1' => $customer['address'],
            'billingCountry' => $customer['country'],
            'billingCity' => $customer['city'],
            'billingPostcode' => $customer['postbox'],
            'billingState' => $customer['region'],
            "phoneNumber" => $customer['phone'],
        );


        $purchase_request_data = array(
            'card' => $formData,
            'token' => $auth_token,
            'transactionId' => $tid,
            'currency' => $gateway_data['currency'],
            'total' => $amount,
            'amount' => $amount,
        );
        return $gateway->purchase($purchase_request_data)->send();


    }


    private function paypal($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data, $customer)
    {

        $gateway = Omnipay::create('PayPal_Rest');
        // Initialise the gateway
        $gateway->initialize(array(
            'clientId' => $gateway_data['key1'],
            'secret' => $gateway_data['key2'],
            'testMode' => $gateway_data['dev_mode'], // Or false when you are ready for live transactions
        ));

        $card = new \Omnipay\Common\CreditCard(array(
            'firstName' => $customer['name'],
            'lastName' => 'Customer',
            'number' => $cardNumber,
            'expiryMonth' => $nmonth,
            'expiryYear' => $nyear,
            'cvv' => $cardCVC,
            'billingAddress1' => $customer['address'],
            'billingCountry' => $customer['country'],
            'billingCity' => $customer['city'],
            'billingPostcode' => $customer['postbox'],
            'billingState' => $customer['region']
        ));

        try {
            $transaction = $gateway->purchase(array(
                'amount' => $amount,
                'currency' => $gateway_data['currency'],
                'description' => 'Payment for #inv ' . $tid,
                'card' => $card,
            ));
            return $transaction->send();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function bank()
    {
        $online_pay = $this->billing->online_pay_settings();
        if ($online_pay['bank'] == 1) {
            $data['accounts'] = $this->billing->bank_accounts('Yes');
            $this->load->view('billing/header');
            $this->load->view('payment/public_bank_view', $data);
            $this->load->view('billing/footer');
        }

    }


    public function recharge()
    {

        if (!$this->input->get()) {
            exit();
        }
        $online_pay = $this->billing->online_pay_settings();
        if ($online_pay['enable'] == 0) {
            exit();
        }
        $data['id'] = base64_decode($this->input->get('id', true));


        $data['amount'] = $this->input->get('amount', true);
        $data['gid'] = $this->input->get('gid', true);
        $data['token'] = $this->input->get('token', true);

        switch ($data['gid']) {
            case 1:
                $fname = 'stripe';
                break;
            case 2:
                $fname = 'authorize';
                break;
            case 3:
                $fname = 'pinpay';
                break;
            case 4:
                $fname = 'paypal';
                break;
            case 5:
                $fname = 'securepay';
                break;
            case 6:
                $fname = 'checkout';
                break;
            case 7:
                $fname = 'payumoney';
                break;
            case 8:
                $fname = 'razor';
                break;
            default :
                $fname = 'stripe';
                break;
        }
        $online_pay = $this->billing->online_pay_settings();
        $data['gateway'] = $this->billing->gateway($data['gid']);
        if ($online_pay['enable'] == 1) {
            $this->load->view('billing/header');
            $this->load->view('gateways/recharge/card_' . $fname, $data);
            $this->load->view('billing/footer');
        } else {
            echo '<h3>' . $this->lang->line('Online Payment Service') . '</h3>';
        }

    }

    public function process_recharge()
    {
        if (!$this->input->post()) {
            exit();
        }

        $tid = $this->input->post('id', true);
        $amount = number_format($this->input->post('amount', true), 2, '.', '');
        $gateway = $this->input->post('gateway', true);
        $cardNumber = $this->input->post('cardNumber', true);
        $cardExpiry = $this->input->post('cardExpiry', true);
        $cardCVC = $this->input->post('cardCVC', true);

        $nmonth = substr($cardExpiry, 0, 2);
        $nyear = '20' . substr($cardExpiry, 5, 2);


        $pmethod = 'Card';

        $amount_o = $amount;

        $gateway_data = $this->billing->gateway($gateway);
        $surcharge = ($amount * $gateway_data['surcharge']) / 100;
        $amount_t = $amount + $surcharge;
        $this->load->model('customers_model', 'customers');
        $customer = $this->customers->details($tid, false);
        $note = 'Recharge Card Payment for Customer' . $customer['email'];


        $amount = number_format($amount_t, 2, '.', '');


        switch ($gateway) {

            case 1:
                //       $response = $this->stripe($this->input->post('stripeToken', true), $amount, $gateway_data, $tid, $customer);

                $response = $this->stripe($this->input->post('paymentMethodId', true), number_format($amount, 0, '', ''), $gateway_data, $tid, $customer, '', $this->input->post('paymentIntentId', true));

                break;
            case 2:
                $response = $this->authorizenet($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data, $customer);
                break;
            case 3:
                $response = $this->pinpay($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data, $customer);
                break;
            case 4:
                $response = $this->paypal($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data, $customer);
                break;
            case 5:
                $response = $this->securepay($cardNumber, $nmonth, $nyear, $cardCVC, $amount, $tid, $gateway_data);
                break;
            case 6:
                $response = $this->twocheckout($this->input->post('auth_token', true), $amount, $tid, $gateway_data, $customer);
                break;

        }

        // Process response
        if ($gateway > 1) {
            if ($response->isSuccessful()) {

                if ($this->billing->recharge_done($tid, $amount_o)) {
                    header('Content-Type: application/json');
                    echo json_encode(array('status' => 'Success', 'message' =>
                        $this->lang->line('Thank you for the payment') . " <a href='" . base_url('crm/payments/recharge') . "' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> " . $this->lang->line('View') . "</a>"));
                }

            } elseif ($response->isRedirect()) {

                // Redirect to offsite payment gateway
                $response->redirect();

            } else {

                // Payment failed
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('Payment failed')));
            }
        } elseif ($gateway == 1 and @$response['status'] == 'succeeded') {
            $amount_o = $amount_o / 100;
            if ($this->billing->recharge_done($tid, $amount_o)) {
                header('Content-Type: application/json');
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Thank you for the payment') . " <a href='" . base_url('crm/payments/recharge') . "' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> " . $this->lang->line('View') . "</a>"));
            }
        } elseif ($gateway == 1 and @$response['status'] == 'error') {
            header('Content-Type: application/json');
            echo json_encode(array('error' => $response['message']));
        }


    }

    public function secureprocess()
    {

        $gid = $this->input->get('g', true);
        //payu
        if ($gid == 7) {
            $status = $this->input->post('status', true);
            $firstname = $this->input->post("firstname", true);
            $amount = $this->input->post("amount", true);
            $txnid = $this->input->post("txnid", true);
            $posted_hash = $this->input->post("hash", true);
            $key = $this->input->post("key", true);
            $productinfo = $this->input->post("productinfo", true);
            $email = $this->input->post("email", true);
            $gateway_data = $this->billing->gateway($gid);
            $salt = $gateway_data['key2'];

            // Salt should be same Post Request

            if ($this->input->post('additionalCharges', true)) {
                $additionalCharges = $this->input->post("additionalCharges", true);
                $retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
            } else {
                $retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
            }
            $hash = hash("sha512", $retHashSeq);
            if ($hash != $posted_hash) {
                echo "Invalid Transaction. Please try again";
            } elseif($status=='success') {

                //tt
                $tid = $this->input->get('inv', true);
                $customer = $this->invocies->invoice_details($tid);
                $note = 'Card Payment for #' . $customer['tid'] . ' T#' . $txnid;
                $pmethod = 'Card';
                $amount_o = $customer['total'] - $customer['pamnt'];
                $surcharge = ($amount_o * $gateway_data['surcharge']) / 100;
                $amount_t = $amount_o + $surcharge;
                $validtoken = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
                if (number_format($amount_t, 2, '.', '') == $amount) {
                    $amount = number_format($amount_o, 2, '.', '');
                    if ($this->billing->paynow($customer['iid'], $amount, $note, $pmethod, $customer['loc'])) {

                        redirect(base_url('billing/view?id=' . $tid . '&token=' . $validtoken));
                    }
                }
            } else {
                   $tid = $this->input->get('inv', true);
                   $validtoken = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
                echo "Invalid Transaction. Please try again";
                    redirect(base_url('billing/view?id=' . $tid . '&token=' . $validtoken));
            }


        } else {
            $data['gateway_data'] = $this->billing->gateway($gid);
            $data['tid'] = $this->input->get('inv', true);
            $this->load->view('gateways/card_razor_verify', $data);
        }
    }


    public function gateway_process()
    {
        //for paypal
        $invoice = $this->input->post('id', true);
        $token = $this->input->post('token', true);

        $gateway_data = $this->billing->gateway(4);
        $paypalConfig = [
            'sandbox' => $gateway_data['dev_mode'],
            'client_id' => $gateway_data['key1'],
            'client_secret' => $gateway_data['key2'],
            'return_url' => base_url('billing/gateway_response'),
            'cancel_url' => base_url('billing/view?id=' . $invoice . '&token=' . $token)
        ];

        $this->load->library("Paypal_gateway", $paypalConfig);

        $apiContext = $this->paypal_gateway->getApiContext();


        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        // Set some example data for the payment.
        $customer = $this->invocies->invoice_details($invoice);
        if (!$customer['tid']) {
            exit();
        }
        $amount = number_format($this->input->post('amount', true), 2, '.', '');
        if ($customer['multi'] > 0) {
            $multi_currency = $this->invocies->currency_d($customer['multi']);
            //    $amount =  $amount;
            $gateway_data['currency'] = $multi_currency['code'];

        }
        if ($customer['loc'] > 0) {
            $multi_currency = $this->invocies->currency_d($customer['multi'], $customer['loc']);
            //        $amount =  $amount;
            $gateway_data['currency'] = $multi_currency['code'];

        }
        $validtoken = hash_hmac('ripemd160', $invoice, $this->config->item('encryption_key'));
        $surcharge = ($amount * $gateway_data['surcharge']) / 100;
        $amount_t = $amount + $surcharge;
        $amount = number_format($amount_t, 2, '.', '');

        if (hash_equals($token, $validtoken)) {

            $amountPayable = $amount;
            $invoiceNumber = $invoice;

            $amount = new Amount();
            $amount->setCurrency($gateway_data['currency'])
                ->setTotal($amountPayable);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setDescription('Some description about the payment being made')
                ->setInvoiceNumber($invoiceNumber);

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($paypalConfig['return_url'])
                ->setCancelUrl($paypalConfig['cancel_url']);

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

            try {
                $payment->create($apiContext);
                $this->billing->token($invoice, 1);
            } catch (Exception $e) {
                throw new Exception('Unable to create link for payment');
            }

            header('location:' . $payment->getApprovalLink());
            exit(1);
        }


    }

    public function gateway_response()
    {
        if (empty($this->input->get('paymentId', true)) || empty($this->input->get('PayerID', true))) {
            exit;
        }
        $gateway_data = $this->billing->gateway(4);
        $paypalConfig = [
            'sandbox' => $gateway_data['dev_mode'],
            'client_id' => $gateway_data['key1'],
            'client_secret' => $gateway_data['key2'],
            'return_url' => base_url('billing/gateway_response'),
            'cancel_url' => base_url('billing/view?id=105&token=ee2f511d44dd7f0212d46b92f2d6022754574bb3')
        ];
        $this->load->library("Paypal_gateway", $paypalConfig);
        $apiContext = $this->paypal_gateway->getApiContext();
        $paymentId = $_GET['paymentId'];
        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($_GET['PayerID']);
        try {
            // Take the payment
            $payment->execute($execution, $apiContext);
            try {
                $payment = Payment::get($paymentId, $apiContext);
                $data = [
                    'transaction_id' => $payment->getId(),
                    'payment_amount' => $payment->transactions[0]->amount->total,
                    'payment_status' => $payment->getState(),
                    'invoice_id' => $payment->transactions[0]->invoice_number
                ];
                $validtoken = hash_hmac('ripemd160', $data['invoice_id'], $this->config->item('encryption_key'));
                $paypalConfig['bill_url'] = base_url('billing/view?id=' . $data['invoice_id'] . '&token=' . $validtoken);
                if ($data['payment_status'] === 'approved') {
                    $customer = $this->invocies->invoice_details($data['invoice_id']);
                    $amount_o = $data['payment_amount'];

                    $amount_o = rev_amountExchange_s($amount_o, $customer['multi'], $customer['loc']);

                    $note = 'Card Payment for #' . $customer['tid'];
                    $pmethod = 'Card';
                    if ($customer['multi'] > 0) {
                        //    $amount =  $amount;
                        $note .= ' (Currency Conversion Applied)';
                    }
                    if ($customer['loc'] > 0) {

                        $note .= ' (Currency Conversion Applied)';
                    }
                    $amount = $amount_o / (($gateway_data['surcharge'] / 100) + 1);
                    $amount_o = number_format($amount, 2, '.', '');
                    $valid = $this->billing->token($customer['iid'], 2);
                    if ($valid['rid'] == $customer['iid']) {
                        $this->billing->paynow($customer['iid'], $amount_o, $note, $pmethod, $customer['loc']);
                        $this->billing->token($customer['iid'], 3);
                    }
                    header('location:' . $paypalConfig['bill_url']);
                    exit(1);
                } else {
                    // Payment failed
                    header('location:' . $paypalConfig['bill_url']);
                    exit(1);
                }
            } catch (Exception $e) {
                // Failed to retrieve payment from PayPal
                $this->billing->token($customer['iid'], 3);
                header('location:' . base_url());
            }
        } catch (Exception $e) {
            // Failed to take payment
            $this->billing->token($customer['iid'], 3);
            header('location:' . base_url());
        }
    }

    public function process_stripe()
    {
        echo 'Payment processing do no hit back button.....';
    }

    public function stripe_api_response()
    {

        $data['gateway'] = $this->billing->gateway(1);
        echo json_encode(['publishableKey' => $data['gateway']['key2']]);
    }

    function generateResponse($intent)
    {
        switch ($intent->status) {
            case "requires_action":
            case "requires_source_action":
                // Card requires authentication
                return [
                    'requiresAction' => true,
                    'paymentIntentId' => $intent->id,
                    'clientSecret' => $intent->client_secret
                ];
            case "requires_payment_method":
            case "requires_source":
                // Card was not properly authenticated, suggest a new payment method
                return [
                    'error' => "Your card was denied, please provide a new payment method"
                ];
            case "succeeded":
                // Payment is complete, authentication not required
                // To cancel the payment after capture you will need to issue a Refund (https://stripe.com/docs/api/refunds)
                return ['clientSecret' => $intent->client_secret];
        }
    }

    // erp2024 purchase receipt 03-10-2024
    
    public function printporeciept()
    {
        //    ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        if (!$this->input->get()) {
            exit();
        }
        $id = intval($this->input->get('id'));
        $tid = intval($this->input->get('id'));
        $token = $this->input->get('token');

        $validtoken = hash_hmac('ripemd160', 'p' . $tid, $this->config->item('encryption_key'));

        // if (hash_equals($token, $validtoken)) {
         $this->load->model('costingcalculation_model', 'costingcalculation');
         $data['purchaseorderdata'] = $this->costingcalculation->purchase_order_by_srv($id);
         $data['invoice'] = $this->costingcalculation->cberp_costing_master_details($id);
         $data['products'] = $this->costingcalculation->costing_item_details($id);
         $data['purchaseexpensesdata'] = $this->costingcalculation->cberp_costing_expenses_details($id);           
         $data['assignedperson'] = $this->costingcalculation->employee($data['invoice']['assigned_to']);
         $data['preparedperson'] = $this->costingcalculation->employee($data['invoice']['prepared_by']);
         $data['supplier'] = $this->costingcalculation->supplier_details($data['invoice']['supplier_id']);
            $data['id'] = $tid;
            $data['title'] = "Purcahse Receipt ". $id;
            $data['round_off'] = $this->custom->api_config(4);
            $data['general'] = array('title' => $this->lang->line('Purchase Order'), 'person' => $this->lang->line('Supplier'), 'prefix' => prefix(2), 't_type' => 0);
            ini_set('memory_limit', '64M');
            if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
                $html = $this->load->view('print_files/purchase-receipt-a4_v' . INVV, $data, true);
            } else {
                $html = $this->load->view('print_files/purchase-receipt-a4_v' . INVV, $data, true);
            }

            //PDF Rendering
            $this->load->library('pdf');
            if (INVV == 1) {
                $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
                $pdf = $this->pdf->load_split(array('margin_top' => 40));
                $pdf->SetHTMLHeader($header);
            }
            if (INVV == 2) {
                $pdf = $this->pdf->load_split(array('margin_top' => 5));
            }
            $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');

            $pdf->WriteHTML($html);

            if ($this->input->get('d')) {

                $pdf->Output('Purchase_#' . $tid . '.pdf', 'D');
            } else {
                $pdf->Output('Purchase_#' . $tid . '.pdf', 'I');
            }


        // }

    }
    public function purchase_reciept_preview()
    {
        if (!$this->input->get()) {
            exit();
        }
        $id = intval($this->input->get('id'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', 'p' . $tid, $this->config->item('encryption_key'));
        // if (hash_equals($token, $validtoken)) {
            $data['id'] = $id;
            $data['token'] = $token;
            $this->load->model('costingcalculation_model', 'costingcalculation');
            $data['purchaseorderdata'] = $this->costingcalculation->purchase_order_by_srv($id);
            $data['invoice'] = $this->costingcalculation->cberp_costing_master_details($id);
            $data['products'] = $this->costingcalculation->costing_item_details($id);
            $data['purchaseexpensesdata'] = $this->costingcalculation->cberp_costing_expenses_details($id);           
            $data['assignedperson'] = $this->costingcalculation->employee($data['invoice']['assign_to']);
            $data['preparedperson'] = $this->costingcalculation->employee($data['invoice']['prepared_by']);
            $data['supplier'] = $this->costingcalculation->supplier_details($data['invoice']['supplier_id']);
            // echo "<pre>"; print_r($data['products']); die();
            $head['title'] = "Purchase Reciept" . $data['purchaseorderdata']['tid'];
            $head['usernm'] = '';
            $this->load->view('billing/header', $head);
            $this->load->view('billing/purchase_reciept', $data);
            $this->load->view('billing/footer');
        // }
    }

    function bank_transaction_pdf()
    {
      
        // ini_set('memory_limit', '64M');

        $display_fields = ['Name', 'Email', 'Phone', 'Address'];
        $output_data = [
            ['John Doe', 'john@example.com', '1234567890', '123 Main St'],
            ['Jane Smith', 'jane@example.com', '0987654321', '456 Elm St']
        ];
        
        $html = $this->load->view('reports/journalentriesprintpdf-' . LTR, $display_fields, true);   
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('balance-sheet-report' . $pay_acc . '.pdf', 'I');
    }


    public function purchase_return_debit_memo()
    {
        if (!$this->input->get()) {
            exit();
        }
        $tid = ($this->input->get('id'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', 's' . $tid, $this->config->item('encryption_key'));
        // if (hash_equals($token, $validtoken)) {
            
        $this->load->model('Purchase_reciept_return_model', 'purchasereturn');
            $data['id'] = $tid;
            $data['title'] = "Invoice $tid";
            $data['invoice'] = $this->purchasereturn->purchase_details($tid);
            $data['products'] = $this->purchasereturn->purchase_products($tid);
            $data['employee'] = $this->purchasereturn->employee($data['invoice']['eid']);
            $data['round_off'] = $this->custom->api_config(4);
            $ty = $this->input->get('ty');

            if ($ty < 2) {
                if ($data['invoice']['i_class'] == 1) {
                    $data['general'] = array('title' => $this->lang->line('Stock Return'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(4), 't_type' => 0);
                } else {
                    $data['general'] = array('title' => $this->lang->line('Stock Return'), 'person' => $this->lang->line('Supplier'), 'prefix' => prefix(4), 't_type' => 0);
                }
            } else {
                $data['general'] = array('title' => $this->lang->line('Credit Note'), 'person' => $this->lang->line('Customer'), 'prefix' => prefix(4), 't_type' => 0);
            }


            ini_set('memory_limit', '64M');
            if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
                $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
            } else {
                $html = $this->load->view('print_files/purchase-return-debit-memo-a4_v' . INVV, $data, true);
            }
            //PDF Rendering
            $this->load->library('pdf');
            if (INVV == 1) {
                $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
                $pdf = $this->pdf->load_split(array('margin_top' => 40));
                $pdf->SetHTMLHeader($header);
            }
            if (INVV == 2) {
                $pdf = $this->pdf->load_split(array('margin_top' => 5));
            }
            $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');
            $pdf->WriteHTML($html);
            if ($this->input->get('d')) {
                $pdf->Output('Stockreturn_order#' . $tid . '.pdf', 'D');
            } else {
                $pdf->Output('Stockreturn_order#' . $tid . '.pdf', 'I');
            }
        // }
    }

    // 26-04-2025
    public function pre_print_invoice1()
    {
  
        if (!$this->input->get()) {
            exit();
        }
        $tid = intval($this->input->get('id'));
        $token = $this->input->get('token');
        $validtoken = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
        $data['print_settings'] = print_settings_details('Pre-Print');
       
        if (hash_equals($token, $validtoken)) {
            $data['id'] = $tid;
            $data['invoice'] = $this->invocies->invoice_details($tid);
            
            $data['refdetails'] = $this->invocies->sales_reference_bydelnoteid($data['invoice']['delevery_note_id']);
            if($data['invoice']['status']=="post dated cheque"){
                $data['checkdate'] = $this->invocies->payment_method_details($data['invoice']['iid']);
            }
          
            $data['title'] = "Invoice " . $data['invoice']['tid'];
            $data['products'] = $this->invocies->invoice_products($tid);
            //   echo "<pre>"; print_r($data['invoice']); die();
            $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
            if (CUSTOM) {
                $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['cid'], 1, 1);
                $data['i_custom_fields'] = $this->custom->view_fields_data($tid, 2, 1);
            }


            $data['round_off'] = $this->custom->api_config(4);
            if ($data['invoice']['i_class'] == 1) {
                $pref = prefix(7);
            } elseif ($data['invoice']['i_class'] > 1) {
                $pref = prefix(3);
            } else {
                $pref = $this->config->item('prefix');
            }
            $data['general'] = array('title' => $this->lang->line('Invoice'), 'person' => $this->lang->line('Customer'), 'prefix' => $pref, 't_type' => 0);
            ini_set('memory_limit', '64M');
            if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
                $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
            } else {
                $html = $this->load->view('print_files/invoice_pre_print_v' . INVV, $data, true);
                //    $html=str_replace("strong","span",$html);
                //     $html=str_replace("<h","<span",$html);
            }
            // ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);
            //PDF Rendering
            $this->load->library('pdf');
            // if (INVV == 1) {
                // $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
                // $pdf = $this->pdf->load_split([
                //     'margin_top' => 20,
                //     'margin_bottom' => 20,
                //     'margin_left' => 80,
                //     'margin_right' => 20,
                // ]);
                $header_height = convertToMillimeters($data['print_settings']['header_height'], $data['print_settings']['measurement_unit']);
                $footer_height = convertToMillimeters($data['print_settings']['footer_height'], $data['print_settings']['measurement_unit']);
                $margin_left = convertToMillimeters($data['print_settings']['margin_left'], $data['print_settings']['measurement_unit']);
                $margin_right = convertToMillimeters($data['print_settings']['margin_right'], $data['print_settings']['measurement_unit']);
                // $pdf = new \Mpdf\Mpdf(['autoArabic' => true]);
                $pdf = new \Mpdf\Mpdf([
                    // 'format' => [148, 210], // your custom size
                    // 'orientation' => 'L',   // 'P' = Portrait, 'L' = Landscape
                    'margin_top' => $header_height,
                    'margin_bottom' => $footer_height,
                    'margin_left' => $margin_left,
                    'margin_right' => $margin_right
                ]);
                
               
            //     $pdf->SetHTMLHeader($header);
            // }
            // if (INVV == 2) {
            //     $pdf = $this->pdf->load_split(array('margin_top' => 5));
            // }
            // $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');
            $pdf->WriteHTML($html);
            if ($this->input->get('d')) {
                $pdf->Output('Invoice_#' . $data['invoice']['tid'] . '.pdf', 'D');
            } else {
                $pdf->Output('Invoice_#' . $data['invoice']['tid'] . '.pdf', 'I');
            }
        }
    }

    public function pre_print_invoice()
    {
        //         ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        if (!$this->input->get()) {
            exit();
        }
        $tid = ($this->input->get('id'));
        $token = $this->input->get('token');        
        $receipt_number = ($this->input->get('receipt_number'));
        $data['receipt_details'] = "";
        $validtoken = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
        $data['print_settings'] = print_settings_details('Pre-Print'); 
        if (hash_equals($token, $validtoken)) {
            $data['id'] = $tid;
            $data['receipt_number'] = $receipt_number;
            if($receipt_number)
            {  
                 $data['receipt_details'] = $this->invocies->payment_receipt_details($tid,$receipt_number);
            }                       
            $header_height = convertToMillimeters($data['print_settings']['header_height'], $data['print_settings']['measurement_unit']);
            $footer_height = convertToMillimeters($data['print_settings']['footer_height'], $data['print_settings']['measurement_unit']);
            $margin_left = convertToMillimeters($data['print_settings']['margin_left'], $data['print_settings']['measurement_unit']);
            $margin_right = convertToMillimeters($data['print_settings']['margin_right'], $data['print_settings']['measurement_unit']);
            $data['page_width'] = convertToMillimeters($data['print_settings']['page_width'], $data['print_settings']['measurement_unit']);
            $data['page_height'] = convertToMillimeters($data['print_settings']['page_height'], $data['print_settings']['measurement_unit']);
            $data['row_height'] = convertToMillimeters($data['print_settings']['row_height'], $data['print_settings']['measurement_unit']);
            $data['bill_details_height'] = convertToMillimeters($data['print_settings']['bill_details_height'], $data['print_settings']['measurement_unit']);
            $data['bill_details'] = $data['print_settings']['bill_details'];
            $data['display_item_labels'] = $data['print_settings']['display_item_labels'];

            $data['header_height'] = $header_height;
            $data['footer_height'] = $footer_height;
            $data['margin_left'] = $margin_left;
            $data['margin_right'] = $margin_right;

            $data['invoice'] = $this->invocies->invoice_details($tid);
            $data['products'] = $this->invocies->invoice_products($tid);
            $data['employee'] = $this->invocies->employee($data['invoice']['employee_id']);
            $data['round_off'] = $this->custom->api_config(4);        
            $this->load->view('print_files/invoice_dotmatrix', $data); 
        }
    }


}
