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

class Quote extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('quote_model', 'quote');
        // require_once('../application/libraries/Aauth.php');
        if (!is_login()) {
            redirect(base_url() . 'user/profile', 'refresh');
        }
        
        
    }


    //invoices list
    public function index()
    {
        $head['title'] = "Manage Quote";

        $this->load->view('includes/header', $head);
        $this->load->view('quotes/quotes');
        $this->load->view('includes/footer');
    }


    public function ajax_list()
    {

        $list = $this->quote->get_datatables();
        $data = array();

        $no = $this->input->post('start');


        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $invoices->tid;
            $row[] = $invoices->name;
            $row[] = dateformat($invoices->invoicedate);
            $row[] = amountFormat($invoices->total);
            $row[] = '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            $row[] = '<a href="' . base_url("quote/view?id=$invoices->id") . '" class="btn btn-success btn-xs"><i class="icon-file-text"></i> ' . $this->lang->line('View') . '</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->quote->count_all(),
            "recordsFiltered" => $this->quote->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function view()
    {
        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $head['title'] = "Quote $tid";
        $data['invoice'] = $this->quote->quote_details($tid);
        if ($data['invoice']['csd'] == $this->session->userdata('user_details')[0]->cid) {
            $data['products'] = $this->quote->quote_products($tid);


            $data['employee'] = $this->quote->employee($data['invoice']['eid']);

            $this->load->view('includes/header', $head);
            $this->load->view('quotes/view', $data);
            $this->load->view('includes/footer');
        }

    }

    public function approve()
    {
        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $head['title'] = "Quote $tid";
        $data['invoice'] = $this->quote->quote_details($tid);
        if ($data['invoice']['csd'] == $this->session->userdata('user_details')[0]->cid) {
            $this->quote->update_status($tid);
            $data['products'] = $this->quote->quote_products($tid);


            $data['employee'] = $this->quote->employee($data['invoice']['eid']);
            $m=array('message'=>'Approved!');
            $this->session->set_flashdata('item',$m);
            $this->session->keep_flashdata('item',$m);
            redirect(base_url('quote/view?id=' . $tid));


        }

    }
    public function editquote()
    {
        $tid = $this->input->get('id');
        $this->load->library("Common");
        $this->load->helper('siteconfig');

        $data['id'] = $tid;
        $head['title'] = "Quote $tid";
        $data['invoice'] = $this->quote->quote_details($tid);
        $data['products'] = $this->quote->quote_products($tid);
        if ($data['invoice']['csd'] == $this->session->userdata('user_details')[0]->cid) {
            $data['products'] = $this->quote->quote_products($tid);
            $data['employee'] = $this->quote->employee($data['invoice']['eid']);
            // $m=array('message'=>'Approved!');
            // $this->session->set_flashdata('item',$m);
            // $this->session->keep_flashdata('item',$m);
         }
         
            $this->load->view('includes/header', $head);
            $this->load->view('quotes/edit', $data);
            $this->load->view('includes/footer');

    }
    public function saveQuoteChanges()
    {
        if($this->input->post()){
            
            
            $quote = [];
            $quotedet = [];
            $tid = $this->input->post('invoicenumber');
            $quote['subtotal'] = $this->input->post('invoicesubtotal');
            $quote['discount'] = $this->input->post('invoicediscount');
            $quote['tax'] = $this->input->post('invoicetax');
            $quote['total'] = $this->input->post('invoicetotal');
            $condition = array('tid'=>$tid);
            $this->quote->update_qoute($quote,$condition,"cberp_quotes");
            $m=array('message'=>'Quote Updated Successfully!');
            $this->session->set_flashdata('quoteupdate',$m);

            $products = $this->input->post('productNo');
            $product_qty = $this->input->post('product_qty');
            $old_product_qty = $this->input->post('old_product_qty');
            $tnumber = $this->input->post('tnumber');
            $old_subtotal = $this->input->post('old_subtotal');
            $new_subtotal = $this->input->post('new_subtotal');
            $eachproducttax = $this->input->post('eachproducttax');
            $eachproductdiscount = $this->input->post('eachproductdiscount');
            $totalItems = count($products);
            if($totalItems>0){
                for($i=0; $i < $totalItems; $i++){
                    $data['qty'] = $product_qty[$i];
                    $data['subtotal'] = $new_subtotal[$i];
                    if($eachproducttax[$i]>0){                        
                        $data['totaltax'] = $eachproducttax[$i];
                    }
                    if($eachproductdiscount[$i]>0){
                        $data['totaldiscount'] = $eachproductdiscount[$i];
                    }
                    $condition = array('tid'=>$tnumber[$i], 'id'=> $products[$i]);
                    $this->quote->update_qoute($data,$condition,"cberp_quotes_items");
                    if($product_qty[$i]!=$old_product_qty[$i]){
                        $quoteai['quote_item_id'] = $tnumber[$i];
                        $quoteai['quote_item_detail_id'] = $products[$i];
                        $quoteai['quoted_qty'] = $old_product_qty[$i];
                        $quoteai['customer_qty'] = $product_qty[$i];
                        if($product_qty[$i]<1){
                            $quoteai['item_deleted'] = "0";
                        }
                        $this->quote->insert_quoteai($quoteai,"cberp_quote_ai");
                    }
                }
            }

            redirect(base_url('quote/editquote?id=8'));
         }
    }

}
