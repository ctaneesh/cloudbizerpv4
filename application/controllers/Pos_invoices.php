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
require_once APPPATH . 'third_party/vendor/autoload.php';
require_once APPPATH . 'third_party/qrcode/vendor/autoload.php';

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\EscposImage;

use Omnipay\Omnipay;
use Endroid\QrCode\QrCode;


class Pos_invoices extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pos_invoices_model', 'invocies');

        $this->load->model('extended_invoices_model', 'extended_invoice');

		$this->load->model('Billing_model', 'billing');

        $this->load->library("Aauth");
        $this->load->library("Registerlog");
        $this->load->library("Common");
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(12)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        if ($this->aauth->get_user()->roleid == 2) {
            $this->limited = $this->aauth->get_user()->id;
        } else {
            $this->limited = '';
        }
        $this->load->library("Custom");
        $this->li_a = 'sales';
    }

    //create invoice
    public function create()
    {
		
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);
		
        if (!$this->registerlog->check($this->aauth->get_user()->id)) {
            redirect('register/create');
        }


        $this->load->model('customers_model', 'customers');
        $this->load->model('categories_model');
        $this->load->model('plugins_model', 'plugins');
        $this->load->library("Common");
          $data['custom_fields_c'] = $this->custom->add_fields(1);

		  

        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $data['gateway'] = $this->invocies->gateway_list('Yes');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['enable_card'] = $this->plugins->universal_api(54);
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->invocies->lastinvoice();
        $data['draft_list'] = $this->invocies->drafts();
        $data['warehouse'] = $this->invocies->warehouses();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $head['title'] = "New Invoice";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['cat'] = $this->categories_model->category_list();
        $data['taxdetails'] = $this->common->taxdetail();
        $data['acc_list'] = $this->invocies->accountslist();
        
		

        $data['emp'] = $this->plugins->universal_api(69);

		

        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }

		// echo "<pre>";
		// print_r($data['warehouse']);
		// echo "</pre>";
		// exit();

		  


        if ($this->input->get('v2')=='true' or POSV == 2) {
			//echo 1; exit();
            $head['s_mode'] = false;
            $this->load->view('fixed/header-pos', $head);
            $this->load->view('pos/newinvoice_v2', $data);
        } else {
			//echo 2; exit();
            $head['s_mode'] = true;
            $this->load->view('fixed/header-pos', $head);
            $this->load->view('pos/newinvoice', $data);
        }
        $this->load->view('fixed/footer-pos');
    }


    public function draft()
    {
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);

        $this->load->model('categories_model');
        $data['gateway'] = $this->invocies->gateway_list('Yes');
        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $data['title'] = "New Invoice";
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $data['invoice'] = $this->invocies->draft_details($tid, $this->limited);
		
        if ($data['invoice']['invoice_number']) $data['products'] = $this->invocies->draft_products($tid);
		//print_r($data['products']);exit();
        $head['title'] = "Edit Invoice #$tid";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->invocies->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['cat'] = $this->categories_model->category_list();
        //$data['lastinvoice'] = $this->invocies->lastinvoice()+1;
		$data['lastinvoice'] = $this->invocies->lastinvoice();
        $data['taxdetails'] = $this->common->taxdetail();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
		$data['acc_list'] = $this->invocies->accountslist();

		// echo "<pre>";
		// print_r($data['products']);
		// echo "</pre>";
		// exit();

        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }

        if ($this->input->get('v2') or POSV == 2) {
            $head['s_mode'] = false;
            $this->load->view('fixed/header-pos', $head);
            if ($data['invoice']['invoice_number']) $this->load->view('pos/draft', $data);
        } else {
            $head['s_mode'] = false;
            $this->load->view('fixed/header-pos', $head);
            if ($data['invoice']['invoice_number']) $this->load->view('pos/draft', $data);
        }
        $this->load->view('fixed/footer-pos');
    }

	

	


    //edit invoice
    public function edit()
    {
        if (!$this->registerlog->check($this->aauth->get_user()->id)) {
            redirect('register/create');
        }
        // if (!$this->aauth->premission(13)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $head['s_mode'] = false;
        $this->load->model('categories_model');
        $data['gateway'] = $this->invocies->gateway_list('Yes');
        $tid = $this->input->get('id');
        $data['id'] = $tid;

        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        if ($data['invoice']['id']) $data['products'] = $this->invocies->invoice_products($tid);
        $head['title'] = "Edit Invoice #$tid";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->invocies->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['cat'] = $this->categories_model->category_list();
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
        $this->load->view('fixed/header-pos', $head);
        if ($data['invoice']['id']) $this->load->view('pos/edit', $data);
        $this->load->view('fixed/footer-pos');

    }

    //invoices list
    public function index()
    {
        $data['permissions'] = load_permissions('Sales','POS Sales','Manage Invoices');
        $head['title'] = "Manage Invoices";
        $head['usernm'] = $this->aauth->get_user()->username; 
        $this->load->model('invoices_model');       
        $condition = " WHERE i_class = 1";
        $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_invoices','invoice_date','total',$condition);
        $this->load->view('fixed/header', $head);
        $this->load->view('pos/invoices', $data);
        $this->load->view('fixed/footer');
    }

        //invoices list
    public function extended()
    {
        $data['permissions'] = load_permissions('Data and Reports','Statements','Product Sales Reports');
        $head['title'] = "Manage Invoices";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('pos/extended',$data);
        $this->load->view('fixed/footer');
    }

    //action
    // public function action()
    // {

   
    //     $v2 = $this->input->get('v2');
    //     $ptype = $this->input->post('type');
    //     $coupon = $this->input->post('coupon');
    //     $notes = $this->input->post('notes', true);
    //     $draft_id = $this->input->post('draft_id');
    //     $coupon_amount = 0;
    //     $coupon_n = '';
    //     $customer_id = $this->input->post('customer_id');
    //     $invocieno = $this->input->post('invocieno');
    //     $invoicedate = $this->input->post('invoicedate');
    //     $invocieduedate = $this->input->post('invocieduedate');
    //     $tax = $this->input->post('tax_handle');

    //     $total_tax = 0;

    //     $discountFormat = $this->input->post('discountFormat');
    //     $pterms = $this->input->post('pterms');
    //     $currency = $this->input->post('mcurrency');
    //     $total_discount = rev_amountExchange_s($this->input->post('after_disc'), $currency, $this->aauth->get_user()->loc);
    //     $disc_val = numberClean($this->input->post('disc_val'));
    //     $ship_taxtype = $this->input->post('ship_taxtype');
    //     $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
    //     $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
    //     $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
    //     if ($ship_taxtype == 'incl') @$shipping = $shipping - $shipping_tax;
    //     $refer = $this->input->post('refer', true);
    //     $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
    //     $st_c = 0;
    //     $print_now = $this->input->post('printnow');
    //     $account = $this->input->post('account');
    //     $this->load->model('plugins_model', 'plugins');
    //     $empl_e = $this->plugins->universal_api(69);
    //     if ($empl_e['key1']) {
    //         $emp = $this->input->post('employee');
    //     } else {
    //         $emp = $this->aauth->get_user()->id;
    //     }


    //     //Payment button click section  - pay now
    //     if ($ptype == 4) {
           
    //         $p_amount = rev_amountExchange_s($this->input->post('p_amount'), $currency, $this->aauth->get_user()->loc);
    //         $pmethod = $this->input->post('p_method');

    //         $c_amt = $p_amount - $total;
    //         if ($c_amt == 0.00) {
    //             $status = 'Paid';
    //             $pamnt = $total;
    //         } elseif ($c_amt < 0.00) {
    //             $status = 'Partial';
    //             if ($p_amount == 0.00) $status = 'Due';
    //             $pamnt = $p_amount;
    //             $c_amt = 0;
    //         } else {
    //             $status = 'Paid';
    //             $pamnt = $total;
    //             $roundoff = $this->custom->api_config(4);
    //             if ($roundoff['other']) $pamnt = round($total, $roundoff['active'], constant($roundoff['other']));
    //         }
    //         $i = 0;
    //         if ($discountFormat == '0') {
    //             $discstatus = 0;
    //         } else {
    //             $discstatus = 1;
    //         }
    //         if ($customer_id == 0) {
    //             echo json_encode(array('status' => 'Error', 'message' =>
    //                 $this->lang->line('Please add a new client')));
    //             exit;
    //         }
    //         $this->db->trans_start();
    //         //products
    //         $transok = true;
    //         $this->load->library("Common");
    //         //Invoice Data
    //         $bill_date = datefordatabase($invoicedate);
    //         $bill_due_date = datefordatabase($invocieduedate);
    //         $promo_flag = false;
    //         if ($coupon) {
    //             $this->db->select('*');
    //             $this->db->from('cberp_promo');
    //             $this->db->where('code', $coupon);
    //             $query = $this->db->get();
    //             $result_c = $query->row_array();
    //             if ($result_c['active'] == 0 && $result_c['available'] > 0) {
    //                 $promo_flag = true;
    //                 $amount = $result_c['amount'];
    //                 $notes .= '-' . $this->input->post('i_coupon');
    //                 $total_discount += $amount;
    //             }
    //         }

    //         $this->db->select('tid');
    //         $this->db->from('cberp_invoices');
    //         $this->db->order_by('id', 'DESC');
    //         $this->db->limit(1);
    //         $this->db->where('tid', $invocieno);
    //         $this->db->where('regular_pos_flag', 1); //regular_pos_flag
    //         $query = $this->db->get();
    //         if(@$query->row()->tid){
    //             $this->db->select('tid');
    //             $this->db->from('cberp_invoices');
    //             $this->db->order_by('id', 'DESC');
    //             $this->db->limit(1);
    //             $this->db->where('regular_pos_flag', 1);
    //             $query = $this->db->get();
    //             $invocieno=$query->row()->tid+1;
    //         }

    //         $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'total' => $total, 'pmethod' => $pmethod, 'notes' => $notes, 'status' => $status, 'csd' => $customer_id, 'eid' => $emp, 'pamnt' => 0, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency, 'i_class' => 1, 'loc' => $this->aauth->get_user()->loc);

    //         if ($this->db->insert('cberp_invoices', $data)) 
    //         {
                
    //             $invocieno_n = $invocieno;
    //             $invocieno2 = $invocieno;
    //             $invocieno = $this->db->insert_id();
            

    //             $pid = $this->input->post('pid');
    //             $productlist = array();
    //             $prodindex = 0;
    //             $itc = 0;
    //             $product_id = $this->input->post('pid');
    //             $product_name1 = $this->input->post('product_name', true);
    //             $product_qty = $this->input->post('product_qty');
    //             $product_price = $this->input->post('product_price');
    //             $product_tax = $this->input->post('product_tax');
    //             $product_discount = $this->input->post('product_discount');
    //             $product_subtotal = $this->input->post('product_subtotal');
    //             $ptotal_tax = $this->input->post('taxa');
    //             $ptotal_disc = $this->input->post('disca');
    //             $product_des = $this->input->post('product_description', true);
    //             $product_unit = $this->input->post('unit');
    //             $product_hsn = $this->input->post('hsn', true);
    //             $product_alert = $this->input->post('alert');
    //             $product_serial = $this->input->post('serial');
    //             if (is_array($pid)) {
    //                 foreach ($pid as $key => $value) {
    //                     $total_discount += numberClean(@$ptotal_disc[$key]);
    //                     $total_tax += numberClean($ptotal_tax[$key]);

    //                     $data = array(
    //                         'tid' => $invocieno,
    //                         'pid' => $product_id[$key],
    //                         'product' => $product_name1[$key],
    //                         'code' => $product_hsn[$key],
    //                         'qty' => numberClean($product_qty[$key]),
    //                         'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
    //                         'tax' => numberClean($product_tax[$key]),
    //                         'discount' => numberClean($product_discount[$key]),
    //                         'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
    //                         'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
    //                         'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
    //                         'product_des' => @$product_des[$key],
    //                         'i_class' => 1,
    //                         'unit' => $product_unit[$key],
    //                         'serial' => $product_serial[$key] ?? null
    //                     );

    //                     $flag = true;
    //                     $productlist[$prodindex] = $data;
    //                     $i++;
    //                     $prodindex++;

    //                     $amt = numberClean($product_qty[$key]);

    //                     if ($ptype AND $product_id[$key] > 0 and $this->common->zero_stock()) {

    //                         if ((numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0) {
    //                             echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
    //                             $transok = false;
    //                             $st_c = 1;
    //                         } else {
    //                             $this->db->set('qty', "qty-$amt", FALSE);
    //                             $this->db->where('pid', $product_id[$key]);
    //                             $this->db->update('cberp_products');
    //                         }
    //                     }


    //                     $itc += $amt;


    //                 }

    //             }
    //             if ($prodindex > 0) {
    //                 $this->db->insert_batch('cberp_invoice_items', $productlist);
        
    //                 $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
    //                 $this->db->where('id', $invocieno);
    //                 $this->db->update('cberp_invoices');
            
    //                 if (is_array($product_serial) AND count($product_serial) > 0) {
    //                     $this->db->set('status', 1);
    //                     $this->db->where_in('serial', $product_serial);
    //                     $this->db->update('cberp_product_serials');
    //                 }
    //             }
    //             else {
    //                 echo json_encode(array('status' => 'Error', 'message' =>
    //                     "Please choose product from product list. Go to Item manager section if you have not added the products."));
    //                 $transok = false;
    //             }
            
    //             $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
    //             $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
    //             $link = base_url('billing/view?id=' . $invocieno);
              

    //             if ($transok) {
    //                 $this->load->library("Printer");
    //                 $printer = $this->printer->check($this->aauth->get_user()->loc);
    //                 $p_tid = 'thermal_p';
    //                 if (@$printer['val2'] == 'server') $p_tid = 'thermal_server';
            
    //                 echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Invoice Success') . " <a target='_blank' href='thermal_pdf?id=$invocieno' class='btn btn-blue btn-sm breaklink'><span class='fa fa-ticket' aria-hidden='true'></span> PDF  </a> &nbsp; &nbsp;   <a id='$p_tid' data-ptid='$invocieno' data-url='" . @$printer['val3'] . "'  class='btn btn-info btn-sm white breaklink'><span class='fa fa-ticket' aria-hidden='true'></span> " . $this->lang->line('Thermal Printer') . "  </a> &nbsp; &nbsp;<a href='#' class='btn btn-reddit btn-sm breaklink print_image' id='print_image' data-inid='$invocieno'><span class='fa fa-window-restore' aria-hidden='true'></span></a> &nbsp; &nbsp; <a target='_blank' href='printinvoice?id=$invocieno' class='btn btn-blue btn-sm breaklink'><span class='fa fa-print' aria-hidden='true'></span> A4  </a> &nbsp; &nbsp; <a href='view?id=$invocieno' class='btn btn-purple btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> " . $this->lang->line('View') . "  </a> &nbsp; &nbsp; <a href='$link' class='btn btn-blue-grey btn-sm breaklink'><span class='fa fa-globe' aria-hidden='true'></span> " . $this->lang->line('Public View') . " </a> &nbsp;<a href='create?v2=$v2' class='btn btn-flickr btn-sm breaklink'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create') . "  </a>"));                
    //             }
    //             $this->load->model('billing_model', 'billing');
    //             $tnote = '#' . $invocieno_n . '-' . $pmethod;
    //             switch ($pmethod) {
    //                 case 'Cash' :
    //                     $r_amt1 = $pamnt;
    //                     $r_amt2 = 0;
    //                     $r_amt3 = 0;
    //                     break;
    //                 case 'Card Swipe' :
    //                     $r_amt1 = 0;
    //                     $r_amt2 = $pamnt;
    //                     $r_amt3 = 0;
    //                     break;
    //                 case 'Bank' :
    //                     $r_amt1 = 0;
    //                     $r_amt2 = 0;
    //                     $r_amt3 = $pamnt;
    //                     break;
    //             }
    //             $d_trans = $this->plugins->universal_api(69);
                
    //             if ($d_trans['key2']) {
    //                     $t_data = array(
    //                     'type' => 'Income',
    //                     'cat' => 'Sales',
    //                     'payerid' => $customer_id,
    //                     'method' => $pmethod,
    //                     'date' => $bill_date,
    //                     'eid' =>$emp,
    //                     'tid' => $invocieno,
    //                     'loc' =>$this->aauth->get_user()->loc
    //                 );
            
    //                 $dual = $this->custom->api_config(65);
    //                 $this->db->select('holder');
    //                 $this->db->from('cberp_accounts');
    //                 $this->db->where('id', $dual['key2']);
    //                 $query = $this->db->get();
    //                 $account_d = $query->row_array();
    //                 $t_data['credit'] = 0;
    //                 $t_data['debit'] = $total;
    //                 $t_data['type'] = 'Expense';
    //                 $t_data['acid'] = $dual['key2'];
    //                 $t_data['account'] = $account_d['holder'];
    //                 $t_data['note'] = 'Debit ' . $tnote;
            
    //                 $this->db->insert('cberp_transactions', $t_data);
    //                 //account update
    //                 $this->db->set('lastbal', "lastbal-$total", FALSE);
    //                 $this->db->where('id', $dual['key2']);
    //                 $this->db->update('cberp_accounts');
                    
            
    //             }

    //             if ($pamnt > 0) 
    //             {
    //                 $this->billing->paynow($invocieno, $pamnt, $tnote, $pmethod, $this->aauth->get_user()->loc, $bill_date, $account);
    //             }
    //             $this->registerlog->update($this->aauth->get_user()->id, $r_amt1, $r_amt2, $r_amt3, 0, $c_amt);


    //             if ($promo_flag) {
    //                 $cqty = $result_c['available'] - 1;
    //                 if ($cqty > 0) {
    //                     $data = array('available' => $cqty);
    //                 } else {
    //                     $data = array('active' => 1, 'available' => $cqty);
    //                 }
    //                 $amount = $result_c['amount'];
    //                 $this->db->set($data);
    //                 $this->db->where('id', $result_c['id']);
    //                 $this->db->update('cberp_promo');
            
    //                 if ($result_c['reflect'] > 0) {
    //                     $data = array(
    //                         'payerid' => 0,
    //                         'payer' => $this->lang->line('Coupon') . '-' . $result_c['code'],
    //                         'acid' => $result_c['reflect'],
    //                         'account' => 'Promo',
    //                         'date' => date('Y-m-d'),
    //                         'debit' => 0,
    //                         'credit' => $amount,
    //                         'type' => 'Income',
    //                         'cat' => $this->lang->line('Coupon'),
    //                         'method' => 'Transfer',
    //                         'eid' => $this->aauth->get_user()->id,
    //                         'note' => $this->lang->line('Coupon') . ' ' . $result_c['code'],
    //                         'loc' => $this->aauth->get_user()->loc
    //                     );
    //                     $this->db->set('lastbal', "lastbal+$amount", FALSE);
    //                     $this->db->where('id', $result_c['reflect']);
    //                     $this->db->update('cberp_accounts');
    //                     $this->db->insert('cberp_transactions', $data);
    //                 }
    //             }
               
    //         } 
    //         else {
    //             echo json_encode(array('status' => 'Error', 'message' =>
    //                 "Invalid Entry!"));
    //             $transok = false;
    //         }
    //         if ($transok) {
    //             $this->db->trans_complete();
    //         } else {
    //             $this->db->trans_rollback();
    //         }
    //     } 
        
        
        
        
    //     elseif ($ptype == 2) {
    //         ///card section
    //         $discountFormat = $this->input->post('discountFormat');
    //         $pterms = $this->input->post('pterms');
    //         $currency = $this->input->post('mcurrency');
    //         $i = 0;
    //         if ($discountFormat == '0') {
    //             $discstatus = 0;
    //         } else {
    //             $discstatus = 1;
    //         }
    //         if ($customer_id == 0) {
    //             echo json_encode(array('status' => 'Error', 'message' =>
    //                 $this->lang->line('Please add a new client')));
    //             exit;
    //         }
    //         $this->db->trans_start();
    //         //products
    //         $transok = true;
    //         //Invoice Data
    //         $bill_date = datefordatabase($invoicedate);
    //         $bill_due_date = datefordatabase($invocieduedate);

    //         $promo_flag = false;
    //         if ($coupon) {
    //             $this->db->select('*');
    //             $this->db->from('cberp_promo');
    //             $this->db->where('code', $coupon);
    //             $query = $this->db->get();
    //             $result_c = $query->row_array();
    //             if ($result_c['active'] == 0 && $result_c['available'] > 0) {
    //                 $promo_flag = true;
    //                 $amount = $result_c['amount'];
    //                 $notes .= '-' . $this->input->post('i_coupon');
    //                 $total_discount += $amount;
    //             }
    //         }
    //         $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $emp, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency, 'i_class' => 1, 'loc' => $this->aauth->get_user()->loc);
    //         $invoice_true = false;
    //         if ($this->db->insert('cberp_invoices', $data)) {
    //             $invoice_true = true;
    //             $tid = $invocieno;
    //             $invocieno2 = $invocieno;
    //             $invocieno = $this->db->insert_id();
    //             $pid = $this->input->post('pid');
    //             $productlist = array();
    //             $prodindex = 0;
    //             $itc = 0;
    //             $product_id = $this->input->post('pid');
    //             $product_name1 = $this->input->post('product_name', true);
    //             $product_qty = $this->input->post('product_qty');
    //             $product_price = $this->input->post('product_price');
    //             $product_tax = $this->input->post('product_tax');
    //             $product_discount = $this->input->post('product_discount');
    //             $product_subtotal = $this->input->post('product_subtotal');
    //             $ptotal_tax = $this->input->post('taxa');
    //             $ptotal_disc = $this->input->post('disca');
    //             $product_des = $this->input->post('product_description', true);
    //             $product_unit = $this->input->post('unit');
    //             $product_hsn = $this->input->post('hsn');
    //             $product_alert = $this->input->post('alert');
    //             $product_serial = $this->input->post('serial');
    //             foreach ($pid as $key => $value) {
    //                 $total_discount += numberClean(@$ptotal_disc[$key]);
    //                 $total_tax += numberClean($ptotal_tax[$key]);
    //                 $data = array(
    //                     'tid' => $invocieno,
    //                     'pid' => $product_id[$key],
    //                     'product' => $product_name1[$key],
    //                     'code' => $product_hsn[$key],
    //                     'qty' => numberClean($product_qty[$key]),
    //                     'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
    //                     'tax' => numberClean($product_tax[$key]),
    //                     'discount' => numberClean($product_discount[$key]),
    //                     'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
    //                     'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
    //                     'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
    //                     'product_des' => $product_des[$key],
    //                     'i_class' => 1,
    //                     'unit' => $product_unit[$key],
    //                     'serial' => $product_serial[$key]
    //                 );
    //                 $productlist[$prodindex] = $data;
    //                 $i++;
    //                 $prodindex++;
    //                 $amt = numberClean($product_qty[$key]);
    //                 if ($product_id[$key] > 0 and $this->common->zero_stock()) {

    //                 	if($draft_id){
	// 						$this->db->set('qty', "qty-$amt", FALSE);
	// 						$this->db->where('pid', $product_id[$key]);
	// 						$this->db->update('cberp_products');
	// 					} elseif ((numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0) {
    //                         echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
    //                         $transok = false;
    //                         $st_c = 1;
    //                     } else {
    //                         $this->db->set('qty', "qty-$amt", FALSE);
    //                         $this->db->where('pid', $product_id[$key]);
    //                         $this->db->update('cberp_products');
    //                     }
    //                 }
    //                 $itc += $amt;
    //             }
    //             if ($prodindex > 0) {
    //                 $this->db->insert_batch('cberp_invoice_items', $productlist);
    //                 if (count($product_serial) > 0) {
    //                     $this->db->set('status', 1);
    //                     $this->db->where_in('serial', $product_serial);
    //                     $this->db->update('cberp_product_serials');
    //                 }
    //             } else {
    //                 echo json_encode(array('status' => 'Error', 'message' =>
    //                     "Please choose product from product list. Go to Item manager section if you have not added the products."));
    //                 $transok = false;
    //             }
    //             $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
    //             $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
    //             if ($promo_flag) {
    //                 $cqty = $result_c['available'] - 1;
    //                 if ($cqty > 0) {
    //                     $data = array('available' => $cqty);
    //                 } else {
    //                     $data = array('active' => 1, 'available' => $cqty);
    //                 }

    //                 $this->db->set($data);
    //                 $this->db->where('id', $result_c['id']);
    //                 $this->db->update('cberp_promo');

    //                 if ($result_c['reflect'] > 0) {
    //                     $data = array(
    //                         'payerid' => 0,
    //                         'payer' => $this->lang->line('Coupon') . '-' . $result_c['code'],
    //                         'acid' => $result_c['reflect'],
    //                         'account' => 'Promo',
    //                         'date' => date('Y-m-d'),
    //                         'debit' => 0,
    //                         'credit' => $amount,
    //                         'type' => 'Income',
    //                         'cat' => $this->lang->line('Coupon'),
    //                         'method' => 'Transfer',
    //                         'eid' => $this->aauth->get_user()->id,
    //                         'note' => $this->lang->line('Coupon') . ' ' . $this->lang->line('Delete') . ' ' . $this->lang->line('Qty') . '-' . $result_c['available'],
    //                         'loc' => $this->aauth->get_user()->loc
    //                     );
    //                     $this->db->set('lastbal', "lastbal+$amount", FALSE);
    //                     $this->db->where('id', $result_c['reflect']);
    //                     $this->db->update('cberp_accounts');
    //                     $this->db->insert('cberp_transactions', $data);
    //                 }
    //             }

    //         } else {
    //             echo json_encode(array('status' => 'Error', 'message' =>
    //                 "Invalid Entry!"));
    //             $transok = false;
    //         }


    //         //card process
    //         //card process
    //         if ($invoice_true) {
    //             $gateway = $this->input->post('gateway', true);
    //             setcookie("pos_set", base_url('pos_invoices/view?id=' . $invocieno), time() + 30 * 24 * 60 * 60, '/');
    //             $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
    //             $quckpay = base_url() . "billing/card?id=$invocieno&itype=inv&token=$validtoken&gid=$gateway";

    //             if ($transok) echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Invoice Success') . " <script type='text/javascript' >window.location.replace('$quckpay');</script><a target='_blank' href='$quckpay' class='btn btn-blue btn-sm breaklink'><span class='icon-printer' aria-hidden='true'></span> " . $this->lang->line('Pay') . "  </a> &nbsp; &nbsp;    <a target='_blank' href='thermal_pdf?id=$invocieno' class='btn btn-blue btn-sm breaklink'><span class='icon-printer' aria-hidden='true'></span> " . $this->lang->line('Print') . "  </a> &nbsp; &nbsp;    &nbsp; &nbsp; <a href='view?id=$invocieno' class='btn btn-purple btn-sm breaklink'><span class='icon-file-text2' aria-hidden='true'></span> " . $this->lang->line('View') . "  </a> &nbsp; &nbsp; <a href='$link' class='btn btn-blue-grey btn-sm'><span class='icon-earth' aria-hidden='true'></span> " . $this->lang->line('Public View') . " </a> <a href='create?v2=$v2' class='btn btn-amber btn-sm breaklink'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create') . "  </a>"));
    //         }

    //         if ($transok) {
    //             $this->db->trans_complete();
    //         } else {
    //             $this->db->trans_rollback();
    //         }
    //     } else {
    //     //draft
    //         $p_amount = 0;
    //         $pmethod = $this->input->post('p_method', true);

    //         $c_amt = @$p_amount - $total;
    //         if ($c_amt == 0.00) {
    //             $status = 'Paid';
    //             $pamnt = $total;
    //         } elseif ($c_amt < 0.00) {
    //             $status = 'Partial';
    //             $pamnt = $p_amount;


    //         } else {
    //             $status = 'Paid';
    //             $pamnt = $total;
    //         }


    //         $i = 0;
    //         if ($discountFormat == '0') {
    //             $discstatus = 0;
    //         } else {
    //             $discstatus = 1;
    //         }

    //         if ($customer_id == 0) {
    //             echo json_encode(array('status' => 'Error', 'message' =>
    //                 $this->lang->line('Please add a new client')));
    //             exit;
    //         }
    //         $this->db->trans_start();
    //         //products
    //         $transok = true;
    //         //Invoice Data
    //         $bill_date = datefordatabase($invoicedate);
    //         $bill_due_date = datefordatabase($invocieduedate);
    //         $promo_flag = false;
    //         $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'total' => $total, 'pmethod' => $pmethod, 'notes' => $notes, 'status' => $status, 'csd' => $customer_id, 'eid' => $this->aauth->get_user()->id, 'pamnt' => 0, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency, 'i_class' => 1, 'loc' => $this->aauth->get_user()->loc);
    //         if ($this->db->insert('cberp_draft', $data)) {
    //             $invocieno2 = $invocieno;
    //             $invocieno = $this->db->insert_id();
    //             $pid = $this->input->post('pid');
    //             $productlist = array();
    //             $prodindex = 0;
    //             $itc = 0;

    //             $product_id = $this->input->post('pid');
    //             $product_name1 = $this->input->post('product_name', true);
    //             $product_qty = $this->input->post('product_qty');
    //             $product_price = $this->input->post('product_price');
    //             $product_tax = $this->input->post('product_tax');
    //             $product_discount = $this->input->post('product_discount');
    //             $product_subtotal = $this->input->post('product_subtotal');
    //             $ptotal_tax = $this->input->post('taxa');
    //             $ptotal_disc = $this->input->post('disca');
    //             $product_des = $this->input->post('product_description', true);
    //             $product_unit = $this->input->post('unit');
    //             $product_hsn = $this->input->post('hsn');


    //             foreach ($pid as $key => $value) {


    //                 $total_discount += numberClean(@$ptotal_disc[$key]);
    //                 $total_tax += numberClean($ptotal_tax[$key]);

    //                 $data = array(
    //                     'tid' => $invocieno,
    //                     'pid' => $product_id[$key],
    //                     'product' => $product_name1[$key],
    //                     'code' => $product_hsn[$key],
    //                     'qty' => numberClean($product_qty[$key]),
    //                     'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
    //                     'tax' => numberClean($product_tax[$key]),
    //                     'discount' => numberClean($product_discount[$key]),
    //                     'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
    //                     'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
    //                     'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
    //                     'product_des' => $product_des[$key] ?? null,
    //                     'i_class' => 1,
    //                     'unit' => $product_unit[$key]
    //                 );

    //                 $flag = true;
    //                 $productlist[$prodindex] = $data;
    //                 $i++;
    //                 $prodindex++;
    //                 $amt = numberClean($product_qty[$key]);
    //                 $itc += $amt;
    //             }

    //             if ($prodindex > 0) {
    //                 $this->db->insert_batch('cberp_draft_items', $productlist);
    //                 $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
    //                 $this->db->where('id', $invocieno);
    //                 $this->db->update('cberp_draft');

    //             } else {
    //                 echo json_encode(array('status' => 'Error', 'message' =>
    //                     "Please choose product from product list. Go to Item manager section if you have not added the products."));
    //                 $transok = false;
    //             }

    //             $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
    //             $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
    //             if ($transok) echo json_encode(array('status' => 'Success', 'message' =>
    //                 $this->lang->line('Draft Success') . " <a href='create' class='btn btn-info btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('New') . "  </a>"));

    //         } else {
    //             echo json_encode(array('status' => 'Error', 'message' =>
    //                 "Invalid Entry!"));
    //             $transok = false;
    //         }
    //         if ($transok) {
    //             $this->db->trans_complete();
    //         } else {
    //             $this->db->trans_rollback();
    //         }
    //     }
    //     if ($transok) {
    //         $this->db->from('univarsal_api');
    //         $this->db->where('univarsal_api.id', 56);
    //         $query = $this->db->get();
    //         $auto = $query->row_array();
    //         if ($auto['key1'] == 1) {
    //             $this->db->select('name,email');
    //             $this->db->from('cberp_customers');
    //             $this->db->where('id', $customer_id);
    //             $query = $this->db->get();
    //             $customer = $query->row_array();

    //             $this->load->model('communication_model');
    //             $invoice_mail = $this->send_invoice_auto($invocieno, $invocieno2, $bill_date, $total, $currency);
    //             $attachmenttrue = false;
    //             $attachment = '';
    //             $this->communication_model->send_corn_email($customer['email'], $customer['name'], $invoice_mail['subject'], $invoice_mail['message'], $attachmenttrue, $attachment);

    //         }

    //         if ($auto['key2'] == 1) {
    //             $this->db->select('name,phone');
    //             $this->db->from('cberp_customers');
    //             $this->db->where('id', $customer_id);
    //             $query = $this->db->get();
    //             $customer = $query->row_array();

    //             $this->load->model('plugins_model', 'plugins');
    //             $invoice_sms = $this->send_sms_auto($invocieno, $invocieno2, $bill_date, $total, $currency);

    //             $mobile = $customer['phone'];
    //             $text_message = $invoice_sms['message'];
    //             $this->load->model('sms_model', 'sms');
    //             $this->sms->send_sms($mobile, $text_message, false);


    //         }
    //         if($draft_id>0){
    //              $this->db->delete('cberp_draft', array('id' => $draft_id));
    //             $this->db->delete('cberp_draft_items', array('tid' => $draft_id));
    //         }
    //     }
    //     //profit calculation
    //     $t_profit = 0;
    //     $this->db->select('cberp_invoice_items.pid, cberp_invoice_items.price, cberp_invoice_items.qty, cberp_products.product_cost');
    //     $this->db->from('cberp_invoice_items');
    //     $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
    //     $this->db->where('cberp_invoice_items.tid', $invocieno);
    //     $query = $this->db->get();
    //     $pids = $query->result_array();
    //     foreach ($pids as $profit) {
    //         $t_cost = $profit['product_cost'] * $profit['qty'];
    //         $s_cost = $profit['price'] * $profit['qty'];

    //         $t_profit += $s_cost - $t_cost;
    //     }
    //     $data = array('type' => 9, 'rid' => $invocieno, 'col1' => $t_profit, 'd_date' => $bill_date);

    //     $this->db->insert('cberp_metadata', $data);

    //     if ($print_now) {
    //         $print = $this->thermal_print($invocieno, false, false);
    //     }
    // }


	//action
    public function action()
{   
        $v2 = $this->input->get('v2');
        $ptype = $this->input->post('type');
        $coupon = $this->input->post('coupon');
        $notes = $this->input->post('notes', true);
        $draft_id = $this->input->post('draft_id');
        $coupon_amount = 0;
        $coupon_n = '';
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $tax = $this->input->post('tax_handle');

        $total_tax = 0;

        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $currency = $this->input->post('mcurrency');
        $total_discount = rev_amountExchange_s($this->input->post('after_disc'), $currency, $this->aauth->get_user()->loc);
        $disc_val = numberClean($this->input->post('disc_val'));
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') @$shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $st_c = 0;
        $print_now = $this->input->post('printnow');
        $account = $this->input->post('account');
        $this->load->model('plugins_model', 'plugins');
        $empl_e = $this->plugins->universal_api(69);
        if ($empl_e['key1']) {
            $emp = $this->input->post('employee');
        } else {
            $emp = $this->aauth->get_user()->id;
        }


        //Payment button click section  - pay now
        if ($ptype == 4) {
           
            $p_amount = rev_amountExchange_s($this->input->post('p_amount'), $currency, $this->aauth->get_user()->loc);
            $pmethod = $this->input->post('p_method');

            $c_amt = $p_amount - $total;
            if ($c_amt == 0.00) {
                $status = 'Paid';
                $pamnt = $total;
            } elseif ($c_amt < 0.00) {
                $status = 'Partial';
                if ($p_amount == 0.00) $status = 'Due';
                $pamnt = $p_amount;
                $c_amt = 0;
            } else {
                $status = 'Paid';
                $pamnt = $total;
                $roundoff = $this->custom->api_config(4);
                if ($roundoff['other']) $pamnt = round($total, $roundoff['active'], constant($roundoff['other']));
            }

            $i = 0;
            if ($discountFormat == '0') {
                $discstatus = 0;
            } else {
                $discstatus = 1;
            }

            if ($customer_id == 0) {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('Please add a new client')));
                exit;
            }


            $this->db->trans_start();
            //products
            $transok = true;

            $this->load->library("Common");
            //Invoice Data
            $bill_date = datefordatabase($invoicedate);
            $bill_due_date = datefordatabase($invocieduedate);
            $promo_flag = false;

            if ($coupon) {
                $this->db->select('*');
                $this->db->from('cberp_promo');
                $this->db->where('code', $coupon);
                $query = $this->db->get();
                $result_c = $query->row_array();
                if ($result_c['active'] == 0 && $result_c['available'] > 0) {
                    $promo_flag = true;
                    $amount = $result_c['amount'];
                    $notes .= '-' . $this->input->post('i_coupon');
                    $total_discount += $amount;
                }
            }

            if (!empty($draft_id)){//update draft existing 

                

                $data = array('invoice_number' => $draft_id, 'invoice_date' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'total' => $total, 'payment_method' => $pmethod, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $this->aauth->get_user()->id, 'paid_amount' => 0, 'tax_status' => $tax, 'discount_status' => $discstatus, 'format_discount' => $discountFormat, 'reference' => $refer, 'payment_terms' => $pterms, 'multi' => $currency, 'i_class' => 1, 'loc' => $this->aauth->get_user()->loc);

                    

                            $this->db->where('invoice_number', $draft_id);
                           $updated = $this->db->update('cberp_invoices', $data);

                        if ($updated) {

                            $invocieno2 = $draft_id;
                            $invocieno = $draft_id;
                            $invocieno_n = $draft_id;

                            $pid = $this->input->post('pid');
                            $productlist = array();
                            $prodindex = 0;
                            $itc = 0;   

                            $product_id = $this->input->post('pid');
                            $product_name1 = $this->input->post('product_name', true);
                            $product_qty = $this->input->post('product_qty');
                            $product_price = $this->input->post('product_price');
                            $product_tax = $this->input->post('product_tax');
                            $product_discount = $this->input->post('product_discount');
                            $product_subtotal = $this->input->post('product_subtotal');
                            $ptotal_tax = $this->input->post('taxa');
                            $ptotal_disc = $this->input->post('disca');
                            $product_des = $this->input->post('product_description', true);
                            $product_unit = $this->input->post('unit');
                            $product_hsn = $this->input->post('hsn');

                            //echo json_encode($pid);exit();                
                                                    


                            foreach ($pid as $key => $value) {

                                $total_discount += numberClean(@$ptotal_disc[$key]);
                                $total_tax += numberClean($ptotal_tax[$key]);

                                $data = array(
                                    'invoice_number' => $draft_id,
                                    'product_code' => $product_id[$key],
                                    'quantity' => numberClean($product_qty[$key]),
                                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                                    'tax' => numberClean($product_tax[$key]),
                                    'discount' => numberClean($product_discount[$key]),
                                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                                );

                                $flag = true;
                                $productlist[$prodindex] = $data;
                                $i++;
                                $prodindex++;
                                $amt = numberClean($product_qty[$key]);
                                $itc += $amt;

                                // Check if item already exists
                                $this->db->where('invoice_number', $draft_id);
                                $this->db->where('product_code', $product_id[$key]);
                                $query = $this->db->get('cberp_invoice_items');

                                if ($query->num_rows() > 0) {
                                    // Update existing item
                                    $this->db->where('invoice_number', $draft_id);
                                    $this->db->where('product_code', $product_id[$key]);
                                    $this->db->update('cberp_invoice_items', $data);
                                } else {
                                    // Insert new item
                                    $this->db->insert('cberp_invoice_items', $data);
                                }
                            }

                            if ($prodindex > 0) {
                                //$this->db->insert_batch('cberp_invoice_items', $productlist);                              

                                $this->db->set(array('total_discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                                $this->db->where('invoice_number', $draft_id);
                                $this->db->update('cberp_invoices');

                                //if (is_array($product_serial) AND count($product_serial) > 0) {
                                if (is_array($product_serial) && count(array_filter($product_serial)) > 0) {
                                    $this->db->set('status', 1);
                                    $this->db->where_in('serial', $product_serial);
                                    $this->db->update('cberp_product_serials');
                                }

                            } else {
                                echo json_encode(array('status' => 'Error', 'message' =>
                                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                                $transok = false;
                            }

                            $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
                            //echo $validtoken;exit();
                            $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
                            //$link = base_url('billing/view?id=' . $invocieno);
                      

                            if ($transok) {
                                $this->load->library("Printer");
                                $printer = $this->printer->check($this->aauth->get_user()->loc);
                                $p_tid = 'thermal_p';
                                if (@$printer['val2'] == 'server') $p_tid = 'thermal_server';
                        
                                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Invoice Success') . " <a target='_blank' href='thermal_pdf?id=$draft_id' class='btn btn-blue btn-sm breaklink'><span class='fa fa-ticket' aria-hidden='true'></span> PDF  </a> &nbsp; &nbsp;   <a id='$p_tid' data-ptid='$draft_id' data-url='" . @$printer['val3'] . "'  class='btn btn-info btn-sm white breaklink'><span class='fa fa-ticket' aria-hidden='true'></span> " . $this->lang->line('Thermal Printer') . "  </a> &nbsp; &nbsp;<a href='#' class='btn btn-reddit btn-sm breaklink print_image' id='print_image' data-inid='$draft_id'><span class='fa fa-window-restore' aria-hidden='true'></span></a> &nbsp; &nbsp; <a target='_blank' href='printinvoice?id=$draft_id' class='btn btn-blue btn-sm breaklink'><span class='fa fa-print' aria-hidden='true'></span> A4  </a> &nbsp; &nbsp; <a href='view?id=$draft_id' class='btn btn-purple btn-sm' target='_blank'><span class='fa fa-eye' aria-hidden='true'></span> " . $this->lang->line('View') . "  </a> &nbsp; &nbsp; <a href='$link' class='btn btn-blue-grey btn-sm breaklink' target='_blank'><span class='fa fa-globe' aria-hidden='true'></span> " . $this->lang->line('Public View') . " </a> &nbsp;<a href='create?v2=$v2' class='btn btn-flickr btn-sm breaklink'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create') . "  </a>"));                
                            
                            }

                            $this->load->model('billing_model', 'billing');
                            $tnote = '#' . $invocieno_n . '-' . $pmethod;
                            switch ($pmethod) {
                                case 'Cash' :
                                    $r_amt1 = $pamnt;
                                    $r_amt2 = 0;
                                    $r_amt3 = 0;
                                    break;
                                case 'Card Swipe' :
                                    $r_amt1 = 0;
                                    $r_amt2 = $pamnt;
                                    $r_amt3 = 0;
                                    break;
                                case 'Bank' :
                                    $r_amt1 = 0;
                                    $r_amt2 = 0;
                                    $r_amt3 = $pamnt;
                                    break;
                            }
                            $d_trans = $this->plugins->universal_api(69);

                            if ($d_trans['key2']) {
                                $t_data = array(
                                'type' => 'Income',
                                'cat' => 'Sales',
                                'payerid' => $customer_id,
                                'method' => $pmethod,
                                'date' => $bill_date,
                                'eid' =>$emp,
                                'tid' => $invocieno,
                                'loc' =>$this->aauth->get_user()->loc
                            );
                    
                            $dual = $this->custom->api_config(65);
                            $this->db->select('holder');
                            $this->db->from('cberp_accounts');
                            $this->db->where('id', $dual['key2']);
                            $query = $this->db->get();
                            $account_d = $query->row_array();
                            $t_data['credit'] = 0;
                            $t_data['debit'] = $total;
                            $t_data['type'] = 'Expense';
                            $t_data['acid'] = $dual['key2'];
                            $t_data['account'] = $account_d['holder'];
                            $t_data['note'] = 'Debit ' . $tnote;
                    
                            $this->db->insert('cberp_transactions', $t_data);
                            //account update
                            $this->db->set('lastbal', "lastbal-$total", FALSE);
                            $this->db->where('id', $dual['key2']);
                            $this->db->update('cberp_accounts');
                            
                    
                        }

                        if ($pamnt > 0) 
                        {
                            $this->billing->paynow($invocieno, $pamnt, $tnote, $pmethod, $this->aauth->get_user()->loc, $bill_date, $account);
                        }
                        $this->registerlog->update($this->aauth->get_user()->id, $r_amt1, $r_amt2, $r_amt3, 0, $c_amt);


                        if ($promo_flag) {
                            $cqty = $result_c['available'] - 1;
                            if ($cqty > 0) {
                                $data = array('available' => $cqty);
                            } else {
                                $data = array('active' => 1, 'available' => $cqty);
                            }
                            $amount = $result_c['amount'];
                            $this->db->set($data);
                            $this->db->where('id', $result_c['id']);
                            $this->db->update('cberp_promo');
                    
                            if ($result_c['reflect'] > 0) {
                                $data = array(
                                    'payerid' => 0,
                                    'payer' => $this->lang->line('Coupon') . '-' . $result_c['code'],
                                    'acid' => $result_c['reflect'],
                                    'account' => 'Promo',
                                    'date' => date('Y-m-d'),
                                    'debit' => 0,
                                    'credit' => $amount,
                                    'type' => 'Income',
                                    'cat' => $this->lang->line('Coupon'),
                                    'method' => 'Transfer',
                                    'eid' => $this->aauth->get_user()->id,
                                    'note' => $this->lang->line('Coupon') . ' ' . $result_c['code'],
                                    'loc' => $this->aauth->get_user()->loc
                                );
                                $this->db->set('lastbal', "lastbal+$amount", FALSE);
                                $this->db->where('id', $result_c['reflect']);
                                $this->db->update('cberp_accounts');
                                $this->db->insert('cberp_transactions', $data);
                            }
                        }

                        }else {
                            echo json_encode(array('status' => 'Error', 'message' =>
                                "Invalid Entry!"));
                            $transok = false;
                        }

            }else{//insert new invoice

                

                    $this->configurations = $this->session->userdata('configurations');
                    $prefix = $this->configurations['invoiceprefix']; 

                    $this->db->select('invoice_number');
                    $this->db->from('cberp_invoices');
                    $this->db->order_by('invoice_number', 'DESC');
                    $this->db->limit(1);
                    //$this->db->where('invoice_number', $invocieno);
                    //$this->db->where('regular_pos_flag', 1); //regular_pos_flag
                    $query = $this->db->get();

                    if(@$query->row()->invoice_number){
                        $this->db->select('invoice_number');
                        $this->db->from('cberp_invoices');
                        $this->db->order_by('invoice_number', 'DESC');
                        $this->db->limit(1);
                        //$this->db->where('regular_pos_flag', 1);
                        $query = $this->db->get();
                        //$invocieno=$query->row()->tid+1;

                        if ($query->num_rows() > 0) {
                            $last_invoice_number = $query->row()->invoice_number;
                            $parts = explode('/', $last_invoice_number);
                            $last_number = (int)end($parts); 
                            $next_number = $last_number + 1;
                            $invocieno = $prefix.$next_number;
                        } else {
                            $invocieno = $prefix.'1001';
                        }
                    }

                    $data = array('invoice_number' => $invocieno, 'invoice_date' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'total' => $total, 'payment_method' => $pmethod, 'notes' => $notes, 'status' => $status, 'customer_id' => $customer_id, 'employee_id' => $emp, 'paid_amount' => 0, 'tax_status' => $tax, 'discount_status' => $discstatus, 'format_discount' => $discountFormat, 'reference' => $refer, 'payment_terms' => $pterms, 'multi' => $currency, 'i_class' => 1, 'loc' => $this->aauth->get_user()->loc);

                    if ($this->db->insert('cberp_invoices', $data)) 
                    {
                        
                        $invocieno_n = $invocieno;
                        $invocieno2 = $invocieno;
                        $invocieno = $invocieno;
                    

                        $pid = $this->input->post('pid');
                        $productlist = array();
                        $prodindex = 0;
                        $itc = 0;
                        $product_id = $this->input->post('pid');
                        $product_name1 = $this->input->post('product_name', true);
                        $product_qty = $this->input->post('product_qty');
                        $product_price = $this->input->post('product_price');
                        $product_tax = $this->input->post('product_tax');
                        $product_discount = $this->input->post('product_discount');
                        $product_subtotal = $this->input->post('product_subtotal');
                        $ptotal_tax = $this->input->post('taxa');
                        $ptotal_disc = $this->input->post('disca');
                        $product_des = $this->input->post('product_description', true);
                        $product_unit = $this->input->post('unit');
                        $product_hsn = $this->input->post('hsn', true);
                        $product_alert = $this->input->post('alert');
                        $product_serial = $this->input->post('serial');
                        if (is_array($pid)) {
                            foreach ($pid as $key => $value) {
                                $total_discount += numberClean(@$ptotal_disc[$key]);
                                $total_tax += numberClean($ptotal_tax[$key]);

                                $data = array(
                                    'invoice_number' => $invocieno,
                                    //'pid' => $product_id[$key],
                                    'product_code' => $product_id[$key],
                                    //'product' => $product_name1[$key],
                                    //'code' => $product_hsn[$key],
                                    'quantity' => numberClean($product_qty[$key]),
                                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                                    'tax' => numberClean($product_tax[$key]),
                                    'discount' => numberClean($product_discount[$key]),
                                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                                    //'product_des' => @$product_des[$key],
                                    //'i_class' => 1,
                                    //'unit' => $product_unit[$key],
                                    //'serial' => $product_serial[$key] ?? null
                                );

                                $flag = true;
                                $productlist[$prodindex] = $data;
                                $i++;
                                $prodindex++;

                                $amt = numberClean($product_qty[$key]);

                                if ($ptype AND $product_id[$key] > 0 and $this->common->zero_stock()) {

                                    if ((numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0) {
                                        echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
                                        $transok = false;
                                        $st_c = 1;
                                    } else {
                                        // $this->db->set('qty', "qty-$amt", FALSE);
                                        // $this->db->where('pid', $product_id[$key]);
                                        // $this->db->update('cberp_products');

                                        $this->db->set('kg_quantity', "kg_quantity - $amt", FALSE);
                                        $this->db->where('product_code', $product_id[$key]);
                                        $this->db->update('cberp_products');
                                    }
                                }


                                $itc += $amt;


                            }

                        }
                        if ($prodindex > 0) {
                            $this->db->insert_batch('cberp_invoice_items', $productlist);
                
                            $this->db->set(
                                array(
                                    'total_discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 
                                    'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 
                                    //'items' => $itc
                                ));
                            $this->db->where('invoice_number', $invocieno);
                            $this->db->update('cberp_invoices');
                    
                            //if (is_array($product_serial) AND count($product_serial) > 0) {
                            if (is_array($product_serial) && count(array_filter($product_serial)) > 0) {
                                $this->db->set('status', 1);
                                $this->db->where_in('serial', $product_serial);
                                $this->db->update('cberp_product_serials');
                            }
                        }
                        else {
                            echo json_encode(array('status' => 'Error', 'message' =>
                                "Please choose product from product list. Go to Item manager section if you have not added the products."));
                            $transok = false;
                        }
                    
                        $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
                        //echo $validtoken;exit();
                        $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
                        //$link = base_url('billing/view?id=' . $invocieno);
                      

                        if ($transok) {
                            $this->load->library("Printer");
                            $printer = $this->printer->check($this->aauth->get_user()->loc);
                            $p_tid = 'thermal_p';
                            if (@$printer['val2'] == 'server') $p_tid = 'thermal_server';
                    
                            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Invoice Success') . " <a target='_blank' href='thermal_pdf?id=$invocieno' class='btn btn-blue btn-sm breaklink'><span class='fa fa-ticket' aria-hidden='true'></span> PDF  </a> &nbsp; &nbsp;   <a id='$p_tid' data-ptid='$invocieno' data-url='" . @$printer['val3'] . "'  class='btn btn-info btn-sm white breaklink'><span class='fa fa-ticket' aria-hidden='true'></span> " . $this->lang->line('Thermal Printer') . "  </a> &nbsp; &nbsp;<a href='#' class='btn btn-reddit btn-sm breaklink print_image' id='print_image' data-inid='$invocieno'><span class='fa fa-window-restore' aria-hidden='true'></span></a> &nbsp; &nbsp; <a target='_blank' href='printinvoice?id=$invocieno' class='btn btn-blue btn-sm breaklink'><span class='fa fa-print' aria-hidden='true'></span> A4  </a> &nbsp; &nbsp; <a href='view?id=$invocieno' class='btn btn-purple btn-sm' target='_blank'><span class='fa fa-eye' aria-hidden='true'></span> " . $this->lang->line('View') . "  </a> &nbsp; &nbsp; <a href='$link' class='btn btn-blue-grey btn-sm breaklink' target='_blank'><span class='fa fa-globe' aria-hidden='true'></span> " . $this->lang->line('Public View') . " </a> &nbsp;<a href='create?v2=$v2' class='btn btn-flickr btn-sm breaklink'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create') . "  </a>"));                
                        
                        }
                        $this->load->model('billing_model', 'billing');
                        $tnote = '#' . $invocieno_n . '-' . $pmethod;
                        switch ($pmethod) {
                            case 'Cash' :
                                $r_amt1 = $pamnt;
                                $r_amt2 = 0;
                                $r_amt3 = 0;
                                break;
                            case 'Card Swipe' :
                                $r_amt1 = 0;
                                $r_amt2 = $pamnt;
                                $r_amt3 = 0;
                                break;
                            case 'Bank' :
                                $r_amt1 = 0;
                                $r_amt2 = 0;
                                $r_amt3 = $pamnt;
                                break;
                        }
                        $d_trans = $this->plugins->universal_api(69);
                        
                        if ($d_trans['key2']) {
                                $t_data = array(
                                'type' => 'Income',
                                'cat' => 'Sales',
                                'payerid' => $customer_id,
                                'method' => $pmethod,
                                'date' => $bill_date,
                                'eid' =>$emp,
                                'tid' => $invocieno,
                                'loc' =>$this->aauth->get_user()->loc
                            );
                    
                            $dual = $this->custom->api_config(65);
                            $this->db->select('holder');
                            $this->db->from('cberp_accounts');
                            $this->db->where('id', $dual['key2']);
                            $query = $this->db->get();
                            $account_d = $query->row_array();
                            $t_data['credit'] = 0;
                            $t_data['debit'] = $total;
                            $t_data['type'] = 'Expense';
                            $t_data['acid'] = $dual['key2'];
                            $t_data['account'] = $account_d['holder'];
                            $t_data['note'] = 'Debit ' . $tnote;
                    
                            $this->db->insert('cberp_transactions', $t_data);
                            //account update
                            $this->db->set('lastbal', "lastbal-$total", FALSE);
                            $this->db->where('id', $dual['key2']);
                            $this->db->update('cberp_accounts');
                            
                    
                        }

                        if ($pamnt > 0) 
                        {
                            $this->billing->paynow($invocieno, $pamnt, $tnote, $pmethod, $this->aauth->get_user()->loc, $bill_date, $account);
                        }
                        $this->registerlog->update($this->aauth->get_user()->id, $r_amt1, $r_amt2, $r_amt3, 0, $c_amt);


                        if ($promo_flag) {
                            $cqty = $result_c['available'] - 1;
                            if ($cqty > 0) {
                                $data = array('available' => $cqty);
                            } else {
                                $data = array('active' => 1, 'available' => $cqty);
                            }
                            $amount = $result_c['amount'];
                            $this->db->set($data);
                            $this->db->where('id', $result_c['id']);
                            $this->db->update('cberp_promo');
                    
                            if ($result_c['reflect'] > 0) {
                                $data = array(
                                    'payerid' => 0,
                                    'payer' => $this->lang->line('Coupon') . '-' . $result_c['code'],
                                    'acid' => $result_c['reflect'],
                                    'account' => 'Promo',
                                    'date' => date('Y-m-d'),
                                    'debit' => 0,
                                    'credit' => $amount,
                                    'type' => 'Income',
                                    'cat' => $this->lang->line('Coupon'),
                                    'method' => 'Transfer',
                                    'eid' => $this->aauth->get_user()->id,
                                    'note' => $this->lang->line('Coupon') . ' ' . $result_c['code'],
                                    'loc' => $this->aauth->get_user()->loc
                                );
                                $this->db->set('lastbal', "lastbal+$amount", FALSE);
                                $this->db->where('id', $result_c['reflect']);
                                $this->db->update('cberp_accounts');
                                $this->db->insert('cberp_transactions', $data);
                            }
                        }
                       
                    } 
                    else {
                        echo json_encode(array('status' => 'Error', 'message' =>
                            "Invalid Entry!"));
                        $transok = false;
                    }
            }

            if ($transok) {
                $this->db->trans_complete();
            } else {
                $this->db->trans_rollback();
            }
        } 
        
        
        
        
        elseif ($ptype == 2) {
            ///card section
            $discountFormat = $this->input->post('discountFormat');
            $pterms = $this->input->post('pterms');
            $currency = $this->input->post('mcurrency');
            $i = 0;
            if ($discountFormat == '0') {
                $discstatus = 0;
            } else {
                $discstatus = 1;
            }
            if ($customer_id == 0) {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('Please add a new client')));
                exit;
            }
            $this->db->trans_start();
            //products
            $transok = true;
            //Invoice Data
            $bill_date = datefordatabase($invoicedate);
            $bill_due_date = datefordatabase($invocieduedate);

            $promo_flag = false;
            if ($coupon) {
                $this->db->select('*');
                $this->db->from('cberp_promo');
                $this->db->where('code', $coupon);
                $query = $this->db->get();
                $result_c = $query->row_array();
                if ($result_c['active'] == 0 && $result_c['available'] > 0) {
                    $promo_flag = true;
                    $amount = $result_c['amount'];
                    $notes .= '-' . $this->input->post('i_coupon');
                    $total_discount += $amount;
                }
            }
            $data = array('invoice_number' => $invocieno, 'invoice_date' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'total' => $total, 'notes' => $notes, 'customer_id' => $customer_id, 'employee_id' => $emp, 'tax_status' => $tax, 'discount_status' => $discstatus, 'format_discount' => $discountFormat, 'reference' => $refer, 'payment_terms' => $pterms, 'multi' => $currency, 'i_class' => 1, 'loc' => $this->aauth->get_user()->loc);
            $invoice_true = false;
            if ($this->db->insert('cberp_invoices', $data)) {
                $invoice_true = true;
                $tid = $invocieno;
                $invocieno2 = $invocieno;
                $invocieno = $invocieno;
                $pid = $this->input->post('pid');
                $productlist = array();
                $prodindex = 0;
                $itc = 0;
                $product_id = $this->input->post('pid');
                $product_name1 = $this->input->post('product_name', true);
                $product_qty = $this->input->post('product_qty');
                $product_price = $this->input->post('product_price');
                $product_tax = $this->input->post('product_tax');
                $product_discount = $this->input->post('product_discount');
                $product_subtotal = $this->input->post('product_subtotal');
                $ptotal_tax = $this->input->post('taxa');
                $ptotal_disc = $this->input->post('disca');
                $product_des = $this->input->post('product_description', true);
                $product_unit = $this->input->post('unit');
                $product_hsn = $this->input->post('hsn');
                $product_alert = $this->input->post('alert');
                $product_serial = $this->input->post('serial');
                foreach ($pid as $key => $value) {
                    $total_discount += numberClean(@$ptotal_disc[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);
                    $data = array(
                        'invoice_number' => $invocieno,
                        'product_code' => $product_id[$key],
                        //'product' => $product_name1[$key],
                        //'code' => $product_hsn[$key],
                        'quantity' => numberClean($product_qty[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'tax' => numberClean($product_tax[$key]),
                        'discount' => numberClean($product_discount[$key]),
                        'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                        'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                        'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                        //'product_des' => $product_des[$key],
                        //'i_class' => 1,
                        //'unit' => $product_unit[$key],
                        //'serial' => $product_serial[$key]
                    );
                    $productlist[$prodindex] = $data;
                    $i++;
                    $prodindex++;
                    $amt = numberClean($product_qty[$key]);
                    if ($product_id[$key] > 0 and $this->common->zero_stock()) {

                        if($draft_id){
                            // $this->db->set('qty', "qty-$amt", FALSE);
                            // $this->db->where('pid', $product_id[$key]);
                            // $this->db->update('cberp_products');

                            $this->db->set('kg_quantity', "kg_quantity - $amt", FALSE);
                            $this->db->where('product_code', $product_id[$key]);
                            $this->db->update('cberp_products');

                        } elseif ((numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0) {
                            echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
                            $transok = false;
                            $st_c = 1;
                        } else {
                            // $this->db->set('qty', "qty-$amt", FALSE);
                            // $this->db->where('pid', $product_id[$key]);
                            // $this->db->update('cberp_products');

                            $this->db->set('kg_quantity', "kg_quantity - $amt", FALSE);
                            $this->db->where('product_code', $product_id[$key]);
                            $this->db->update('cberp_products');
                        }
                    }
                    $itc += $amt;
                }
                if ($prodindex > 0) {
                    $this->db->insert_batch('cberp_invoice_items', $productlist);
                    if (count($product_serial) > 0) {
                        $this->db->set('status', 1);
                        $this->db->where_in('serial', $product_serial);
                        $this->db->update('cberp_product_serials');
                    }
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product from product list. Go to Item manager section if you have not added the products."));
                    $transok = false;
                }
                $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
                $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
                if ($promo_flag) {
                    $cqty = $result_c['available'] - 1;
                    if ($cqty > 0) {
                        $data = array('available' => $cqty);
                    } else {
                        $data = array('active' => 1, 'available' => $cqty);
                    }

                    $this->db->set($data);
                    $this->db->where('id', $result_c['id']);
                    $this->db->update('cberp_promo');

                    if ($result_c['reflect'] > 0) {
                        $data = array(
                            'payerid' => 0,
                            'payer' => $this->lang->line('Coupon') . '-' . $result_c['code'],
                            'acid' => $result_c['reflect'],
                            'account' => 'Promo',
                            'date' => date('Y-m-d'),
                            'debit' => 0,
                            'credit' => $amount,
                            'type' => 'Income',
                            'cat' => $this->lang->line('Coupon'),
                            'method' => 'Transfer',
                            'eid' => $this->aauth->get_user()->id,
                            'note' => $this->lang->line('Coupon') . ' ' . $this->lang->line('Delete') . ' ' . $this->lang->line('Qty') . '-' . $result_c['available'],
                            'loc' => $this->aauth->get_user()->loc
                        );
                        $this->db->set('lastbal', "lastbal+$amount", FALSE);
                        $this->db->where('id', $result_c['reflect']);
                        $this->db->update('cberp_accounts');
                        $this->db->insert('cberp_transactions', $data);
                    }
                }

            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Invalid Entry!"));
                $transok = false;
            }


            //card process
            //card process
            if ($invoice_true) {
                $gateway = $this->input->post('gateway', true);
                setcookie("pos_set", base_url('pos_invoices/view?id=' . $invocieno), time() + 30 * 24 * 60 * 60, '/');
                $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
                $quckpay = base_url() . "billing/card?id=$invocieno&itype=inv&token=$validtoken&gid=$gateway";

                if ($transok) echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Invoice Success') . " <script type='text/javascript' >window.location.replace('$quckpay');</script><a target='_blank' href='$quckpay' class='btn btn-blue btn-sm breaklink'><span class='icon-printer' aria-hidden='true'></span> " . $this->lang->line('Pay') . "  </a> &nbsp; &nbsp;    <a target='_blank' href='thermal_pdf?id=$invocieno' class='btn btn-blue btn-sm breaklink'><span class='icon-printer' aria-hidden='true'></span> " . $this->lang->line('Print') . "  </a> &nbsp; &nbsp;    &nbsp; &nbsp; <a target='_blank' href='view?id=$invocieno' class='btn btn-purple btn-sm breaklink'><span class='icon-file-text2' aria-hidden='true'></span> " . $this->lang->line('View') . "  </a> &nbsp; &nbsp; <a href='$link' class='btn btn-blue-grey btn-sm' target='_blank'><span class='icon-earth' aria-hidden='true'></span> " . $this->lang->line('Public View') . " </a> <a href='create?v2=$v2' class='btn btn-amber btn-sm breaklink'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create') . "  </a>"));
            }

            if ($transok) {
                $this->db->trans_complete();
            } else {
                $this->db->trans_rollback();
            }
        } else {
        //draft   

        

                $p_amount = 0;
                    $pmethod = $this->input->post('p_method', true);

                    $c_amt = @$p_amount - $total;
                    if ($c_amt == 0.00) {
                        $status = 'Paid';
                        $pamnt = $total;
                    } elseif ($c_amt < 0.00) {
                        $status = 'Partial';
                        $pamnt = $p_amount;


                    } else {
                        $status = 'Paid';
                        $pamnt = $total;
                    }


                    $i = 0;
                    if ($discountFormat == '0') {
                        $discstatus = 0;
                    } else {
                        $discstatus = 1;
                    }

                    if ($customer_id == 0) {
                        echo json_encode(array('status' => 'Error', 'message' =>
                            $this->lang->line('Please add a new client')));
                        exit;
                    }
                    $this->db->trans_start();
                    //products
                    $transok = true;
                    //Invoice Data
                    $bill_date = datefordatabase($invoicedate);
                    $bill_due_date = datefordatabase($invocieduedate);
                    $promo_flag = false;

                    $status = 'Draft';

                    //echo $status;exit();

                    $data = array('invoice_number' => $invocieno, 'invoice_date' => $bill_date, 'due_date' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'shipping_tax' => $shipping_tax, 'shipping_tax_type' => $ship_taxtype, 'total' => $total, 'payment_method' => $pmethod, 'notes' => $notes, 'status' => $status, 'customer_id' => $customer_id, 'employee_id' => $this->aauth->get_user()->id, 'paid_amount' => 0, 'tax_status' => $tax, 'discount_status' => $discstatus, 'format_discount' => $discountFormat, 'reference' => $refer, 'payment_terms' => $pterms, 'multi' => $currency, 'i_class' => 1, 'loc' => $this->aauth->get_user()->loc);

                    $product_id = $this->input->post('pid');
                            $product_name1 = $this->input->post('product_name', true);
                            $product_qty = $this->input->post('product_qty');
                            $product_price = $this->input->post('product_price');
                            $product_tax = $this->input->post('product_tax');
                            $product_discount = $this->input->post('product_discount');
                            $product_subtotal = $this->input->post('product_subtotal');
                            $ptotal_tax = $this->input->post('taxa');
                            $ptotal_disc = $this->input->post('disca');
                            $product_des = $this->input->post('product_description', true);
                            $product_unit = $this->input->post('unit');
                            $product_hsn = $this->input->post('hsn');

                    if($draft_id>0){//update existing

                        
                        
                        $this->db->where('invoice_number', $invocieno);
                        $updated = $this->db->update('cberp_invoices', $data);

                        if ($updated) {
                            $invocieno2 = $invocieno;
                            $invocieno = $invocieno;
                            $pid = $this->input->post('pid');
                            $productlist = array();
                            $prodindex = 0;
                            $itc = 0;                           


                            foreach ($pid as $key => $value) {

                                $total_discount += numberClean(@$ptotal_disc[$key]);
                                $total_tax += numberClean($ptotal_tax[$key]);

                                $data = array(
                                    'invoice_number' => $invocieno,
                                    'product_code' => $product_id[$key],
                                    'quantity' => numberClean($product_qty[$key]),
                                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                                    'tax' => numberClean($product_tax[$key]),
                                    'discount' => numberClean($product_discount[$key]),
                                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                                );

                                $flag = true;
                                $productlist[$prodindex] = $data;
                                $i++;
                                $prodindex++;
                                $amt = numberClean($product_qty[$key]);
                                $itc += $amt;

                                // Check if item already exists
                                $this->db->where('invoice_number', $invocieno);
                                $this->db->where('product_code', $product_id[$key]);
                                $query = $this->db->get('cberp_invoice_items');

                                if ($query->num_rows() > 0) {
                                    // Update existing item
                                    $this->db->where('invoice_number', $invocieno);
                                    $this->db->where('product_code', $product_id[$key]);
                                    $this->db->update('cberp_invoice_items', $data);
                                } else {
                                    // Insert new item
                                    $this->db->insert('cberp_invoice_items', $data);
                                }
                            }

                            if ($prodindex > 0) {
                                //$this->db->insert_batch('cberp_invoice_items', $productlist);                              

                                $this->db->set(array('total_discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                                $this->db->where('invoice_number', $invocieno);
                                $this->db->update('cberp_invoices');

                            } else {
                                echo json_encode(array('status' => 'Error', 'message' =>
                                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                                $transok = false;
                            }

                            $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
                            $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
                            if ($transok) echo json_encode(array('status' => 'Success', 'message' =>
                                $this->lang->line('Draft Success') . " <a href='create' class='btn btn-info btn-sm' target='_blank'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('New') . "  </a>"));
                        }else {
                            echo json_encode(array('status' => 'Error', 'message' =>
                                "Invalid Entry!"));
                            $transok = false;
                        }

                    }else{

                        
                        if ($this->db->insert('cberp_invoices', $data)) {
                            $invocieno2 = $invocieno;
                            $invocieno = $invocieno;
                            $pid = $this->input->post('pid');
                            $productlist = array();
                            $prodindex = 0;
                            $itc = 0;                  


                            foreach ($pid as $key => $value) {


                                $total_discount += numberClean(@$ptotal_disc[$key]);
                                $total_tax += numberClean($ptotal_tax[$key]);

                                $data = array(
                                    'invoice_number' => $invocieno,
                                    'product_code' => $product_id[$key],
                                    //'product' => $product_name1[$key],
                                    //'code' => $product_hsn[$key],
                                    'quantity' => numberClean($product_qty[$key]),
                                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                                    'tax' => numberClean($product_tax[$key]),
                                    'discount' => numberClean($product_discount[$key]),
                                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                                    'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                                    'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                                    //'product_des' => $product_des[$key] ?? null,
                                    //'i_class' => 1,
                                    //'unit' => $product_unit[$key]echooooooo
                                );

                                $flag = true;
                                $productlist[$prodindex] = $data;
                                $i++;
                                $prodindex++;
                                $amt = numberClean($product_qty[$key]);
                                $itc += $amt;
                            }

                            if ($prodindex > 0) {
                                $this->db->insert_batch('cberp_invoice_items', $productlist);
                                //$this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));

                                $this->db->set(array('total_discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc)));
                                $this->db->where('invoice_number', $invocieno);
                                $this->db->update('cberp_invoices');

                            } else {
                                echo json_encode(array('status' => 'Error', 'message' =>
                                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                                $transok = false;
                            }

                            $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
                            $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
                            if ($transok) echo json_encode(array('status' => 'Success', 'message' =>
                                $this->lang->line('Draft Success') . " <a href='create' class='btn btn-info btn-sm' target='_blank'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('New') . "  </a>"));

                        } else {
                            echo json_encode(array('status' => 'Error', 'message' =>
                                "Invalid Entry!"));
                            $transok = false;
                        }

                    }
                    
                    if ($transok) {
                        $this->db->trans_complete();
                    } else {
                        $this->db->trans_rollback();
                    }
               
                    if ($transok) {
                        $this->db->from('univarsal_api');
                        $this->db->where('univarsal_api.id', 56);
                        $query = $this->db->get();
                        $auto = $query->row_array();
                        if ($auto['key1'] == 1) {
                            $this->db->select('name,email');
                            $this->db->from('cberp_customers');
                            $this->db->where('customer_id', $customer_id);
                            $query = $this->db->get();
                            $customer = $query->row_array();

                            $this->load->model('communication_model');
                            $invoice_mail = $this->send_invoice_auto($invocieno, $invocieno2, $bill_date, $total, $currency);
                            $attachmenttrue = false;
                            $attachment = '';
                            $this->communication_model->send_corn_email($customer['email'], $customer['name'], $invoice_mail['subject'], $invoice_mail['message'], $attachmenttrue, $attachment);

                        }

                        if ($auto['key2'] == 1) {
                            $this->db->select('name,phone');
                            $this->db->from('cberp_customers');
                            $this->db->where('customer_id', $customer_id);
                            $query = $this->db->get();
                            $customer = $query->row_array();

                            $this->load->model('plugins_model', 'plugins');
                            $invoice_sms = $this->send_sms_auto($invocieno, $invocieno2, $bill_date, $total, $currency);

                            $mobile = $customer['phone'];
                            $text_message = $invoice_sms['message'];
                            $this->load->model('sms_model', 'sms');
                            $this->sms->send_sms($mobile, $text_message, false);


                        }

                    }
            
            
        }
        //profit calculation
        $t_profit = 0;
        $this->db->select('cberp_invoice_items.product_code, cberp_invoice_items.price, cberp_invoice_items.quantity, cberp_products.product_cost');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_invoice_items.product_code', 'left');
        $this->db->where('cberp_invoice_items.invoice_number', $invocieno);
        $query = $this->db->get();
        $pids = $query->result_array();
        foreach ($pids as $profit) {
            $t_cost = $profit['product_cost'] * $profit['quantity'];
            $s_cost = $profit['price'] * $profit['quantity'];

            $t_profit += $s_cost - $t_cost;
        }
        $data = array('type' => 9, 'rid' => $invocieno, 'col1' => $t_profit, 'd_date' => $bill_date);

        $this->db->insert('cberp_metadata', $data);

        if ($print_now) {
            $print = $this->thermal_print($invocieno, false, false);
        }
}

    



	


    public function ajax_list()
    {

        $list = $this->invocies->get_datatables($this->limited);

		//print_r($list);exit();

        $data = array();

        $no = $this->input->post('start');

        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url("pos_invoices/view?id=$invoices->invoice_number") . '">&nbsp; ' . $invoices->invoice_number . '</a>';
            $row[] = $invoices->name;
            $row[] = dateformat($invoices->invoice_date);
            $row[] = amountExchange($invoices->total, 0, $this->aauth->get_user()->loc);
            $row[] = '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            $row[] = '<a href="' . base_url("pos_invoices/thermal_pdf?id=$invoices->invoice_number") . '&d=1" class="btn btn-info btn-sm"  title="Download"><span class="fa fa-download"></span></a>&nbsp;<a href="#" data-object-id="' . $invoices->invoice_number . '" class="btn btn-danger btn-sm delete-object"><span class="fa fa-trash"></span></a>';

            $data[] = $row;
        }


        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->invocies->count_all($this->limited),
            "recordsFiltered" => $this->invocies->count_filtered($this->limited),
            "data" => $data,
        );

        //output to json format
        echo json_encode($output);

    }

    function salesOrderexportpdf()
    {

        
        // if (!$this->aauth->premission(10)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }

        $salesPro=$this->input->post('selectedProducts');

        $tid = $this->session->userdata("salesorderid");
        $productIds = explode(",",$salesPro);

        $data = array('status' => 'material requested');
        $this->db->where_in('pid', $productIds);
        $this->db->where('tid', $tid);
        $this->db->where('status IS NULL');
        $this->db->update('cberp_sales_orders_items', $data);

        $invocienoId=$this->input->post('invocienoId');
        $customer_id=$this->input->post('customer_id');
        $data['products'] = $this->invocies->sales_products($invocienoId,$productIds);
        $data["invoicedate"]=$this->input->post('invoicedate');
        $data["invocieduedate"]=$this->input->post('invocieduedate');

        
        $data['CustDetails'] = $this->invocies->salesorder_cust($customer_id);
        $data['invocienoId'] = $invocienoId;
        $client = '' . $data['CustDetails'][0]['name'] . '<br>' . $data['CustDetails'][0]['address'] . ','. $data['CustDetails'][0]['city'] .' <br>' . $data['CustDetails'][0]['phone'] . '<br>' .$data['CustDetails'][0]['email'] ;
        $data["client"]=$client;
        $data["custId"]=$data['CustDetails'][0]['id'];
       
        $loc = location($this->aauth->get_user()->loc);
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        
        //$this->load->view('accounts/saleorderrepopdf-ltr',$data);
         $html = $this->load->view('accounts/saleorderrepopdf-' . LTR, $data, true);
         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('Sales-order-invoice' . $pay_acc . '.pdf', 'D');
        
            
    }
    function materialrequestreportpdf()
    {
        
        
        // if (!$this->aauth->premission(10)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }

        $salesPro=$this->input->post('selectedProducts');
        $tid = $this->session->userdata("orderid");
        $productIds = explode(",",$salesPro);
        $data = array('status' => 'material requested');
        $this->db->where_in('pid', $productIds);
        $this->db->where('tid', $tid);
        $this->db->where('status IS NULL');
        $this->db->update('cberp_sales_orders_items', $data);

        $invocienoId=$this->input->post('invocienoId');
        $customer_id=$this->input->post('customer_id');
        $data['products'] = $this->invocies->sales_products($invocienoId,$productIds);
        $data["invoicedate"]=$this->input->post('invoicedate');
        $data["invocieduedate"]=$this->input->post('invocieduedate');

        
        $data['CustDetails'] = $this->invocies->salesorder_cust($customer_id);
        $data['invocienoId'] = $invocienoId;
        $client = '' . $data['CustDetails'][0]['name'] . '<br>' . $data['CustDetails'][0]['address'] . ','. $data['CustDetails'][0]['city'] .' <br>' . $data['CustDetails'][0]['phone'] . '<br>' .$data['CustDetails'][0]['email'] ;
        $data["client"]=$client;
        $data["custId"]=$data['CustDetails'][0]['id'];
       
        $loc = location($this->aauth->get_user()->loc);
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        
        //$this->load->view('accounts/saleorderrepopdf-ltr',$data);
         $html = $this->load->view('accounts/saleorderrepopdf-' . LTR, $data, true);
         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('material-request-report' . $pay_acc . '.pdf', 'I');
        
            
    }
    function deliverNoteexportpdf()
    {

       
        // if (!$this->aauth->premission(10)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        
        $salesPro=$this->input->post('selectedProducts');
        $data['deliverynoteFlg']=$this->input->post('deliverynoteFlg');
        $deliveredQtyItems = $this->input->post('deliveredItems');
        $deliveredItems = explode(",",$deliveredQtyItems);
        $tid = $this->session->userdata("salesorderid");
        $productIds = explode(",",$salesPro);
        
        // print_r($productIds); die();

        $invocienoId=$this->input->post('invocienoId');
        $customer_id=$this->input->post('customer_id');
        $data['products'] = $this->invocies->sales_products($invocienoId,$productIds);
        $data["invoicedate"]=$this->input->post('invoicedate');
        $data["invocieduedate"]=$this->input->post('invocieduedate');

        $this->load->model('customers_model', 'customers');
        $data['CustDetails'] = $this->invocies->salesorder_cust($customer_id);        
        $data['salesman'] = $this->customers->customer_salesman($customer_id);
        $data['invocienoId'] = $invocienoId;
        $client = '' . $data['CustDetails'][0]['name'] . '<br>' . $data['CustDetails'][0]['address'] . ','. $data['CustDetails'][0]['city'] .' <br>' . $data['CustDetails'][0]['phone'] . '<br>' .$data['CustDetails'][0]['email'] ;
        $data["client"]=$client;
        $data["custId"]=$data['CustDetails'][0]['id'];
       
        $loc = location($this->aauth->get_user()->loc);
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

     
        if($this->session->userdata("deliverymaster")){
            $delevery_note_id = $this->session->userdata("deliverymaster");
            $this->db->select('product_id, product_qty, product_price, product_tax, product_discount, subtotal, totaltax, totaldiscount');
            $this->db->from('cberp_delivery_note_items');
            $this->db->where('delevery_note_id', $delevery_note_id);
            $delprds = $this->db->get();
            $delprdsres = $delprds->result_array();

            if(!empty($delprdsres)){
                foreach($delprdsres as $key=>$item){
                    $delProducts[$item['product_id']]['product_qty'] = $item['product_qty'];
                    $delProducts[$item['product_id']]['product_price'] = $item['product_price'];
                    $delProducts[$item['product_id']]['product_discount'] = $item['product_discount'];
                    $delProducts[$item['product_id']]['subtotal'] = $item['subtotal'];
                    $delProducts[$item['product_id']]['totaltax'] = $item['totaltax'];
                    $delProducts[$item['product_id']]['totaldiscount'] = $item['totaldiscount'];
                }
                $data['delProducts'] = $delProducts;
            }
          
            $this->db->select('total_amount, subtotal, ship_tax, discount, tax');
            $this->db->from('cberp_delivery_notes');
            $this->db->where('delevery_note_id', $delevery_note_id);
            $delmainprds = $this->db->get();
            $delmainprdsres = $delmainprds->row_array();
            $data['delmainprdsres'] = $delmainprdsres;
        }
        // ==================================================================
            $this->db->select('pid,qty');
            $this->db->from('cberp_sales_orders_items');
            $this->db->where_in('pid', $productIds);
            $this->db->where('tid', $tid);
            $this->db->where('status','delivered');

            
            // $this->db->where('inventory_adjusted','0');        
            $query = $this->db->get();       
           
            $result = $query->result_array();
            //  print_r($this->db->last_query()); print_r($result); die(); 
            if(!empty($result)){
                $k=0;
                foreach($result as $item){
                    $pid = $item['pid'];
                    $deliveredqty = $deliveredItems[$k];
                    $this->db->select('qty');
                    $this->db->from('cberp_products');
                    $this->db->where_in('pid', $pid);
                    $prdQry = $this->db->get();      

                    $prdresult = $prdQry->row_array();
                    $onhand = $prdresult['qty'];
                    $updateQty = intval($onhand) - intval($deliveredqty);
                    $upqty = array('qty' => $updateQty); 
                    $this->db->where_in('pid', $pid);
                    $this->db->update('cberp_products', $upqty);

                    // $this->db->set('inventory_adjusted', '1');
                    // $this->db->where_in('pid', $pid);
                    // $this->db->where('tid', $tid);
                    // $this->db->update('cberp_sales_orders_items');
                }
            }
        // ===================================================================
        
        $data['deliveredItems'] = $deliveredItems;
       
        // $this->load->view('accounts/deliverynotetopdf-ltr',$data);
         $html = $this->load->view('accounts/deliverynotetopdf-' . LTR, $data, true);
         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('delivery-note' . $pay_acc . '.pdf', 'I');
        
            
    }

    function exportpdf()
    {
        // if (!$this->aauth->premission(10)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }

        $list = $this->extended_invoice->get_datatables($this->limited);
        $data = array();
        $data1 = array();

        $no = $this->input->post('start');

        foreach ($list as $invoices) {
            $data1[]=[
                "name"=>$invoices->name,
                "date"=>dateformat($invoices->invoicedate),
                "product"=>$invoices->product,
                "qty"=>intval($invoices->qty),
                "discount"=>number_format($invoices->discount, 2),
                // "discount"=>amountExchange($invoices->discount, 0, $this->aauth->get_user()->loc),
                "tax"=>number_format($invoices->tax, 2),
                "amount"=>number_format($invoices->subtotal, 2)
            ];
            
        }
        //   echo "<pre>"; print_r( $list[0]->eid); die();
        $data['employee'] = $this->invocies->employee($list[0]->eid);
        $data["list"]= $data1;
        $data["start_date"]= $this->input->post('start_date');
        $data["end_date"]= $this->input->post('end_date');
      
        $loc = location($this->aauth->get_user()->loc);
        $company = '<strong>' . $loc['cname'] . '</strong><br>' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        if ($loc['tax_id']) $company .= '<br>' . $this->lang->line('Tax') . ' ID: ' . $loc['tax_id'];
        $data['lang']['company'] = $company;
      
        $html = $this->load->view('accounts/salereportpdf-' . LTR, $data, true);


        ini_set('memory_limit', '64M');


        $this->load->library('pdf');

        $pdf = $this->pdf->load();


        $pdf->WriteHTML($html);


        $pdf->Output('Statement' . $pay_acc . '.pdf', 'I');


    }

    public function extended_ajax_list()
    {
        // if (!$this->aauth->premission(10)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }

        $list = $this->extended_invoice->get_datatables($this->limited);

        $data = array();

        $no = $this->input->post('start');

        foreach ($list as $invoices) {
            if (strpos($invoices->product, '(') !== false && strpos($invoices->product, ')') !== false && substr($invoices->product, -1) === ')') {
                // Use preg_replace to remove the section between '(' and ')'
                $invoices->product = preg_replace('/\s*\([^)]*\)/', '', $invoices->product);
            }            
            $invoices->product = trim($invoices->product);
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url("invoices/create?id=$invoices->id") . '">&nbsp; ' . $invoices->invoice_number . '</a>';
            $row[] = $invoices->name;
            $row[] = dateformat($invoices->invoicedate);
             $row[] = $invoices->product;
             $row[] = $invoices->code;
            $row[] = intval($invoices->qty);
            $row[] = number_format($invoices->subtotal, 2);
            // $row[] = amountExchange($invoices->subtotal, 0, $this->aauth->get_user()->loc);
            $row[] = number_format($invoices->subtotal, 2);
            $row[] = number_format($invoices->discount, 2 );
            // $row[] = number_format($invoices->tax, 2);

         //   $row[] = '<a href="' . base_url("pos_invoices/view?id=$invoices->id") . '" class="btn btn-success btn-sm" title="View"><i class="fa fa-eye"></i></a>&nbsp;<a href="' . base_url("pos_invoices/thermal_pdf?id=$invoices->id") . '&d=1" class="btn btn-info btn-sm"  title="Download"><span class="fa fa-download"></span></a>&nbsp;<a href="#" data-object-id="' . $invoices->id . '" class="btn btn-danger btn-sm delete-object"><span class="fa fa-trash"></span></a>';

            $data[] = $row;
        }


        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->extended_invoice->count_all($this->limited),
            "recordsFiltered" => $this->extended_invoice->count_filtered($this->limited),
            "data" => $data,
        );

        //output to json format
        echo json_encode($output);

    }

    public function view()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = $this->input->get('id');
        $data['id'] = $tid;

        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        $data['attach'] = $this->invocies->attach($tid);
        if ($data['invoice']['invoice_number']) $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']['invoice_number']) $data['activity'] = $this->invocies->invoice_transactions($tid);
        $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['cid'], 1);

        $this->load->library("Printer");
        $data['printer'] = $this->printer->check($data['invoice']['loc']);

        $data['employee'] = $this->invocies->employee($data['invoice']['employee_id']);
        $head['title'] = "Invoice " . $data['invoice']['invoice_number'];
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        if ($data['invoice']['invoice_number']) $this->load->view('pos/view', $data);
        $this->load->view('fixed/footer');

    }


    public function printinvoice()
    {
        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $data['round_off'] = $this->custom->api_config(4);
		
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
		
		
        if ($data['invoice']['invoice_number']) $data['products'] = $this->invocies->invoice_products($tid);
		// echo "<pre>";
		// print_r($data['invoice']);
		// echo "</pre>";
		// exit();
        if ($data['invoice']['invoice_number']) $data['employee'] = $this->invocies->employee($data['invoice']['employee_id']);
        if (CUSTOM) $data['c_custom_fields'] = $this->custom->view_fields_data($data['invoice']['customer_id'], 1, 1);
        if ($data['invoice']['i_class'] == 1) {
            $pref = prefix(7);
        } else {
            $pref = $this->config->item('prefix');
        }

        $data['general'] = array('title' => $this->lang->line('Invoice'), 'person' => $this->lang->line('Customer'), 'prefix' => $pref, 't_type' => 0);
        ini_set('memory_limit', '64M');
        if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
            $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
        } else {
            $html = $this->load->view('print_files/invoice-a4_v' . INVV, $data, true);
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
            $pdf->Output('Invoice_pos' . $data['invoice']['invoice_number'] . '.pdf', 'D');
        } else {
            $pdf->Output('Invoice_pos' . $data['invoice']['invoice_number'] . '.pdf', 'I');
        }
    }

    public function delete_i()
    {
        $id = $this->input->post('deleteid');
       // if ($this->aauth->premission(11)) {
            if ($this->invocies->invoice_delete($id, $this->limited)) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('DELETED')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
        // } else {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         $this->lang->line('ERROR')));
        // }
    }

    public function editaction()
    {
        // if (!$this->aauth->premission(13)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $ptype = $this->input->post('type');
        $coupon = $this->input->post('coupon');
        $notes = $this->input->post('notes', true);
        $coupon_amount = 0;
        $coupon_n = '';
        $account = $this->input->post('account', true);
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invoiceno');
        $invocieno = $this->input->post('iid');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $currency = $this->input->post('mcurrency');
        if ($ptype == 4) {
            $p_amount = rev_amountExchange_s($this->input->post('p_amount'), $currency, $this->aauth->get_user()->loc);
            $pmethod = $this->input->post('p_method');
            $notes = $this->input->post('notes');
            $tax = $this->input->post('tax_handle');
            $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
            $ship_taxtype = $this->input->post('ship_taxtype');
            $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
            $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
            if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
            $refer = $this->input->post('refer');
            $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
            $old_total = rev_amountExchange_s($this->input->post('old_total'), $currency, $this->aauth->get_user()->loc);
            $total_tax = 0;
            $total_discount = 0;
            $discountFormat = $this->input->post('discountFormat');
            $pterms = $this->input->post('pterms');
            //edit
            $diff = $total - $old_total;
            $c_amt = $p_amount - $diff;
            if ($c_amt < 0) {
                $c_amt = 0;
            }
            $i = 0;
            if ($this->limited) {
                $employee = $this->invocies->invoice_details($invocieno, $this->limited);
                if ($this->aauth->get_user()->id != $employee['eid']) exit();
            }
            if ($discountFormat == '0') {
                $discstatus = 0;
            } else {
                $discstatus = 1;
            }
            if ($customer_id == 0) {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('Please add a new client')));
                exit;
            }
            $this->db->trans_start();
            $transok = true;
            $bill_date = datefordatabase($invoicedate);
            $bill_due_date = datefordatabase($invocieduedate);
            $promo_flag = false;
            if ($coupon) {
                $this->db->select('*');
                $this->db->from('cberp_promo');
                $this->db->where('code', $coupon);
                $query = $this->db->get();
                $result_c = $query->row_array();
                if ($result_c['active'] == 0 && $result_c['available'] > 0) {
                    $promo_flag = true;
                    $amount = $result_c['amount'];
                    $notes .= '-' . $this->input->post('i_coupon');
                    $total_discount += $amount;
                }
            }
            $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency);
            $this->db->set($data);
            $this->db->where('id', $invocieno);
            if ($this->db->update('cberp_invoices', $data)) {
                //Product Data
                $pid = $this->input->post('pid');
                $productlist = array();
                $prodindex = 0;
                $itc = 0;
                $this->db->delete('cberp_invoice_items', array('tid' => $invocieno));
                $product_id = $this->input->post('pid');
                $product_name1 = $this->input->post('product_name', true);
                $product_qty = $this->input->post('product_qty');
                $old_product_qty = $this->input->post('old_product_qty');
                $product_price = $this->input->post('product_price');
                $product_tax = $this->input->post('product_tax');
                $product_discount = $this->input->post('product_discount');
                $product_subtotal = $this->input->post('product_subtotal');
                $ptotal_tax = $this->input->post('taxa');
                $ptotal_disc = $this->input->post('disca');
                $product_des = $this->input->post('product_description', true);
                $product_unit = $this->input->post('unit');
                $product_hsn = $this->input->post('hsn');
                $product_serial = $this->input->post('serial');
                foreach ($pid as $key => $value) {

                    $total_discount += numberClean(@$ptotal_disc[$key]);
                    $total_tax += numberClean($ptotal_tax[$key]);
                    $data = array(
                        'tid' => $invocieno,
                        'pid' => $product_id[$key],
                        'product' => $product_name1[$key],
                        'code' => $product_hsn[$key],
                        'qty' => numberClean($product_qty[$key]),
                        'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                        'tax' => numberClean($product_tax[$key]),
                        'discount' => numberClean($product_discount[$key]),
                        'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                        'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                        'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                        'product_des' => $product_des[$key] ?? null,
                        'i_class' => 1,
                        'unit' => $product_unit[$key],
                        'serial' => $product_serial[$key] ?? null
                    );

                    $productlist[$prodindex] = $data;
                    $i++;
                    $prodindex++;
                    $amt = numberClean($product_qty[$key]) - @numberClean($old_product_qty[$key]);

                    if ($product_id[$key] > 0) {
                        $this->db->set('qty', "qty-$amt", FALSE);
                        $this->db->where('pid', $product_id[$key]);
                        $this->db->update('cberp_products');
                    }
                    $itc += $amt;
                }
                if ($prodindex > 0) {

                    $this->db->insert_batch('cberp_invoice_items', $productlist);
                    $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                    $this->db->where('id', $invocieno);
                    $this->db->update('cberp_invoices');
                    if ($product_serial AND count($product_serial) > 0) {
                        $this->db->set('status', 1);
                        $this->db->where_in('serial', $product_serial);
                        $this->db->update('cberp_product_serials');
                    }
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        "Please add at least one product in invoice"));
                    $transok = false;
                }
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Invoice has  been updated') . " <a href='view?id=$invocieno' class='btn btn-info btn-sm'><span class='icon-file-text2' aria-hidden='true'></span> " . $this->lang->line('View') . " </a> "));

                $this->load->model('billing_model', 'billing');
                $tnote = '#' . $invocieno_n . '-' . $pmethod;
                switch ($pmethod) {
                    case 'Cash' :
                        $r_amt1 = $diff;
                        $r_amt2 = 0;
                        $r_amt3 = 0;
                        break;
                    case 'Card Swipe' :
                        $r_amt1 = 0;
                        $r_amt2 = $diff;
                        $r_amt3 = 0;
                        break;
                    case 'Bank' :
                        $r_amt1 = 0;
                        $r_amt2 = 0;
                        $r_amt3 = $diff;
                        break;
					default :
						$r_amt1 = 0;
						$r_amt2 = 0;
						$r_amt3 = $diff;
						break;
                }
                $this->billing->paynow($invocieno, $diff, $tnote, $pmethod, $this->aauth->get_user()->loc, 0, $account);
                $this->registerlog->update($this->aauth->get_user()->id, $r_amt1, $r_amt2, $r_amt3, 0, $c_amt);
                if ($promo_flag) {
                    $cqty = $result_c['available'] - 1;
                    if ($cqty > 0) {
                        $data = array('available' => $cqty);
                    } else {
                        $data = array('active' => 1, 'available' => $cqty);
                    }
                    $amount = $result_c['amount'];
                    $this->db->set($data);
                    $this->db->where('id', $result_c['id']);
                    $this->db->update('cberp_promo');

                    if ($result_c['reflect'] > 0) {
                        $data = array(
                            'payerid' => 0,
                            'payer' => $this->lang->line('Coupon') . '-' . $result_c['code'],
                            'acid' => $result_c['reflect'],
                            'account' => 'Promo',
                            'date' => date('Y-m-d'),
                            'debit' => 0,
                            'credit' => $amount,
                            'type' => 'Income',
                            'cat' => $this->lang->line('Coupon'),
                            'method' => 'Transfer',
                            'eid' => $this->aauth->get_user()->id,
                            'note' => $this->lang->line('Coupon') . ' ' . $this->lang->line('Delete') . ' ' . $this->lang->line('Qty') . '-' . $result_c['available'],
                            'loc' => $this->aauth->get_user()->loc
                        );
                        $this->db->set('lastbal', "lastbal+$amount", FALSE);
                        $this->db->where('id', $result_c['reflect']);
                        $this->db->update('cberp_accounts');
                        $this->db->insert('cberp_transactions', $data);
                    }
                }
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
                $transok = false;
            }

            if ($this->input->post('restock')) {
                foreach ($this->input->post('restock') as $key => $value) {

                    $myArray = explode('-', $value);
                    $prid = $myArray[0];
                    $dqty = numberClean($myArray[1]);
                    if ($prid > 0) {
                        $this->db->set('qty', "qty+$dqty", FALSE);
                        $this->db->where('pid', $prid);
                        $this->db->update('cberp_products');
                    }
                }
            }


            if ($transok) {
                $this->db->trans_complete();
            } else {
                $this->db->trans_rollback();
            }

        }

        //profit calculation
        $t_profit = 0;
		//$mega_discount=rev_amountExchange_s(amountFormat_general($total_discount));
        $this->db->select('cberp_invoice_items.pid, cberp_invoice_items.price, cberp_invoice_items.qty, cberp_products.product_cost');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
        $this->db->where('cberp_invoice_items.tid', $invocieno);
        $query = $this->db->get();
        $pids = $query->result_array();
        foreach ($pids as $profit) {
            $t_cost = $profit['product_cost'] * $profit['qty'];
            $s_cost = $profit['price'] * $profit['qty'];

            $t_profit += $s_cost - $t_cost;
        }
        $this->db->set('col1', $t_profit);
        $this->db->where('type', 9);
        $this->db->where('rid', $invocieno);
        $this->db->update('cberp_metadata');

    }

    public function update_status()
    {
        $tid = $this->input->post('tid');
        $status = $this->input->post('status');
        $this->db->set('status', $status);
        $this->db->where('id', $tid);
        $this->db->update('cberp_invoices');
        echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('UPDATED'), 'pstatus' => $status));
    }


    public function addcustomer()
    {
        $name = $this->input->post('name', true);
        $company = $this->input->post('company', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);
        $address = $this->input->post('address', true);
        $city = $this->input->post('city', true);
        $region = $this->input->post('region', true);
        $country = $this->input->post('country', true);
        $postbox = $this->input->post('postbox', true);
        $tax_id = $this->input->post('tax_id', true);
        $customergroup = $this->input->post('customergroup', true);
        $shipping_name = $this->input->post('shipping_name', true);
        $shipping_phone = $this->input->post('shipping_phone', true);
        $shipping_email = $this->input->post('shipping_email', true);
        $shipping_address_1 = $this->input->post('shipping_address_1', true);
        $shipping_city = $this->input->post('shipping_city', true);
        $shipping_region = $this->input->post('shipping_region', true);
        $shipping_country = $this->input->post('shipping_country', true);
        $shipping_postbox = $this->input->post('shipping_postbox', true);
           $custom = $this->input->post('c_field', true);
        $this->load->model('customers_model', 'customers');
        $this->customers->add($name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox,'', '', '', '', $custom);

    }

    public function file_handling()
    {
        if ($this->input->get('op')) {
            $name = $this->input->get('name');
            $invoice = $this->input->get('invoice');
            if ($this->invocies->meta_delete($invoice, 1, $name)) {
                echo json_encode(array('status' => 'Success'));
            }
        } else {
            $id = $this->input->get('id');
            $this->load->library("Uploadhandler_generic", array(
                'accept_file_types' => '/\.(gif|jpe?g|png|docx|docs|txt|pdf|xls)$/i', 'upload_dir' => FCPATH . 'userfiles/attach/', 'upload_url' => base_url() . 'userfiles/attach/'
            ));
            $files = (string)$this->uploadhandler_generic->filenaam();
            if ($files != '') {

                $this->invocies->meta_insert($id, 1, $files);
            }
        }


    }


    function thermal_print($id = 0, $mode = true, $d = true)
    {
        //dual mode -basic for standard users and advanced mode

        $basic_mode = false;

        $tid = $this->input->get('id');
        if (!$tid > 0) $tid = $id;
        $data['id'] = $tid;
        $data['title'] = "Invoice $tid";
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        if ($data['invoice']['id']) $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']['id']) $data['employee'] = $this->invocies->employee($data['invoice']['eid']);

        $this->load->library("Printer");
        $printer_data = $this->printer->check($data['invoice']['loc']);

        if ($printer_data) {

            if ($printer_data['val2'] != 'server') {
                if ($d) echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('your thermal printer')));

                //based on  https://github.com/mike42/escpos-php
                switch ($printer_data['val2']) {
                    case 'file' :
                        $connector = new FilePrintConnector($printer_data['val3']);
                        break;
                    case 'network' :
                        $address = parse_url($printer_data['val3']);
                        $connector = new NetworkPrintConnector($address['host'], $address['port']);
                        break;
                    case 'windows' :
                        $connector = new WindowsPrintConnector($printer_data['val3']);
                        break;

                    case 'test' :
                        $connector = new DummyPrintConnector();
                        break;

                }

                $items = array();
                $sub_t = 0;
                foreach ($data['products'] as $row) {
                    $items[] = $this->toString($row['product'], $row['subtotal']);
                    $sub_t += $row['price'] * $row['qty'];
                }
                $subtotal = $this->toString($this->lang->line('Subtotal'), $sub_t);
                $tax = $this->toString($this->lang->line('Tax'), $data['invoice']['tax']);
                $total = $this->toString($this->lang->line('Total'), $data['invoice']['total'], true);
                $round_off = $this->custom->api_config(4);
                if ($round_off['other']) {
                    $final_amount = round($data['invoice']['total'], $round_off['active'], constant($round_off['other']));
                    $total_round = $this->toString($this->lang->line('Round Off'), $final_amount, true);
                }


                //Date is kept the same for testing
                // $date = date('l jS \of F Y h:i:s A');
                $date = dateformat($data['invoice']['invoicedate']) . ' ' . date('h:i:s A');


//simple and fast printing
                if (!$printer_data['other']) {

                    // Start the printer
                    //$logo = EscposImage::load(FCPATH . "userfiles/company/logo.png", false);
                    $printer = new Printer($connector);

                    // Print top logo
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    //$printer->graphics($logo);

                    //Name of shop
                    $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer->text($this->config->item('ctitle') . "\n");
                    $printer->selectPrintMode();
                    $printer->text($this->config->item('address') . "\n");
                    $printer->feed();

                    //Title of receipt
                    $printer->setEmphasis(true);
                    $printer->text($this->lang->line('Invoice') . ' ' . $data['invoice']['tid'] . "\n");
                    $printer->setEmphasis(false);

                    //Items
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->setEmphasis(true);
                    $printer->text($this->toString('', $this->config->item('currency')));
                    $printer->setEmphasis(false);
                    foreach ($items as $item) {
                        $printer->text($item);
                    }
                    $printer->setEmphasis(true);
                    $printer->text($subtotal);
                    $printer->setEmphasis(false);
                    $printer->feed();

                    // Tax and total
                    $printer->text($tax);
                    $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer->text($total);
                    if ($round_off['other']) {
                        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                        $printer->text($total_round);
                    }
                    $printer->selectPrintMode();

                    // Footer
                    $printer->feed(2);
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->text($this->lang->line('Thank you') . "\n");
                    $printer->text("\n");
                    $printer->feed(2);
                    $printer->text($date . "\n");

                    // Cut the receipt and open the cash drawer
                    $printer->cut();
                    //   $printer->pulse();
                    if ($printer_data['val2'] == 'test') {
                        $data = $connector->getData();

                        header('Content-type: application/octet-stream');
                        header('Content-Length: ' . strlen($data));

                        $file = FCPATH . "pos_test_receipt_" . date('Y-m-d_H_i_s') . ".bin";
                        file_put_contents($file, $data);
                    }


                    $printer->close();
                } else {
                    //thermal pdf printing
                    $printer = new Printer($connector);
                    $this->pheight = 0;
                    $data['id'] = $tid;
                    $data['title'] = "Invoice $tid";
                    $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
                    if ($data['invoice']) $data['products'] = $this->invocies->invoice_products($tid);
                    if ($data['invoice']) $data['employee'] = $this->invocies->employee($data['invoice']['eid']);

                    $this->load->model('billing_model', 'billing');
                    $online_pay = $this->billing->online_pay_settings();
                    if ($online_pay['enable'] == 1) {
                        $token = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
                        $data['qrc'] = 'pos_' . date('Y_m_d_H_i_s') . '_.png';
                        $static_q = $data['qrc'];

                        $qrCode = QrCode::create(base_url('billing/card?id=' . $tid . '&itype=inv&token=' . $token))
                            ->setEncoding(new Encoding('UTF-8'))
                            ->setSize(300)
                            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                            ->setForegroundColor(new Color(0, 0, 0))
                            ->setBackgroundColor(new Color(255, 255, 255));

                        $writer = new PngWriter();
                        $result = $writer->write($qrCode);
                        $result->saveToFile(FCPATH . 'userfiles/pos_temp/' . $data['qrc']);

                        //$qrCode = new QrCode(base_url('billing/card?id=' . $tid . '&itype=inv&token=' . $token));

//header('Content-Type: '.$qrCode->getContentType());
//echo $qrCode->writeString();
                       // $qrCode->writeFile(FCPATH . 'userfiles/pos_temp/' . $data['qrc']);
                    }

                    // boost the memory limit if it's low ;)
                    ini_set('memory_limit', '64M');
                    // load library
                    $this->load->library('pdf');
                    $pdf = $this->pdf->load_thermal();
                    // retrieve data from model or just static date
                    $data['title'] = "items";
                    $pdf->allow_charset_conversion = true;  // Set by default to TRUE
                    $pdf->charset_in = 'UTF-8';
                    //   $pdf->SetDirectionality('rtl'); // Set lang direction for rtl lang
                    $pdf->autoLangToFont = true;
                    $html = $this->load->view('pos/pdfposthermal', $data, true);
                    // render the view into HTML

                    $h = 160 + $this->pheight;
                    $pdf->_setPageSize(array(70, $h), $pdf->DefOrientation);
                    $pdf->WriteHTML($html);
                    $file_name = preg_replace('/[^A-Za-z0-9]+/', '-', 'PosInvoice_' . $data['invoice']['name'] . '_' . $data['invoice']['tid']);

                    $pdf->Output('userfiles/pos_temp/' . $file_name . '.pdf', 'F');


                    if (!extension_loaded('imagick')) {
                        // echo 'imagick not installed';
                        echo json_encode(array('status' => 'Error', 'message' => 'imagick not installed'));
                        $printer->close();
die();
                    }
                    try {
                        $im = new Imagick();

                        $image=FCPATH . 'userfiles' . DIRECTORY_SEPARATOR . 'pos_temp' . DIRECTORY_SEPARATOR . $file_name . '.png';

                        $im->setResolution(300, 300);
                        $im->readimage(FCPATH . 'userfiles' . DIRECTORY_SEPARATOR . 'pos_temp' . DIRECTORY_SEPARATOR . $file_name . '.pdf');
                        $im->setImageType(imagick::IMGTYPE_TRUECOLOR);
                        $im->setImageFormat('png');
                        //$im->transparentPaintImage(      'white', 0, 100, false    );
                        $im->writeImage($image);
                        $im->clear();
                        $im->destroy();
                        $printer->graphics(EscposImage::load($image));
                        $printer->cut();
                        if ($printer_data['val2'] == 'test') {
                            $data = $connector->getData();

                            header('Content-type: application/octet-stream');
                            header('Content-Length: ' . strlen($data));

                            $file = FCPATH . "advanced_pos_test_receipt_" . date('Y-m-d_H_i_s') . ".bin";
                            file_put_contents($file, $data);
                        }
                        $printer->close();
                        unlink(FCPATH . 'userfiles' . DIRECTORY_SEPARATOR . 'pos_temp' . DIRECTORY_SEPARATOR . $static_q);
                        unlink(FCPATH . 'userfiles' . DIRECTORY_SEPARATOR . 'pos_temp' . DIRECTORY_SEPARATOR . $file_name . '.pdf');
                        unlink(FCPATH . 'userfiles' . DIRECTORY_SEPARATOR . 'pos_temp' . DIRECTORY_SEPARATOR . $file_name . '.png');


                    } catch (ImagickException $imagick_exception) {
echo 6;
                    }
                }


            } elseif ($printer_data['val2'] == 'server') {

                $this->db->select('key');
                $this->db->from('cberp_restkeys');
                $this->db->limit(1);
                $query_r = $this->db->get();

                if ($query_r->num_rows() > 0) {

                    $rest = $query_r->row_array()['key'];
                    $ch = curl_init();
                    // Set cURL options
                    curl_setopt($ch, CURLOPT_URL, $printer_data['val3'] . "?app_url=" . htmlentities(base_url()) . '&id=' . $tid . '&rest_key=' . $rest . '&printer_connection=server');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.66 Safari/537.36");
                    // Decode returned JSON
                    $output = json_decode(curl_exec($ch), true);
                    // Close Channel
                    curl_close($ch);

                } else {
                    if ($mode) echo json_encode(array('status' => 'Error', 'message' => 'Please create  a rest key.'));
                }
            } else {
                if ($mode) echo json_encode(array('status' => 'Error', 'message' => 'Please setup a thermal printer in printer section.'));
            }
        }

    }

    public function toString($name = '', $price = '', $dollarSign = false)
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($name, $leftCols);

        $sign = $this->config->item('currency');
        $right = str_pad($sign . $price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";

    }


    function thermal_pdf()
    {
        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $data['title'] = "Invoice $tid";
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        $data['round_off'] = $this->custom->api_config(4);

		// echo "<pre>";
		// print_r($data['invoice']);
		// echo "</pre>";
		// exit();
		

        if ($data['invoice']) $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']) $data['employee'] = $this->invocies->employee($data['invoice']['employee_id']);
		//print_r($data['employee']);exit();
        $this->load->model('billing_model', 'billing');
        $online_pay = $this->billing->online_pay_settings();
        if ($online_pay['enable'] == 1) {
            $token = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
            $data['qrc'] = 'pos_' . date('Y_m_d_H_i_s') . '_.png';
            $qrCode = QrCode::create(base_url('billing/card?id=' . $tid . '&itype=inv&token=' . $token))
                ->setEncoding(new Encoding('UTF-8'))
                ->setSize(300)
                ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $result->saveToFile(FCPATH . 'userfiles/pos_temp/' . $data['qrc']);
           // $qrCode = new QrCode(base_url('billing/view?id=' . $tid . '&itype=inv&token=' . $token));
            //header('Content-Type: '.$qrCode->getContentType());
            //echo $qrCode->writeString();
           // $qrCode->writeFile(FCPATH . 'userfiles/pos_temp/' . $data['qrc']);
        }
        // boost the memory limit if it's low ;)
        ini_set('memory_limit', '64M');
        // load library
        $this->load->library('pdf');
        $this->pheight = 0;
        $this->load->library('pdf');
        $pdf = $this->pdf->load_thermal();
        // retrieve data from model or just static date
        $data['title'] = "items";
        $pdf->allow_charset_conversion = true;  // Set by default to TRUE
        $pdf->charset_in = 'UTF-8';
        //   $pdf->SetDirectionality('rtl'); // Set lang direction for rtl lang
        $pdf->autoLangToFont = true;
        $html = $this->load->view('print_files/pos_pdf_compact', $data, true);
        $h = 160 + $this->pheight;
        //  $pdf->_setPageSize(array(70, $h), $this->orientation);
        $pdf->_setPageSize(array(70, $h), $pdf->DefOrientation);
        $pdf->WriteHTML($html);
        // render the view into HTML
        // write the HTML into the PDF
        //$file_name = preg_replace('/[^A-Za-z0-9]+/', '-', 'PosInvoice_' . $data['invoice']['name'] . '_' . $data['invoice']['invoice_number']);
		$file_name = preg_replace('/[^A-Za-z0-9]+/', '-', 'PosInvoice_' . $data['invoice']['invoice_number'] . '_' . $data['invoice']['invoice_number']);
        if ($this->input->get('d')) {
            $pdf->Output($file_name . '.pdf', 'D');
        } else {
            $pdf->Output($file_name . '.pdf', 'I');
        }

        unlink('userfiles/pos_temp/' . $data['qrc']);
    }


    public function set_coupon()
    {
        $id = $this->input->post('coupon');
        if ($id) {
            $this->db->select('*');
            $this->db->from('cberp_promo');
            $this->db->where('code', $id);
            $query = $this->db->get();
            $promo = $query->row_array();
            if (($promo['active'] == 0) && ($promo['available'] > 0) && (strtotime($promo['valid']) >= strtotime(date('Y-m-d')))) {
                echo json_encode(array('status' => 'Success', 'message' => $promo['code'] . ' ' . $this->lang->line('Coupon Applied') . ' ' . $this->lang->line('Amount') . ' ' . amountFormat_general($promo['amount']), 'amount' => amountFormat_general($promo['amount'])));
            } elseif (($promo['active'] == 0) && ($promo['available'] > 0) && (strtotime($promo['valid']) < strtotime(date('Y-m-d')))) {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('Coupon Expired'), 'amount' => 0));
            } elseif ($promo['active'] > 0) {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('Usages Exceed'), 'amount' => 0));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('Invalid Coupon'), 'amount' => 0));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' => '', 'amount' => 0));
        }


    }

    public function send_invoice_auto($invocieno, $invocieno2, $idate, $total, $multi)
    {
        $this->load->library('parser');
        $this->load->model('templates_model', 'templates');
        $template = $this->templates->template_info(6);

        $data = array(
            'Company' => $this->config->item('ctitle'),
            'BillNumber' => $invocieno2
        );
        $subject = $this->parser->parse_string($template['key1'], $data, TRUE);
        $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
        $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);


        $data = array(
            'Company' => $this->config->item('ctitle'),
            'BillNumber' => $invocieno2,
            'URL' => "<a href='$link'>$link</a>",
            'CompanyDetails' => '<h6><strong>' . $this->config->item('ctitle') . ',</strong></h6>
<address>' . $this->config->item('address') . '<br>' . $this->config->item('address2') . '</address>
             ' . $this->lang->line('Phone') . ' : ' . $this->config->item('phone') . '<br>  ' . $this->lang->line('Email') . ' : ' . $this->config->item('email'),
            'DueDate' => dateformat($idate),
            'Amount' => amountExchange($total, $multi)
        );
        $message = $this->parser->parse_string($template['other'], $data, TRUE);


        return array('subject' => $subject, 'message' => $message);
    }

    public function send_sms_auto($invocieno, $invocieno2, $idate, $total, $multi)
    {
        $this->load->library('parser');
        $this->load->model('templates_model', 'templates');
        $template = $this->templates->template_info(30);
        $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
        $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
        $this->load->model('plugins_model', 'plugins');
        $sms_service = $this->plugins->universal_api(1);
        if ($sms_service['active']) {
            $this->load->library("Shortenurl");
            $this->shortenurl->setkey($sms_service['key1']);
            $link = $this->shortenurl->shorten($link);
        }
        $data = array(
            'BillNumber' => $invocieno2,
            'URL' => $link,
            'DueDate' => dateformat($idate),
            'Amount' => amountExchange($total, $multi)
        );
        $message = $this->parser->parse_string($template['other'], $data, TRUE);
        return array('message' => $message);
    }


    public function invoicepdf()
    {
        $run = false;
        $id = $this->input->get('id');
        $key = $this->input->get('key');
        $this->db->select('key');
        $this->db->from('cberp_restkeys');
        $this->db->limit(1);
        $this->db->where('key', $key);
        $query_r = $this->db->get();
        if ($query_r->num_rows() > 0) {
            $run = true;
        }

        if (!$run) {
            exit('eer');
        }

        // Get the user from the array, using the id as key for retrieval.
        // Usually a model is to be used for this.

        $tid = $id;
        $data['qrc'] = 'pos_' . date('Y_m_d_H_i_s') . '_.png';
        $data['id'] = $tid;
        $data['title'] = "Invoice $tid";
        $data['invoice'] = $this->invocies->invoice_details($tid);
        if ($data['invoice']) $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']) $data['employee'] = $this->invocies->employee($data['invoice']['eid']);


        $this->load->model('billing_model', 'billing');
        $online_pay = $this->billing->online_pay_settings();
        if ($online_pay['enable'] == 1) {
            $token = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
            $data['qrc'] = 'pos_' . date('Y_m_d_H_i_s') . '_.png';

            $qrCode = new QrCode(base_url('billing/card?id=' . $tid . '&itype=inv&token=' . $token));

//header('Content-Type: '.$qrCode->getContentType());
//echo $qrCode->writeString();
            $qrCode->writeFile(FCPATH . 'userfiles/pos_temp/' . $data['qrc']);
        }

        $this->pheight = 0;
        $this->load->library('pdf');
        $pdf = $this->pdf->load_thermal();
        // retrieve data from model or just static date
        $data['title'] = "items";
        $pdf->allow_charset_conversion = true;  // Set by default to TRUE
        $pdf->charset_in = 'UTF-8';
        //   $pdf->SetDirectionality('rtl'); // Set lang direction for rtl lang
        $pdf->autoLangToFont = true;
        $data['round_off'] = $this->custom->api_config(4);
        $html = $this->load->view('print_files/pos_pdf_compact', $data, true);
        // render the view into HTML

        $h = 160 + $this->pheight;
        $pdf->_setPageSize(array(70, $h), $pdf->DefOrientation);
        $pdf->WriteHTML($html);
        $file_name = substr($key, 0, 6) . $id;
        $pdf->Output('userfiles/pos_temp/' . $file_name . '.pdf', 'F');
        if (!extension_loaded('imagick')) {
            echo 'imagick not installed';
        }
        $im = new Imagick();
        $im->setResolution(300, 300);
        $im->readimage(FCPATH . 'userfiles/pos_temp/' . $file_name . '.pdf');
        $im->setImageType(imagick::IMGTYPE_TRUECOLOR);
        $im->setImageFormat('png');
        //$im->transparentPaintImage(      'white', 0, 100, false    );
        $im->writeImage(FCPATH . 'userfiles/pos_temp/rest-' . $file_name . '.png');
        $im->clear();
        $im->destroy();
        unlink('userfiles/pos_temp/' . $data['qrc']);
        unlink(FCPATH . 'userfiles/pos_temp/' . $file_name . '.pdf');


    }

    public function invoiceclean()
    {
        $file_name = substr($this->input->get('key'), 0, 6) . $this->input->get('id');
        unlink(FCPATH . 'userfiles/pos_temp/' . $file_name . '.png');

    }

    public function view_payslip()
    {
        $id = $this->input->get('id');
        $inv = $this->input->get('inv');
        $data['invoice'] = $this->invocies->invoice_details($inv, $this->limited);
        if (!$data['invoice']['id']) exit('Limited Permissions!');

        $this->load->model('transactions_model', 'transactions');
        $head['title'] = "View Transaction";
        $head['usernm'] = $this->aauth->get_user()->username;

        $data['trans'] = $this->transactions->view($id);

        if ($data['trans']['payerid'] > 0) {
            $data['cdata'] = $this->transactions->cview($data['trans']['payerid'], $data['trans']['ext']);
        } else {
            $data['cdata'] = array('address' => 'Not Registered', 'city' => '', 'phone' => '', 'email' => '');
        }
        ini_set('memory_limit', '64M');

        $html = $this->load->view('transactions/view-print-customer', $data, true);

        //PDF Rendering
        $this->load->library('pdf');

        $pdf = $this->pdf->load_en();

        $pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;"><tr><td width="33%"></td><td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td><td width="33%" style="text-align: right; ">#' . $id . '</td></tr></table>');

        $pdf->WriteHTML($html);

        if ($this->input->get('d')) {

            $pdf->Output('Trans_#' . $id . '.pdf', 'D');
        } else {
            $pdf->Output('Trans_#' . $id . '.pdf', 'I');
        }


    }

    // public function invoice_legacy_bkup()
    // {

    //     $id = $this->input->post('inv');
	// 	//echo $id; exit();

    //     if (!$id) {
    //         exit('eer');
    //     }

    //     // Get the user from the array, using the id as key for retrieval.
    //     // Usually a model is to be used for this.

    //     $file_name = rand(99,999) . $id.time();

    //     $tid = $id;
    //     $data['qrc'] = $file_name. '.png';
    //     $data['id'] = $tid;
    //     $data['title'] = "Invoice $tid";
    //     $data['invoice'] = $this->invocies->invoice_details($tid);
    //     if ($data['invoice']) $data['products'] = $this->invocies->invoice_products($tid);
    //     if ($data['invoice']) $data['employee'] = $this->invocies->employee($data['invoice']['employee_id']);
        


    //     $this->load->model('billing_model', 'billing');
    //     $online_pay = $this->billing->online_pay_settings();
    //     if ($online_pay['enable'] == 1) {
    //         $token = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));
			
    //         $data['qrc'] = $file_name. '.png';

    //         $qrCode = new QrCode(base_url('billing/card?id=' . $tid . '&itype=inv&token=' . $token));
	// 		//header('Content-Type: '.$qrCode->getContentType());
	// 		//echo $qrCode->writeString();
    //         $qrCode->writeFile(FCPATH . 'userfiles/pos_temp/' . $data['qrc']);

	// 		// $qrCode = QrCode::create(base_url('billing/card?id=' . $tid . '&itype=inv&token=' . $token))
    //         //     ->setEncoding(new Encoding('UTF-8'))
    //         //     ->setSize(300)
    //         //     ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
    //         //     ->setForegroundColor(new Color(0, 0, 0))
    //         //     ->setBackgroundColor(new Color(255, 255, 255));

	// 		// 	echo $qrCode.'aa';exit();

    //         // $writer = new PngWriter();
    //         // $result = $writer->write($qrCode);
    //         // $result->saveToFile(FCPATH . 'userfiles/pos_temp/' . $data['qrc']);
    //     }

	// 	// echo "<pre>";
	// 	// print_r($data);
	// 	// echo "</pre>";
	// 	// exit();

    //     $this->pheight = 0;
    //     $this->load->library('pdf');
    //     $pdf = $this->pdf->load_thermal();
    //     // retrieve data from model or just static date
    //     $data['title'] = "items";
    //     $pdf->allow_charset_conversion = true;  // Set by default to TRUE
    //     $pdf->charset_in = 'UTF-8';
    //     //   $pdf->SetDirectionality('rtl'); // Set lang direction for rtl lang
    //     $pdf->autoLangToFont = true;
    //     $data['round_off'] = $this->custom->api_config(4);
    //     $html = $this->load->view('print_files/pos_pdf_compact', $data, true);
    //     // render the view into HTML

    //     $h = 160 + $this->pheight;
    //     $pdf->_setPageSize(array(70, $h), $pdf->DefOrientation);
    //     $pdf->WriteHTML($html);

    //     $r=$pdf->Output('userfiles/pos_temp/' . $file_name . '.pdf', 'F');
    //     echo  json_encode(array('status'=>'Success','file_name'=> $file_name));

    // }

	public function invoice_legacy()
	{
		//error_reporting(E_ALL);
		//ini_set('display_errors', 1);

		try {
			$id = $this->input->post('inv');
			if (!$id) {
				throw new Exception('Invoice ID not provided');
			}

			$file_name = rand(99, 999) . $id . time();

			$tid = $id;
			$data['qrc'] = $file_name . '.png';
			$data['id'] = $tid;
			$data['title'] = "Invoice $tid";
			$data['invoice'] = $this->invocies->invoice_details($tid);
			if ($data['invoice']) $data['products'] = $this->invocies->invoice_products($tid);
			if ($data['invoice']) $data['employee'] = $this->invocies->employee($data['invoice']['employee_id']);

			$this->load->model('billing_model', 'billing');
			$online_pay = $this->billing->online_pay_settings();

			if ($online_pay['enable'] == 1) {
				$token = hash_hmac('ripemd160', $tid, $this->config->item('encryption_key'));

				$data['qrc'] = $file_name . '.png';

				$qrCode = QrCode::create(base_url('billing/card?id=' . $tid . '&itype=inv&token=' . $token))
					->setEncoding(new Encoding('UTF-8'))
					->setSize(300)
					->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
					->setForegroundColor(new Color(0, 0, 0))
					->setBackgroundColor(new Color(255, 255, 255));

				$writer = new PngWriter();
				$result = $writer->write($qrCode);
				
				$qrPath = FCPATH . 'userfiles/pos_temp/' . $data['qrc'];
				if (!is_dir(dirname($qrPath))) {
					mkdir(dirname($qrPath), 0777, true);
				}
				$result->saveToFile($qrPath);
			}

			$this->pheight = 0;
			$this->load->library('pdf');
			$pdf = $this->pdf->load_thermal();

			$data['title'] = "items";
			$pdf->allow_charset_conversion = true;
			$pdf->charset_in = 'UTF-8';
			$pdf->autoLangToFont = true;

			$data['round_off'] = $this->custom->api_config(4);
			$html = $this->load->view('print_files/pos_pdf_compact', $data, true);

			$h = 160 + $this->pheight;
			$pdf->_setPageSize(array(70, $h), $pdf->DefOrientation);
			$pdf->WriteHTML($html);

			$pdfPath = 'userfiles/pos_temp/' . $file_name . '.pdf';
			$r = $pdf->Output($pdfPath, 'F');

			echo json_encode(['status' => 'Success', 'file_name' => $file_name]);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['status' => 'Error', 'message' => $e->getMessage()]);
		}
	}



	


    public function invoice_clean()
    {
        $file_id = $this->input->post('file_id', true);
        unlink(FCPATH . 'userfiles' . DIRECTORY_SEPARATOR . 'pos_temp' . DIRECTORY_SEPARATOR . $file_id . '.png');
        unlink(FCPATH . 'userfiles' . DIRECTORY_SEPARATOR . 'pos_temp' . DIRECTORY_SEPARATOR . $file_id . '.pdf');

    }

}
