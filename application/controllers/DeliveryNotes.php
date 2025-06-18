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

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class DeliveryNotes extends CI_Controller
{
    private $configurations;
    private $prifix72;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('deliverynote_model', 'deliverynote');
         $this->load->model('plugins_model', 'plugins');
         $this->load->model('pos_invoices_model', 'invocies');         
         $this->load->model('customers_model', 'customers');
         $this->load->model('quote_model', 'quote');
        $this->load->library("Aauth");        
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }

        if ($this->aauth->get_user()->roleid == 2) {
            $this->limited = $this->aauth->get_user()->id;
        } else {
            $this->limited = '';
        }
        $this->load->library("Custom");
        $this->li_a = 'sales';
        $this->configurations = $this->session->userdata('configurations');
        $this->prifix72 =  get_prefix_72();
    }
 
    
    //invoices list
    public function index()
    {
        $data['permissions'] = load_permissions('Sales','Sales','Delivery Notes','List');
        $head['title'] = "Delivery Notes";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model');
        // $condition = "";
        // $this->session->unset_userdata("selecteddelnoteids");
        // $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_delivery_notes','created_date','total_amount',$condition);   
        $data['ranges'] = getCommonDateRanges();   
        $data['counts'] = $this->deliverynote->get_filter_count($data['ranges']);        
        $data['customers']  = customer_list();
        $this->load->view('fixed/header', $head);
        $this->load->view('deliverynotes/deliverynotes', $data);
        $this->load->view('fixed/footer');
    }

    public function ajax_list()
    {

        $list = $this->deliverynote->get_datatables($this->limited);
        $data = array();
        $no = $this->input->post('start');
        
        foreach ($list as $deliverynote) {

            
            // check_delivered_and_return_qty_equal created
            $checkedres = $this->deliverynote->check_delivered_and_return_qty_equal($deliverynote->delivery_note_number);
            $no++;
            $row = array();
            if($deliverynote->status=="Printed" || $deliverynote->status=="Completed"){
                $delnoteidcheckbox = '<input type="checkbox" class="checkeditems d-none1" name="delevery_note_ids[]" value="'.$deliverynote->delivery_note_number.'" id="del-'.$deliverynote->delivery_note_number.'">';
            }          
            else{
                $delnoteidcheckbox = '<input type="checkbox" class="checkeditems d-none1" disabled>';              
            }
            if($checkedres==1){
                $delnoteidcheckbox = '<input type="checkbox" class="checkeditems d-none1"  disabled>';
            }
            $row[] = $delnoteidcheckbox;
            $deliverynotenumber = (!empty($deliverynote->delivery_note_number)) ? $deliverynote->delivery_note_number : ($deliverynote->delivery_note_number+1000);
            $targeturl = ($deliverynote->status=="Draft") ? base_url("DeliveryNotes/create?id=$deliverynote->delivery_note_number") : base_url("DeliveryNotes/create?id=$deliverynote->delivery_note_number") ;
           
            //old one
            // $targeturl = ($deliverynote->status=="Draft") ? base_url("DeliveryNotes/create?id=$deliverynote->delivery_note_number") : base_url("DeliveryNotes/create?id=$deliverynote->delivery_note_number") ;

            $row[] = '<a href="' . $targeturl . '">'.($deliverynotenumber).'</a>'; 
            $row[] = '<a href="' . base_url("SalesOrders/salesorder_new?id=$deliverynote->salesorder_number&token=3") . '">'.$deliverynote->salesorder_number.'</a>';
            // $row[] = '<a href="' . base_url("DeliveryNotes/deliverynote_view?id=$deliverynote->delivery_note_number") . '">'.($deliverynotenumber).'</a>';
           
            $colorcode = ($deliverynote->due_date) ? get_color_code($deliverynote->due_date) :"";
            $dudate = (!empty($deliverynote->due_date))?dateformat($deliverynote->due_date):"";
            $row[] = '<b style="color:'.$colorcode.'">'.$dudate.'</b>';
            // $row[] = dateformat($deliverynote->created_date);
            $row[] = "#".$deliverynote->customer_id." ".$deliverynote->name;
            $row[] = $deliverynote->store_name;

            //salesman removed 09-04-2025
            // $row[] = $deliverynote->data;          
            $row[] = $deliverynote->total_amount;

            // if($deliverynote->status=="Delivered")//
            // {
            //     $status = "<span class='st-pending'>".$deliverynote->status."</span>";
            // }
            // else{
            //     $status = "<span class='st-accepted'>".$deliverynote->status."</span>";
            // }
            $reprintBtn = '<a href="' . base_url("DeliveryNotes/reprintnote?delivery=$deliverynote->delivery_note_number&sales=$deliverynote->salesorder_number&cust=$deliverynote->customer_id") . '" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Print</a>';
                                                
            // $invoiceBtn = '<button onclick="invoicing(\'' . $deliverynote->delivery_note_number . '\')" class="btn btn-sm btn-secondary"><i class="fa fa-exchange"></i> Convert to Invoice</button>' ;

            $invoiceBtn = '<a href="'.base_url('invoices/create?dnid='.$deliverynote->delivery_note_number).'"  class="btn btn-sm btn-secondary"><i class="fa fa-exchange"></i> Convert to Invoice</a>';


            // $invoiceBtn = '<a href="'.base_url('deliverynotes/convert_deliverynote_to_invoice?id='.$deliverynote->delivery_note_number).'"  class="btn btn-sm btn-secondary"><i class="fa fa-exchange"></i> Convert to Invoice</a>';
            
            $deliveryBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn?delivery=$deliverynote->delivery_note_number&type=new") . '"  class="btn btn-sm btn-secondary"><i class="fa fa-undo"></i> '.$this->lang->line('Delivery Return').'</a>';

            // $innerbtn = '<a href="' . base_url("DeliveryNotes/deliverynote_view?id=" . $deliverynote->delivery_note_number) . '" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Print</a>';

            $innerbtn = '<a href="' . base_url("DeliveryNotes/reprintnote?delivery=$deliverynote->delivery_note_number&sales=$deliverynote->salesorder_number&cust=$deliverynote->customer_id") . '" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Print</a>';

            $reprintBtn1 = '<a href="' . base_url("DeliveryNotes/deliverynote_view?id=" . $deliverynote->delivery_note_number) . '" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Print</a>';

            // set $reprintBtn1 = $reprintBtn for direct printing create
            $reprintBtn1 = $reprintBtn;

            $actionbtn = "";
            $related_status ="";
            if($deliverynote->status=="Delivered")
            {
                $status = "<span class='st-pending'>".$deliverynote->status."</span>";
                
            }
            else if($deliverynote->status=="Draft")
            {
                $status = "<span class='st-Draft'>".$deliverynote->status."</span>";
                $actionbtn = "";
            }

            else if($deliverynote->status=="Printed" || $deliverynote->status=="Completed")
            {
                $status = "<span class='st-accepted'>Completed</span>";
                // $status = "<span class='st-active'>".$deliverynote->status."</span>";
                $actionbtn = $reprintBtn1.'&nbsp;'.$invoiceBtn.'&nbsp;'.$deliveryBtn;
            }
      
            else if(($deliverynote->status=="Assigned" || $deliverynote->status=="Created") && $deliverynote->pick_ticket_status=="0")
            {
                $status = "<span class='st-created'>".$deliverynote->status."</span>";
                $actionbtn = $innerbtn;
            }
            //09-04-2025 foe in progress
            else if($deliverynote->status=="In Progress")
            {
                $status = "<span class='st-inprogress'>In Progress</span>";
                $actionbtn = $innerbtn;
            }
           
            // else if($deliverynote->status=="Assigned" && $deliverynote->pick_ticket_status=="1" && $deliverynote->pick_item_recieved_status=="0")
            // {
            //     $status = "<span class='st-rejected'>Picking List Printed</span>";
            //     $actionbtn = $innerbtn;
            // }
            // else if($deliverynote->status=="Assigned" && $deliverynote->pick_ticket_status=="1" && $deliverynote->pick_item_recieved_status=="1")
            // {
            //     $status = "<span class='st-rejected'>Items Picked</span>";
            //     $actionbtn = $innerbtn;
            // }
            else if($deliverynote->status=="Invoiced")
            {
                $related_status = "<span class='st-accepted'>".$deliverynote->status."</span>";
                $actionbtn = $deliveryBtn;
            }
            else if($deliverynote->status=="Canceled")
            {
                $related_status = "<span class='st-canceled'>".$deliverynote->status."</span>";
                $actionbtn = "";
            }
            else{
                // $status = "<span class='st-canceled'>".$deliverynote->status."</span>";
            }
            

            if($checkedres==1 && $deliverynote->status!='Draft')
            {
                $related_status = "<span class='st-Closed'>Fully Returned</span>";
                $actionbtn = "";
            }
            $row[] = $status;
            $row[] = $related_status;

            $reprintBtn = '<a href="' . base_url("DeliveryNotes/reprintnote?delivery=$deliverynote->delivery_note_number&sales=$deliverynote->salesorder_number&cust=$deliverynote->customer_id") . '" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Reprint</a>';
            
            $invoiceBtn = ($deliverynote->status == 'Delivered') ? '<button onclick="invoicing(\'' . $deliverynote->delivery_note_number . '\')" class="btn btn-sm btn-secondary"><i class="fa fa-exchange"></i> Convert to Invoice</button>' : "";

            $deliveryBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn?delivery=$deliverynote->delivery_note_number&type=new") . '"  class="btn btn-sm btn-secondary"><i class="fa fa-undo"></i> '.$this->lang->line('Delivery Return').'</a>';

            $row[] = $actionbtn;
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->deliverynote->count_all($this->limited),
            "recordsFiltered" => $this->deliverynote->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    
    public function create()
    {

        //         ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        //prefix
        $data['validity'] = default_validity();
        $data['permissions'] = load_permissions('Sales','Sales','Delivery Notes','View Page');       
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        // $customersalesman = $this->customers->customer_salesman($data['customergrouplist'][0]['id']);
        // $data['salesman'] = $data['customersalesman']['data'];
        $delivery_note_number = $this->input->get('id');
        $data['id'] = $delivery_note_number;
        // $data['trackingdata'] = tracking_details('sales_id',$delivery_note_number);
        $this->session->set_userdata('salesorderid', $delivery_note_number);
        $data['terms'] = $this->quote->billingterms();
        // $data['invoice'] = $this->quote->salesorder_details_draft($delivery_note_number); warehouse_by_id
        // $data['products'] = $this->quote->salesorder_products_deliverynotes($delivery_note_number);
        $checkedres=0;
        $data['action_type'] = "";
        $data['colorcode']="";
        if($delivery_note_number)
        {
            $data['action_type'] = "Edit";
            $data['returned_status'] = $this->deliverynote->check_delivered_and_return_qty_equal($delivery_note_number);
            $data['invoice'] = $this->deliverynote->deliverynoteby_number($delivery_note_number);              
            $data['assigned_customer']  = get_customer_details_by_id($data['invoice']['customer_id']);
            $data['products'] = $this->deliverynote->deliverynote_products($delivery_note_number);
            // echo "<pre>"; print_r($data['products']); die();
            $salesorder_number = $data['invoice']['salesorder_number'];
            //write new funtion for available credit limit
            $data['creditlimtcompare']="";
            // $data['creditlimtcompare'] = $this->quote->compare_delivery_product_price_with_avail_credit_limit($salesorder_number);
            $data['return_status'] = $this->deliverynote->check_delivered_and_return_qty_equal($delivery_note_number);          
            $data['trackingdata'] = tracking_details('deliverynote_number',$delivery_note_number);  
            // $data['log'] = $this->quote->getnotehistory($delivery_note_number); 
            $data['images'] = get_uploaded_images('Deliverynote',$delivery_note_number);   
            $data['new_id'] ="";
            if($data['invoice']['created_by'])
            {
                $data['created_employee'] = employee_details_by_id($data['invoice']['created_by']);
            }
            $data['colorcode'] = ($data['invoice']['deliveryduedate']) ? get_color_code($data['invoice']['deliveryduedate']):"";

            
            //erp2024 06-01-2025 detailed history log starts
            $page = "Deliverynote";
            $data['detailed_log']= get_detailed_logs($delivery_note_number,$page);
            $products = $data['detailed_log'];
            $groupedBySequence = []; // Initialize an empty array for grouping
            if($products)
            {
                foreach ($products as $product) {
                    $sequence = $product['seqence_number'];
                    $groupedBySequence[$sequence][] = $product; // Group by sequence number
                }
                $data['groupedDatas'] = $groupedBySequence;
            }      
            $data['journals_records'] = $this->deliverynote->delivery_note_journal_records($delivery_note_number);
        }
        else{
            $data['invoice'] = [];
            $data['products'] = [];
            $data['created_employee']=[];
            $data['new_id'] = $this->deliverynote->deliverynote_number();
            $data['returned_status'] = 0;
        }
        

        $data['warehouse_title'] = (!empty($data['invoice']['store_id'])) ? $this->quote->warehouse_by_id($data['invoice']['store_id']) : warehouse_list();
       
       
        // $data['deliverynoteid'] = $this->quote->deliverynote_number($delivery_note_number);lastinvoice

        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Delivery Note ";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->quote->warehouses();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['configurations'] = $this->configurations;
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['tax_status']);        
        $data['prefix'] = $this->configurations['invoiceprefix'];
        $this->load->model('invoices_model', 'invocies');
        // $data['lastinvoice'] = $this->invocies->lastinvoice();

        /////////////////////////
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/delivery-note', $data);
        $this->load->view('fixed/footer');
    }
   

    public function reprintnote()
    {

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $delivery_note_number = $this->input->get('delivery', true);
        $salesorder_id = $this->input->get('sales', true);
        $customer_id = $this->input->get('cust', true);
        $priceFlg = $this->input->get('priceFlg', true);
        $data["salesorder_id"] = $salesorder_id;
        $client = "";
        $data['CustDetails'] = $this->invocies->salesorder_cust($customer_id);
        $data['salesman'] = $this->customers->customer_salesman($customer_id);
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Salesorder',$salesorder_id,'Converted to Deliverynote', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        if(!empty($data['CustDetails'])){
            $client = '' . $data['CustDetails'][0]['name'] . '<br>' . $data['CustDetails'][0]['address'] . ','. $data['CustDetails'][0]['city'] .' <br>' . $data['CustDetails'][0]['phone'] . '<br>' .$data['CustDetails'][0]['email'] ;
        
            $data["custId"]=$data['CustDetails'][0]['id'];
        }
        $data["client"]=$client;
       
        $loc = location($this->aauth->get_user()->loc);
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;
        $data['priceFlg']=$priceFlg;
         // ====================================================================
         $this->db->select('cberp_delivery_notes.created_date,cberp_delivery_notes.status,cberp_delivery_notes.salesorder_number,cberp_delivery_notes.delivery_note_number,cberp_delivery_notes.customer_po_reference,cberp_delivery_notes.discount');
         $this->db->from('cberp_delivery_notes');
         $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
         $query11 = $this->db->get();
         $res11 =  $query11->row_array();
         $data["deleverynote_createddate"] = $res11['created_date'];
         $data["discount"] = $res11['discount'];
         $data["deleverynote_status"] = $res11['status']; 
         $data["customer_po_reference"] = $res11['customer_po_reference'];
         $data["delivery_note_number"] = $res11['delivery_note_number'];
         $data["salesorder_refrencedet"] = $this->deliverynote->sales_reference($res11['salesorder_number']);
    
        // ==================================================================
        $this->db->select('cberp_delivery_notes.customer_id, cberp_delivery_notes.salesorder_number, cberp_delivery_notes.discount, cberp_delivery_notes.subtotal, cberp_delivery_notes.tax, cberp_delivery_notes.total_amount, cberp_delivery_note_items.*,cberp_products.product_code,cberp_product_description.product_name,cberp_products.unit AS productunit,cberp_delivery_note_items.quantity as product_qty');
        $this->db->from('cberp_delivery_notes');
        if(!empty($salesorder_id))
        {
            $this->db->join('cberp_delivery_note_items', 'cberp_delivery_note_items.salesorder_number = cberp_delivery_notes.salesorder_number AND cberp_delivery_note_items.delivery_note_number = cberp_delivery_notes.delivery_note_number');
            $this->db->where('cberp_delivery_notes.salesorder_number', $salesorder_id);
        }
        else{            
            $this->db->join('cberp_delivery_note_items', 'cberp_delivery_note_items.delivery_note_number = cberp_delivery_notes.delivery_note_number');
        }
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_delivery_note_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_products.product_code = cberp_product_description.product_code');
        
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
        $this->db->where('cberp_delivery_note_items.delivery_returned_quantity != cberp_delivery_note_items.quantity');
        $query = $this->db->get();
        $data['products'] = $query->result_array();
        $data['employee'] = $this->deliverynote->employee($this->session->userdata('id'));
        
        $html = $this->load->view('deliverynotes/deliverynotereprintpdf-' . LTR, $data, true);
         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('reprint-note' . $pay_acc . '.pdf', 'I');
        
            
    }
    

    public function delivery()
    {

        $tid = $this->input->get('id');

        $data['id'] = $tid;
        $data['title'] = "Invoice $tid";
        $data['invoice'] = $this->invocies->invoice_details($tid, $this->limited);
        if ($data['invoice']['id']) $data['products'] = $this->invocies->invoice_products($tid);
        if ($data['invoice']['id']) $data['employee'] = $this->invocies->employee($data['invoice']['eid']);

        ini_set('memory_limit', '64M');

        $html = $this->load->view('invoices/del_note', $data, true);

        //PDF Rendering
        $this->load->library('pdf');

        $pdf = $this->pdf->load();

        $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $tid . '</div>');

        $pdf->WriteHTML($html);

        if ($this->input->get('d')) {

            $pdf->Output('DO_#' . $data['invoice']['tid'] . '.pdf', 'D');
        } else {
            $pdf->Output('DO_#' . $data['invoice']['tid'] . '.pdf', 'I');
        }


    }

    //erp2024 delivery note to invoice 17-06-2024
    public function productsByDeliveryNoteID(){
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $this->session->unset_userdata('productsInDeliveryNote');
        $this->session->unset_userdata('selectednoteid');
        $selectedid = $this->input->post('devliverynoteid');
      
        $productIDs = $this->deliverynote->productidFromdeliverynote($selectedid);
        if (!empty($productIDs)) {
            $this->session->set_userdata('productsindeliverynote', $productIDs);
            $this->session->set_userdata('selectednoteid', $selectedid);
        }
    }
    public function products_in_selected_deliverynote(){
        $data = $this->session->userdata('productsindeliverynote');
       
        $tid = $this->session->userdata('selectednoteid');
        
        $activeData = [];
        foreach($data as $key => $pid){
            $this->db->select('product_id');
            $this->db->from('cberp_delivery_note_items');
            $this->db->where('product_id', $pid['product_id']);
            $this->db->where('delevery_note_id', $tid);
            // $this->db->where('status', 'Delivered');
            $query = $this->db->get();
            $result = $query->row_array();
            if(!empty($result)){
                $activeData[] = $pid;
            }
        }
        echo json_encode($activeData);
    }


    public function productidsearch()
	{
		$result = array();
		$out = array();
		$pid = $this->input->post('pid', true);
        $product_id = $pid['product_id'];
		// $query = $this->db->query("SELECT cberp_products.pid,cberp_products.product_name,cberp_products.product_price,cberp_products.product_code,cberp_products.taxrate,cberp_products.disrate,cberp_products.product_des,cberp_products.onhand_quantity,cberp_products.unit   FROM cberp_products WHERE cberp_products.pid = '$product_id'");
        $deleverynoteid = $this->session->userdata('selectednoteid');
        $this->db->select('
            cberp_products.pid,
            cberp_products.product_name,
            cberp_products.product_price,
            cberp_products.product_code,
            cberp_products.taxrate,
            cberp_products.disrate,
            cberp_products.product_des,
            cberp_products.onhand_quantity,
            cberp_products.unit,
            cberp_delivery_note_items.product_discount as deltotadiscount,
            cberp_delivery_note_items.product_qty AS reqestqty,
            cberp_delivery_note_items.discount_type AS del_discount_type,
            cberp_delivery_note_items.totaldiscount AS totaldiscount,
        ');
        $this->db->from('cberp_products');
        $this->db->join('cberp_delivery_note_items', 'cberp_delivery_note_items.product_id = cberp_products.pid');
        $this->db->where('cberp_products.pid', $product_id);
        $this->db->where('cberp_delivery_note_items.delevery_note_id', $deleverynoteid);
        $query = $this->db->get();
        // echo $this->db->last_query();
		$result = $query->result_array();
		foreach ($result as $row) {
				$name = array($row['product_name'], amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc), $row['pid'], amountFormat_general($row['taxrate']), amountFormat_general($row['deltotadiscount']), $row['product_des'], $row['unit'], $row['product_code'], intval($row['qty']), intval($row['reqestqty']), $row_num, @$row['serial'],$row['del_discount_type'],$row['totaldiscount']);
				array_push($out, $name);
		}
		echo json_encode($out);
		

	}

    public function deliverynotetoinvoice()
    {
        
      
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }

        $this->load->library("Common");

        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->invocies->lastinvoice();
        $data['warehouse'] = $this->invocies->warehouses();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "Delivery Note To Invoice";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['custom_fields'] = $this->custom->add_fields(2);
        $tid =  $this->session->userdata('selectednoteid');
        $data['master_data'] = $this->deliverynote->deliverynotedetails_byid($tid);
        // echo "<pre>"; print_r($data['']); die();
        $data['trackingdata'] = tracking_details('deliverynote_id',$data['master_data']['delevery_note_id']);
        // print_r($data['trackingdata']); die();
        $customerDetails = $this->deliverynote->customerByDeliverynoteid($tid);
        $data['custname'] = $customerDetails['name'];
        $data['phone'] = $customerDetails['phone'];
        $data['email'] = $customerDetails['email'];
        $data['address'] = $customerDetails['address'];
        $data['city'] = $customerDetails['city'];
        $data['region'] = $customerDetails['region'];
        $data['country'] = $customerDetails['country'];
        $data['customerid'] = $customerDetails['id'];        
        $data['configurations'] = $this->configurations;
        $data['customer_details'] = $customerDetails;        
        $data['prefix'] = $this->configurations['invoiceprefix'];
        // echo "<pre>"; print_r($data); die();
        
        $this->load->view('fixed/header', $head);
        $this->load->view('deliverynotes/deliverynotetoinvoice', $data);
        $this->load->view('fixed/footer');
    }

    public function convert_deliverynote_to_invoice()
    {

        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }

        $this->load->library("Common");

        $tid = $this->input->get('id');
        $this->load->model('customers_model', 'customers');
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->invocies->lastinvoice();
        $data['warehouse'] = $this->invocies->warehouses();
        $data['terms'] = $this->invocies->billingterms();
        $data['currency'] = $this->invocies->currencies();
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $head['title'] = "Delivery Note To Invoice";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['taxdetails'] = $this->common->taxdetail();
        $data['custom_fields'] = $this->custom->add_fields(2);
        $data['master_data'] = $this->deliverynote->deliverynotedetails_byid($tid);
        $data['products'] = $this->deliverynote->deliverynote_products($tid);
        // echo "<pre>"; print_r($data['master_data']); die();
        $data['trackingdata'] = tracking_details('deliverynote_id',$data['master_data']['delevery_note_id']);
        // print_r($data['trackingdata']); die();
        $customerDetails = $this->deliverynote->customerByDeliverynoteid($tid);
        $data['custname'] = $customerDetails['name'];
        $data['phone'] = $customerDetails['phone'];
        $data['email'] = $customerDetails['email'];
        $data['address'] = $customerDetails['address'];
        $data['city'] = $customerDetails['city'];
        $data['region'] = $customerDetails['region'];
        $data['country'] = $customerDetails['country'];
        $data['customerid'] = $customerDetails['id'];        
        $data['configurations'] = $this->configurations;
        $data['customer_details'] = $customerDetails;        
        $data['prefix'] = $this->configurations['invoiceprefix'];
        // echo "<pre>"; print_r($data); die();
        
        $this->load->view('fixed/header', $head);
        $this->load->view('deliverynotes/convert_deliverynotetoinvoice', $data);
        $this->load->view('fixed/footer');
    }
    
    
    


    public function actionconverttoinvoice()
    {
       
        $store_id = $this->input->post('s_warehouses');
        $warehouse_id1 = $this->input->post('store_id');
        $eid = $this->session->userdata('id');
        // $eid = $this->input->post('eid');
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invoicenumnber = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $invoice_number = $this->input->post('invoice_number');
        $transaction_number = $this->input->post('transaction_number');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $disc_val = numberClean($this->input->post('disc_val'));
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $project = $this->input->post('prjid');
        $total_tax = 0;
        // $total_discount = rev_amountExchange_s($this->input->post('after_disc'), $currency, $this->aauth->get_user()->loc);
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms', true);
        $order_discount = $this->input->post('order_discount', true);
        $delevery_note_id = $this->input->post('delevery_note_id', true);
        $delivery_note_number = $this->input->post('delivery_note_number', true);

        $i = 0;
        $totaldiscount=0;
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

        $this->load->model('plugins_model', 'plugins');
        $empl_e = $this->plugins->universal_api(69);
        if ($empl_e['key1']) {
            $emp = $this->input->post('employee');
        } else {
            $emp = $this->aauth->get_user()->id;
        }

        $transok = true;
        $st_c = 0;
        $this->load->library("Common");
        $this->db->trans_start();
        //Invoice Data
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $this->db->select('tid');
        $this->db->from('cberp_invoices');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $this->db->where('tid', $invocieno);
        $this->db->where('i_class', 0);
        $query = $this->db->get();
        if(@$query->row()->tid){
            $this->db->select('tid');
            $this->db->from('cberp_invoices');
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $this->db->where('i_class', 0);
            $query = $this->db->get();
            $invocieno=$query->row()->tid+1;
        }

        $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $emp, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'store_id'=>$warehouse_id1,'eid'=>$eid, 'delevery_note_id'=>$delevery_note_id,'invoice_number' => $invoice_number,'transaction_number'=>$transaction_number,'order_discount'=>$order_discount,'invoice_type'=>'Deliverynote'); 
        $invocieno2 = $invocieno;
		//$data['status']='due';
        $product_id1 = $this->input->post('pid');        
        $product_name2 = $this->input->post('product_name', true);
        $product_qty1 = $this->input->post('product_qty');
        

        


        foreach ($product_id1 as $key => $value1) {
            //erp2024 check transfer warehoues 13-06-2024
            $this->db->select('id,stock_qty');
            $this->db->from('cberp_product_to_store');
            $this->db->where('product_id', $product_id1[$key]);
            $this->db->where('store_id', $store_id);
            $checkquery = $this->db->get();
            // echo $this->db->last_query();   
            $check_result = $checkquery->row_array();                    
            $chekedID = (!empty($check_result))?$check_result['id']:"0";
            // erp2024 hide quantity check 08-07-2024 srats
            // if ((numberClean($check_result['stock_qty']) < $product_qty1[$key])) {               
            //     echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name2[$key] . "</strong> - Low quantity. Available stock is  " . numberClean($check_result['stock_qty'])));
            //     $transok = false;
            //     $st_c = 1;                        
            //     exit;
            // }
            // erp2024 hide quantity check 08-07-2024 ends
        }
        if($shipping)
        {
            $shipping_account_details = default_chart_of_account('shipping');
            $shipping_data1 = [
                'acid' => $shipping_account_details,
                'type' => 'Asset',
                'cat' => 'Deliverynote',
                'credit' => $shipping,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number
            ];
            $this->db->insert('cberp_transactions',$shipping_data1);
            $this->db->set('lastbal', 'lastbal - ' .$shipping, FALSE);
            $this->db->where('acn', $shipping_account_details);
            $this->db->update('cberp_accounts'); 

            $invoice_receivable_account_details = default_chart_of_account('accounts_receivable');
            $shipping_data_debit = [
                'acid' => $invoice_receivable_account_details,
                'type' => 'Asset',
                'cat' => 'Deliverynote',
                'debit' => $shipping,
                // 'debit' => $total,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->insert('cberp_transactions',$shipping_data_debit);
            $this->db->set('lastbal', 'lastbal + ' .$shipping, FALSE);
            $this->db->where('acn', $invoice_receivable_account_details);
            $this->db->update('cberp_accounts'); 
            
        }
        if ($this->db->insert('cberp_invoices', $data)) {            

            $invocieno = $this->db->insert_id();
            history_table_log('delivery_note_log','deliverynote_id',$delevery_note_id,'Convert to invoice');
            history_table_log('cberp_invoice_log','invoice_id',$invocieno,'Create');
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Deliverynote',$delevery_note_id,'Converted to Invoice', $_POST['changedFields']);
            detailed_log_history('Invoice',$invocieno,'Created', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 

            //$invoicenumnber insert data into tracking
            $this->db->select('invoice_id,invoice_number');
            $this->db->from('cberp_transaction_tracking');
            $this->db->like('deliverynote_id', $this->session->userdata('selectednoteid'));
            $existing = $this->db->get()->row_array();
            if(!empty($existing['invoice_id']) && !empty($existing['invoice_number'])) {
                $existing_sales_id = $existing['invoice_id'];
                $existing_sales_number = $existing['invoice_number'];
                $sales_id = $existing_sales_id . ',' . $invocieno;
                $sales_number = $existing_sales_number . ',' . $invoicenumnber;
                
                $this->db->like('deliverynote_id', $this->session->userdata('selectednoteid'));
                $this->db->update('cberp_transaction_tracking',['invoice_id'=>$sales_id,'invoice_number'=>$sales_number]);
            }
            else{
                $this->db->like('deliverynote_id', $this->session->userdata('selectednoteid'));
                $this->db->update('cberp_transaction_tracking',['invoice_id'=>$invocieno,'invoice_number'=>$invoicenumnber]);
            }


            //products cberp_delivery_notes
            $pid = $this->input->post('pid');
            $productlist = array();
            $productids = array();
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
            $income_account_number = $this->input->post('income_account_number');
            $discount_type = $this->input->post('discount_type');
            $discount = $this->input->post('discount');
            

            foreach ($pid as $key => $value) {
                $total_discount += numberClean($ptotal_disc[$key]);
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
                    'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'serial' => $product_serial[$key],
                    'account_number' => $income_account_number[$key],
                    'discount_type' => $discount_type[$key],
                    'discount' => $discount[$key],
                    'delevery_note_id'=>$delevery_note_id,
                    'delnote_number'=>$delivery_note_number
                );
                $productids[$prodindex] =  $product_id[$key];
                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;
                $amt = numberClean($product_qty[$key]);

                if ($product_id[$key] > 0) {                    

                    //erp2024 check transfer warehoues 13-06-2024
                    // $this->db->select('id,stock_qty');
                    // $this->db->from('cberp_product_to_store');
                    // $this->db->where('product_id', $product_id[$key]);
                    // $this->db->where('store_id', $store_id);
                    // $checkquery = $this->db->get();
                    // $check_result = $checkquery->row_array();                    
                    // $chekedID = (!empty($check_result))?$check_result['id']:"0";
                    // $transferqty = $amt;
                    

                    // if($chekedID>0){
                    //     $existingQty = $check_result['stock_qty'];
                    //     $current_stock = ($existingQty>0)? $existingQty-$transferqty :$transferqty;
                    //     $data3['stock_qty'] = $current_stock;
                    //     $data3['updated_by'] = $this->session->userdata('id');
                    //     $data3['updated_dt'] = date('Y-m-d H:i:s');
                    //     $this->db->where('id', $chekedID);
                    //     $this->db->update('cberp_product_to_store', $data3);
                    // }
                    //erp2024 check transfer warehoues 13-06-2024

                    // $this->db->set('qty', "qty-$amt", FALSE);
                    // $this->db->where('pid', $product_id[$key]);
                    // $this->db->update('cberp_products');

                    // if ((numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0 and $this->common->zero_stock()) {
                    //     echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
                    //     $transok = false;
                    //     $st_c = 1;
                    // }
                    
                }
                $itc += $amt;
            }


            if (count($product_serial) > 0) {
                $this->db->set('status', 1);
                $this->db->where_in('serial', $product_serial);
                $this->db->update('cberp_product_serials');
            }
            if ($prodindex > 0) {
                $tid = $this->session->userdata('selectednoteid');
              
                if(!empty($tid)){
                    $this->db->set('status', 'Invoiced');
                    $this->db->where_in('product_id', $productids);
                    $this->db->where('delevery_note_id', $tid);
                    $this->db->update('cberp_delivery_note_items'); 
                    
                    // $this->db->set('status', 'Invoiced');
                    // $this->db->set('invoice_number', $invoice_number);
                    // $this->db->where('delevery_note_id', $delevery_note_id);
                    // $this->db->update('cberp_delivery_notes');
                    
                }
                
                $this->db->insert_batch('cberp_invoice_items', $productlist);
                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                $this->db->where('id', $invocieno);
                $this->db->update('cberp_invoices');
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                $transok = false;
            }
          $tnote = '#' . $invocieno . '-' ;
          $d_trans = $this->plugins->universal_api(69);
            if ($d_trans['key2']) {
                $t_data = array(
                'type' => 'Income',
                'cat' => 'Sales',
                'payerid' => $customer_id,
                'method' => 'Auto',
                'date' => $bill_date,
                'eid' =>$emp,
                'tid' => $invocieno,
                'loc' =>$this->aauth->get_user()->loc
            );

            // $dual = $this->custom->api_config(65);
            // $this->db->select('holder');
            // $this->db->from('cberp_accounts');
            // $this->db->where('id', $dual['key2']);
            // $query = $this->db->get();
            // $account_d = $query->row_array();
            // $t_data['credit'] = 0;
            // $t_data['debit'] = $total;
            // $t_data['type'] = 'Expense';
            // $t_data['acid'] = $dual['key2'];
            // $t_data['account'] = $account_d['holder'];
            // $t_data['note'] = 'Debit ' . $tnote;

            // $this->db->insert('cberp_transactions', $t_data);
            // //account update
            // $this->db->set('lastbal', "lastbal-$total", FALSE);
            // $this->db->where('id', $dual['key2']);
            // $this->db->update('cberp_accounts');

        }
        if ($transok) {
            $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
            $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
            $invoicelink = base_url('invoices/view?id=' . $invocieno);
            $printlink = base_url('invoices/printinvoice?id=' . $invocieno);
            // $withstatus  = $this->deliverynote->itemcountwithstatus($this->session->userdata('selectednoteid'));
            // $withoutstatus  = $this->deliverynote->itemcountwithoutstatus($this->session->userdata('selectednoteid'));
            // if($withstatus==$withstatus){
                $this->db->set('status', 'Invoiced');
                $this->db->set('invoice_number', $invoice_number);
                $this->db->where('delevery_note_id', $delevery_note_id);
                $this->db->update('cberp_delivery_notes');
            // }
            echo json_encode(array('status' => 'Success', 'data' => $invocieno));
            // echo json_encode(array('status' => 'Success', 'message' =>
            //     $this->lang->line('Invoice Success') . " <a href='$invoicelink' class='btn btn-primary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> " . $this->lang->line('View') . "  </a> &nbsp; &nbsp;<a href='$printlink' class='btn btn-blue btn-sm' target='_blank'><span class='fa fa-print' aria-hidden='true'></span> " . $this->lang->line('Print') . "  </a> &nbsp;"));
        }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Invalid Entry!"));
            $transok = false;
        }

        if ($transok) {
          //  if ($this->aauth->premission(4) and $project > 0) {
                // $data = array('pid' => $project, 'meta_key' => 11, 'meta_data' => $invocieno, 'value' => '0');
                // $this->db->insert('cberp_project_meta', $data);
          //  }
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
                $this->db->where('id', $customer_id);
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
                $this->db->where('id', $customer_id);
                $query = $this->db->get();
                $customer = $query->row_array();
                $this->load->model('plugins_model', 'plugins');

                $invoice_sms = $this->send_sms_auto($invocieno, $invocieno2, $bill_date, $total, $currency);
                $mobile = $customer['phone'];
                $text_message = $invoice_sms['message'];
                $this->load->model('sms_model', 'sms');
                $this->sms->send_sms($mobile, $text_message, false);
            }

            //profit calculation
            $t_profit = 0;
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
            $data = array('type' => 9, 'rid' => $invocieno, 'col1' => $t_profit, 'd_date' => $bill_date);

            $this->db->insert('cberp_metadata', $data);

            $this->custom->save_fields_data($invocieno, 2);

        }

    }
    //erp2024 delivery note to invoice 17-06-2024 ends

    

    // erp2024 delivery note view page 08-07-2024 starts
    public function deliverynote_view()
    {
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;        
        $data['trackingdata'] = tracking_details('deliverynote_id',$tid);
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Delivery Note #" . $tid;
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['notemaster'] = $this->deliverynote->deliverynoteby_number($tid);
        $data['products'] = $this->deliverynote->deliverynote_products($tid);
        $data['configurations'] = $this->configurations;
        $data['delivery_return_status'] =  $this->deliverynote->check_delivered_and_return_qty_equal($tid);
        $this->load->view('fixed/header', $head);
        $this->load->view('deliverynotes/view-delivery-note', $data);
        $this->load->view('fixed/footer');
    }
    public function deliverynote_edit()
    {
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;        
        $data['trackingdata'] = tracking_details('deliverynote_id',$tid);
        
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Delivery Note #" . $tid;
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['notemaster'] = $this->deliverynote->deliverynoteby_number($tid);
        $data['products'] = $this->deliverynote->deliverynote_products($tid);
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('deliverynotes/edit-delivery-note', $data);
        $this->load->view('fixed/footer');
    }
    public function deliveryreturn()
    {
        $tid = intval($this->input->get('delivery'));
        $type = $this->input->get('type');
        $data['id'] = $tid;
        $data['currency'] = $this->quote->currencies();
        $head['title'] = "Delivery Return #" . $tid+1000;
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['notemaster'] = $this->deliverynote->deliverynoteby_number($tid);
        $data['products'] = $this->deliverynote->deliverynote_products_for_return($tid);
        $data['delivery_return_number'] = $this->deliverynote->last_delivery_return_number();
        $data['configurations'] = $this->configurations;
        $this->load->model('deliveryreturn_model', 'deliveryreturn');
        $data['deliverynote_status'] = $this->deliveryreturn->deliverynote_status($tid);
        
        $data['invoiceid'] = ($data['deliverynote_status']=='Invoiced') ? $this->deliveryreturn->invoice_details_by_delnoteid($tid):"";
        $data['trackingdata'] = tracking_details('deliverynote_id',$tid);
        $data['invoice_details'] = ($data['deliverynote_status']=="Invoiced") ? $this->deliveryreturn->invoice_details($tid) : "";
        
        // echo "<pre>"; print_r($data['deliverynote_status']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('deliverynotes/delivery-return', $data);
        $this->load->view('fixed/footer');
    }
    public function delivery_return_action()
    {
       
        $flg = 0;
        $grandtotal = 0;
        $grandtax = 0;
        $granddiscount = 0;
        $grandsubtotal = 0;
        $grandtax = 0;
        $return_qty = $this->input->post('return_qty', true);
        foreach($return_qty as $key1 =>$row){
            if($return_qty[$key1]>0){
                $flg = 1;
                break;
            }            
        }

        if($flg==1){
            $masterdata =[];
             
            $masterdata['customer_id'] = $this->input->post('customer_id', true);
            $masterdata['deliverynote_id'] = $this->input->post('delevery_note_id', true);
            $masterdata['store_id'] = $this->input->post('store_id', true);
            $masterdata['salesorder_id'] = $this->input->post('salesorder_id', true);
            $masterdata['delivery_note_status'] = $this->input->post('status', true);
            $masterdata['salesorder_number'] = !empty($masterdata['salesorder_id']) ? $masterdata['salesorder_id']+1000:'';
            $masterdata['shipping'] = numberClean($this->input->post('shipping', true));
            $masterdata['created_date'] = date('Y-m-d');
            $masterdata['created_time'] = date('H:i:s');
            $masterdata['created_by'] =  $this->session->userdata('id');
            $masterdata['order_discount'] = $this->input->post('order_discount', true);
            $masterdata['order_discount_percentage'] = $this->input->post('order_discount_percentage', true);
            $masterdata['total_amount'] = $this->input->post('total', true);
            
            $product_id = $this->input->post('product_id', true);
            $damaged_qty = $this->input->post('damaged_qty', true);
            // $product_price = $this->input->post('product_price', true);
            $totaltax = $this->input->post('taxa', true);
            $totaldiscount = $this->input->post('disca', true);
            $product_subtotal = $this->input->post('product_subtotal', true);
            $unit = $this->input->post('unit', true);
            $product_tax = $this->input->post('product_tax', true);
            $delivered_qty = $this->input->post('delivered_qty', true);
            $product_price = $this->input->post('product_price', true);
            $discount_type = $this->input->post('discount_type', true);
            $product_discount = $this->input->post('product_discount', true);
            $product_amt = $this->input->post('product_amt', true);
            $product_cost = $this->input->post('product_cost', true);
            $income_account_number = $this->input->post('income_account_number', true);
        
            $this->db->insert('cberp_delivery_returns', $masterdata);
            // die($this->db->last_query()); 
            $lastinsertid = $this->db->insert_id();
            $del = $this->input->post('delevery_note_id', true);
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Deliveryreturn',$lastinsertid,'Created', $changedFields);
            detailed_log_history('Deliverynote',$del,'Delivery Returned', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
            // erp2024 15-02-2025 load default accounts-
           
            //insert data to delivery return
            $this->db->select('delivery_return_number,delivery_return_number');
            $this->db->from('cberp_transaction_tracking');
            $this->db->like('deliverynote_id', $masterdata['deliverynote_id']);
            $existing = $this->db->get()->row_array();
            if(!empty($existing['delivery_return_number']) && !empty($existing['delivery_return_number'])) {
                $existing_sales_id = $existing['delivery_return_number'];
                $existing_sales_number = $existing['delivery_return_number'];
                $sales_id = $existing_sales_id . ',' . $lastinsertid;
                $sales_number = $existing_sales_number . ',' . $lastinsertid+1000;                
                $this->db->like('deliverynote_id', $masterdata['deliverynote_id']);
                $this->db->update('cberp_transaction_tracking',['delivery_return_number'=>$sales_id,'delivery_return_number'=>$sales_number]);
            }
            else{
                $this->db->like('deliverynote_id', $masterdata['deliverynote_id']);
                $this->db->update('cberp_transaction_tracking',['delivery_return_number'=>$lastinsertid,'delivery_return_number'=>$lastinsertid+1000]);
            }
         

            foreach($return_qty as $key=>$row){
                if($return_qty[$key]>0)
                {
                    $items = [];
                    $items['delivery_return_number'] = $lastinsertid;
                    $items['product_id'] = $product_id[$key];
                    $items['return_qty'] = $return_qty[$key];
                    $items['damaged_qty'] = ($damaged_qty[$key])?$damaged_qty[$key]:0;
                    $items['totaltax'] = numberClean($totaltax[$key]);
                    $items['totaldiscount'] = numberClean($totaldiscount[$key]);
                    $items['subtotal'] = numberClean($product_subtotal[$key]);
                    $items['product_price'] = numberClean($product_price[$key]);
                    if($discount_type[$key]=="Amttype"){
                        $discountamount = numberClean($product_amt[$key]);
                    }
                    else if($discount_type[$key]=="Perctype"){
                        $discountamount = numberClean($product_discount[$key]);
                    }
                    else{
        
                    }
                    $items['product_discount'] = $discountamount;
                    $items['discount_type'] = $discount_type[$key];
                    // Update the grand totals
                    $grandsubtotal = $grandsubtotal + $items['subtotal'];
                    $grandtax = $grandtax + $items['totaltax'];
                    $granddiscount = $granddiscount + $items['totaldiscount'];

                    $items['unit'] = $unit[$key];
                    $items['product_tax'] = numberClean($product_tax[$key]);
                    $items['delivered_qty'] = numberClean($delivered_qty[$key]);
                    $items['product_cost'] = ($product_cost[$key]);
                    $this->db->insert('cberp_delivery_return_items', $items);
                    // echo $this->db->last_query();

                     //erp2024  update delivered quantity to cberp_delivery_note_items table 09-07-2024
                     $this->deliverynote->delivered_qty_update_to_delivery_note_items_table($masterdata['deliverynote_id'], $items['product_id'], $return_qty[$key]);
                    //erp2024  update delivered quantity to cberp_delivery_note_items table 09-07-2024 ends 
                    
                    //erp2024 product stock update 09-07-2024
                    // $currentprdstock = $this->deliverynote->productstockbyid($items['product_id']);
                    // if($currentprdstock>0){            
                    //     $currentprdstock = $currentprdstock + $items['return_qty'];            
                    //     $this->db->where('pid', $items['product_id']);
                    //     $this->db->update('cberp_products', ['qty'=>$currentprdstock]);
                    // }


                    // $noteitems = [];
                    // $existinglist = $this->deliverynote->existingnotelist($masterdata['deliverynote_id'],$items['product_id']);
                    // if($existinglist>0){
                    //     $noteitems['product_qty'] = $existinglist-$items['return_qty'];
                    //     $noteitems['totaltax'] = $items['totaltax'];
                    //     $noteitems['subtotal'] = $items['subtotal'];
                    //     $noteitems['totaldiscount'] = $items['totaldiscount'];
                    //     $this->db->where('delevery_note_id', $masterdata['deliverynote_id']);
                    //     $this->db->where('product_id', $items['product_id']);
                    //     $this->db->update('cberp_delivery_note_items', $noteitems);
                    // }
                    
                }
            }            
            $granddata['subtotal'] = $grandsubtotal;
            $granddata['discount'] = $granddiscount;
            $granddata['tax'] = $grandtax;
            $this->db->where('delivery_return_number', $items['delivery_return_number']);
            $this->db->update('cberp_delivery_returns',$granddata);


            // erp2024 update customer table available credit limit 10-09-2024
            // $this->load->model('transactions_model', 'transactions');
            // $custdata = $this->transactions->check_customer_account_details($masterdata['customer_id']);
            // $custcredit_limit = $custdata['credit_limit'];
            // $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;

            // $subamount = $cust_avalable_credit_limit + $grandsubtotal;
            // $this->db->set('avalable_credit_limit', $subamount, FALSE);
            // $this->db->where('id', $masterdata['customer_id']);
            // $this->db->update('cberp_customers');
            // erp2024 update customer table available credit limit 10-09-2024 ends



            $link = '<a  href="' . base_url("DeliveryNotes"). '" class="btn btn-secondary btn-sm" title="Back to delivery notes"><i class="fa fa-arrow-left"></i> Back to delivery notes</a>';
            $returns = '<a  href="' . base_url("Deliveryreturn"). '" class="btn btn-secondary btn-sm" target="_blank" title="Deliveryreturn"><i class="fa fa-arrow-right"></i> Go to delivery returns</a>';
            
            echo json_encode(array('status' => 'Success', 'message' =>$this->lang->line('Delivery Return') . "&nbsp;".$link."&nbsp;".$returns));
            
        }
        else{
           echo json_encode(array('status' => 'Error', 'message' =>'Must enter at least one return quantity'));
        }
       

    }



    public function deliverynote_status_update()
    {
        $deliverynote_id = $this->input->post('delivery');
        $store_id = $this->input->post('store_id');
        $status = $this->input->post('status');
        $salesPro = $this->input->post('selectedProducts');
        $salesPro = $this->input->post('selectedProducts');
        $salesorder_number = $this->input->post('salesorder_number');

        $this->db->select('product_id,product_qty');
        $this->db->from('cberp_delivery_note_items');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.delevery_note_id = cberp_delivery_note_items.delevery_note_id');
        $this->db->where_in('cberp_delivery_note_items.product_id', $salesPro);
        $this->db->where('cberp_delivery_note_items.delevery_note_id', $deliverynote_id);
        $this->db->where('cberp_delivery_notes.status','Created');     
         
        $query = $this->db->get();      
        $result = $query->result_array();


        if(!empty($result)){
            // foreach($result as $item) {
            //     $pid = $item['product_id'];
            //     $qty = intval($item['product_qty']);
            //     $this->db->select('qty');
            //     $this->db->from('cberp_products');
            //     $this->db->where('pid', $pid);
            //     $prdQry = $this->db->get();        
            //     $prdresult = $prdQry->row_array();
            //     $this->update_warehouse_products($pid, $store_id, $qty);
            //     if ($prdresult) {
            //         $onhand = intval($prdresult['qty']);
            //         $updateQty = $onhand - $qty;          
            //         $upqty = array('qty' => $updateQty);
            //         $this->db->where('pid', $pid);
            //         $this->db->update('cberp_products', $upqty);
            //     }               
            // }
            
            // echo json_encode(array('status' => '1', 'message' => $this->lang->line('Inventory status updated')));
        }else{
            // echo json_encode(array('status' => '1', 'message' =>$this->lang->line('Inventory status failed')));
        }
        if($status!='Invoiced')
        {
            $this->db->update('cberp_delivery_notes',['status'=>'Printed'],['delevery_note_id'=>$deliverynote_id]);
        }
        
        echo json_encode(array('status' => 'Success'));
    }

    public function update_warehouse_products($product_code, $store_id, $qty)
    {
        $this->db->select('stock_quantity');
        $this->db->from('cberp_product_to_store');
        $this->db->where('product_code', $product_code);
        $this->db->where('store_id', $store_id);
        $warehouseQry = $this->db->get();  
        $warehouseresult = $warehouseQry->row_array();
        $onhandwh = intval($warehouseresult['stock_quantity']);
        $onhandwhQty = ($onhandwh > 0) ? $onhandwh - $qty : 0;
        $upqty1 = array('stock_quantity' => $onhandwhQty);
        $this->db->where('product_code', $product_code);
        $this->db->where('store_id', $store_id);
        $this->db->update('cberp_product_to_store', $upqty1);
    }

    public function check_customer_is_same(){
        $selectedIds = $this->input->post('selecteditems');
        $isSameCustomer = $this->deliverynote->check_customer_is_same($selectedIds);
    
        if ($isSameCustomer) {
            $this->session->set_userdata("selecteddelnoteids", $selectedIds);
            echo json_encode(array('status' => '1', 'data' => $selectedIds));
        } else {
            echo json_encode(array('status' => '0'));
        }
    }
    

    public function convert_multiple_deliverynotes_to_invoice()
    {
        
        $tid =  $this->session->userdata('selecteddelnoteids');
        if(!empty($tid))
        {
            $data['master_data'] = $this->deliverynote->deliverynotedetails_byid_for_multiple($tid);
            $data['orderamount'] = $this->deliverynote->order_amount_total_by_delivery_note_ids($tid);
            // echo "<pre>"; print_r($data['master_data']); die(); 
            // $data['trackingdata'] = tracking_details('deliverynote_id',$tid);
            $customerDetails = $this->deliverynote->customerByDeliverynoteid($tid[0]);
            $data['custname'] = $customerDetails['name'];
            $data['phone'] = $customerDetails['phone'];
            $data['email'] = $customerDetails['email'];
            $data['address'] = $customerDetails['address'];
            $data['city'] = $customerDetails['city'];
            $data['region'] = $customerDetails['region'];
            $data['country'] = $customerDetails['country'];
            $data['credit_limit'] = $customerDetails['credit_limit'];
            $data['credit_period'] = $customerDetails['credit_period'];
            $data['avalable_credit_limit'] = $customerDetails['avalable_credit_limit'];
            $data['customerid'] = $customerDetails['id'];        
            $data['configurations'] = $this->configurations;

            $data['exchange'] = $this->plugins->universal_api(5);
            $data['customergrouplist'] = $this->customers->group_list();
            $data['lastinvoice'] = $this->invocies->lastinvoice();
            $data['warehouse'] = $this->invocies->warehouses();
            $data['terms'] = $this->invocies->billingterms();
            $data['currency'] = $this->invocies->currencies();
            $head['title'] = "Merge Multiple Delivery Notes into a Single Invoice";
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['prefix'] = $this->configurations['invoiceprefix'];
            
        }
       
        
        $this->load->view('fixed/header', $head);
        $this->load->view('deliverynotes/multiple_deliverynotetoinvoice', $data);
        $this->load->view('fixed/footer');
    }

    public function actionconverttomultipleinvoice()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        
        $eid = $this->input->post('eid');
        $warehouse_id1 = $this->input->post('store_id');
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invoicenumnber = $this->input->post('invocieno');        
        $invoice_number = $this->input->post('invoice_number');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $disc_val = numberClean($this->input->post('disc_val'));
        $subtotal = numberClean($this->input->post('subtotal'));
        $shipping = numberClean($this->input->post('shipping'));
        $shipping_tax = numberClean($this->input->post('ship_tax'));
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $total = numberClean($this->input->post('total'));
        $project = $this->input->post('prjid');
        $total_tax = 0;
        // $total_discount = numberClean($this->input->post('after_disc'));
        $total_discount=0;
        $discountFormat = $this->input->post('discountFormat');
        $term = $this->input->post('term', true);
        $refer = $this->input->post('refer', true);

        //erp2024 09-12-2024
        $order_discount = $this->input->post('order_discount', true);
        
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

      
        $transok = true;
        $st_c = 0;
        $this->load->library("Common");
        $this->db->trans_start();
        //Invoice Data tracking
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);

        $this->db->select('tid');
        $this->db->from('cberp_invoices');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $this->db->where('tid', $invocieno);
        $this->db->where('i_class', 0);
        $query = $this->db->get();
        $data = array('tid' => $invocieno,'invoice_number'=>$invoice_number, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount_rate' => $disc_val, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id,  'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat,   'multi' => $currency, 'loc' => $this->aauth->get_user()->loc,'store_id'=>$warehouse_id1,'eid'=>$eid, 'refer'=>$refer, 'term'=>$term,'order_discount'=>$order_discount,'invoice_type'=>'Deliverynote');
     
        $invocieno2 = $invocieno;
        if ($this->db->insert('cberp_invoices', $data)) {

            $invocieno = $this->db->insert_id();
            history_table_log('cberp_invoice_log','invoice_id',$invocieno,'Create');
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Invoice',$invocieno,'Created', $changedFields);
            //erp2024 06-01-2025 detailed history log ends 
            // $this->db->where('deliverynote_id', $this->session->userdata('selectednoteid'));
            // $this->db->update('cberp_transaction_tracking',['invoice_id'=>$invocieno,'invoice_number'=>$invoicenumnber]);
            $pid = $this->input->post('pid');
            $productlist = array();
            $productids = array();
            $prodindex = 0;
            $itc = 0;
            $product_id = $this->input->post('pid');
            $product_name1 = $this->input->post('product_name', true);
            $product_qty = $this->input->post('product_qty');
            $product_price = $this->input->post('product_price');
            // $product_tax = $this->input->post('product_tax');
            $product_discount = $this->input->post('product_discount');
            $product_subtotal = $this->input->post('product_subtotal');
            $ptotal_tax = $this->input->post('taxa');
            $ptotal_disc = $this->input->post('disca');
            // $product_des = $this->input->post('product_description', true);
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn');
            $product_alert = $this->input->post('alert');
            $product_alert = $this->input->post('alert');
            $delevery_note_ids = $this->input->post('delevery_note_ids');
            // $product_serial = $this->input->post('serial');
            

            //$invoicenumnber insert data into tracking
            $this->db->select('invoice_id,invoice_number');
            $this->db->from('cberp_transaction_tracking');
            $this->db->like('deliverynote_id', $this->session->userdata('selectednoteid'));
            $existing = $this->db->get()->row_array();
            if(!empty($existing['invoice_id']) && !empty($existing['invoice_number'])) {
                $existing_sales_id = $existing['invoice_id'];
                $existing_sales_number = $existing['invoice_number'];
                $sales_id = $existing_sales_id . ',' . $invocieno;
                $sales_number = $existing_sales_number . ',' . $invoicenumnber;
                
                $this->db->like('deliverynote_id', $this->session->userdata('selectednoteid'));
                $this->db->update('cberp_transaction_tracking',['invoice_id'=>$sales_id,'invoice_number'=>$sales_number]);
            }
            else{
                $this->db->like('deliverynote_id', $this->session->userdata('selectednoteid'));
                $this->db->update('cberp_transaction_tracking',['invoice_id'=>$invocieno,'invoice_number'=>$invoicenumnber]);
            }

            

            foreach ($pid as $key => $value) {
                $total_discount += numberClean($ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                $data = array(
                    'tid' => $invocieno,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'code' => $product_hsn[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    // 'tax' => numberClean($product_tax[$key]),
                    'discount' => numberClean($product_discount[$key]),
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => $ptotal_disc[$key],
                    // 'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'delevery_note_id' => $delevery_note_ids[$key],
                    // 'serial' => $product_serial[$key]
                );
                $productids[$prodindex] =  $product_id[$key];
                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;
                $amt = numberClean($product_qty[$key]);

                if ($product_id[$key] > 0) {                    

                    //erp2024 check transfer warehoues 13-06-2024
                    // $this->db->select('id,stock_qty');
                    // $this->db->from('cberp_product_to_store');
                    // $this->db->where('product_id', $product_id[$key]);
                    // $this->db->where('store_id', $store_id);
                    // $checkquery = $this->db->get();
                    // $check_result = $checkquery->row_array();                    
                    // $chekedID = (!empty($check_result))?$check_result['id']:"0";
                    // $transferqty = $amt;
                    

                    // if($chekedID>0){
                    //     $existingQty = $check_result['stock_qty'];
                    //     $current_stock = ($existingQty>0)? $existingQty-$transferqty :$transferqty;
                    //     $data3['stock_qty'] = $current_stock;
                    //     $data3['updated_by'] = $this->session->userdata('id');
                    //     $data3['updated_dt'] = date('Y-m-d H:i:s');
                    //     $this->db->where('id', $chekedID);
                    //     $this->db->update('cberp_product_to_store', $data3);
                    // }
                    //erp2024 check transfer warehoues 13-06-2024

                    // $this->db->set('qty', "qty-$amt", FALSE);
                    // $this->db->where('pid', $product_id[$key]);
                    // $this->db->update('cberp_products');

                    // if ((numberClean($product_alert[$key]) - $amt) < 0 and $st_c == 0 and $this->common->zero_stock()) {
                    //     echo json_encode(array('status' => 'Error', 'message' => 'Product - <strong>' . $product_name1[$key] . "</strong> - Low quantity. Available stock is  " . $product_alert[$key]));
                    //     $transok = false;
                    //     $st_c = 1;
                    // }
                    
                }
                $itc += $amt;
            }

      


            // if (count($product_serial) > 0) {
            //     $this->db->set('status', 1);
            //     $this->db->where_in('serial', $product_serial);
            //     $this->db->update('cberp_product_serials'); Invalid Entry
            // }
            if ($prodindex > 0) {
                for($i=0;$i<sizeof($delevery_note_ids);$i++)
                {
                    history_table_log('delivery_note_log','deliverynote_id',$delevery_note_ids[$i],'Convert to invoice');  
                    //erp2024 06-01-2025 detailed history log starts
                    detailed_log_history('Deliverynote',$delevery_note_ids[$i],'Convert to invoice', $changedFields);
                     //erp2024 06-01-2025 detailed history log ends  
                }
                
                $this->db->set('status', 'Invoiced');
                $this->db->where_in('product_id', $productids);
                $this->db->where_in('delevery_note_id', $delevery_note_ids);
                $this->db->update('cberp_delivery_note_items');

                $this->db->set('status', 'Invoiced');
                $this->db->set('invoice_number', $invoice_number);
                $this->db->where_in('delevery_note_id', $delevery_note_ids);
                $this->db->update('cberp_delivery_notes');

                $this->db->insert_batch('cberp_invoice_items', $productlist);
                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                $this->db->where('id', $invocieno);
                $this->db->update('cberp_invoices');
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                $transok = false;
            }
          $tnote = '#' . $invocieno . '-' ;
          $d_trans = $this->plugins->universal_api(69);
            if ($d_trans['key2']) {
                $t_data = array(
                'type' => 'Income',
                'cat' => 'Sales',
                'payerid' => $customer_id,
                'method' => 'Auto',
                'date' => $bill_date,
                'eid' =>$emp,
                'tid' => $invocieno,
                'loc' =>$this->aauth->get_user()->loc
            );


        }
        if ($transok) {
            $validtoken = hash_hmac('ripemd160', $invocieno, $this->config->item('encryption_key'));
            $link = base_url('billing/view?id=' . $invocieno . '&token=' . $validtoken);
            $invoicelink = base_url('invoices/view?id=' . $invocieno);
            $printlink = base_url('invoices/printinvoice?id=' . $invocieno);
            // $withstatus  = $this->deliverynote->itemcountwithstatus($this->session->userdata('selectednoteid'));
            // $withoutstatus  = $this->deliverynote->itemcountwithoutstatus($this->session->userdata('selectednoteid'));
            // if($withstatus==$withstatus){
            //     $this->db->set('status', 'Invoiced');
            //     $this->db->where_in('delevery_note_id', $this->session->userdata('selecteddelnoteids'));
            //     $this->db->update('cberp_delivery_notes');
               
            // }
            echo json_encode(array('status' => 'Success', 'data' =>$invocieno));
        }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Invalid Entry!"));
            $transok = false;
        }
        if ($transok) {
        //    if ($this->aauth->premission(4) and $project > 0) {
                // $data = array('pid' => $project, 'meta_key' => 11, 'meta_data' => $invocieno, 'value' => '0');
                // $this->db->insert('cberp_project_meta', $data);
          //  }
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
                $this->db->where('id', $customer_id);
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
                $this->db->where('id', $customer_id);
                $query = $this->db->get();
                $customer = $query->row_array();
                $this->load->model('plugins_model', 'plugins');

                $invoice_sms = $this->send_sms_auto($invocieno, $invocieno2, $bill_date, $total, $currency);
                $mobile = $customer['phone'];
                $text_message = $invoice_sms['message'];
                $this->load->model('sms_model', 'sms');
                $this->sms->send_sms($mobile, $text_message, false);
            }

            //profit calculation
            $t_profit = 0;
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
            $data = array('type' => 9, 'rid' => $invocieno, 'col1' => $t_profit, 'd_date' => $bill_date);

            $this->db->insert('cberp_metadata', $data);

            $this->custom->save_fields_data($invocieno, 2);

        }

    }
    

    // #erp2024 delivery return approval 11-09-2024
    public function delivery_return_approval_action()
    {
    
    //    ini_set('display_errors', 1);
    //     ini_set('display_startup_errors', 1);
    //     error_reporting(E_ALL);  
        $flg = 0;
        $grandtotal = 0;
        $grandtax = 0;
        $granddiscount = 0;
        $grandsubtotal = 0;
        $grandtax = 0;
        $grand_product_cost = 0;
        $return_qty = $this->input->post('return_qty', true);
        // print_r($return_qty); die();
        foreach($return_qty as $key1 =>$row){
            if($return_qty[$key1]>0){
                $flg = 1;
                break;
            }            
        }
        if($flg==1){
            
            $transaction_number = get_latest_trans_number();
            $delivery_return_number = $this->input->post('delivery_return_number', true);
            $deliverynote_status = $this->input->post('deliverynote_status', true);
            $masterdata =[];
            $masterdata['approved_date'] = date('Y-m-d H:i:s');
            $masterdata['approval_flg'] = "1";
            $masterdata['status'] = "Approved";
            $masterdata['delivery_note_status'] = $deliverynote_status;
            $masterdata['approval_comments'] = $this->input->post('approval_comments', true);
            $masterdata['approved_by'] =  $this->session->userdata('id');
            $masterdata['transaction_number'] =  $transaction_number;
            
            $this->db->update('cberp_delivery_returns',$masterdata,['delivery_return_number'=>$delivery_return_number]);
            
            $total = $this->input->post('total', true);
            $product_id = $this->input->post('product_id', true);
            $invoice_number = $this->input->post('invoice_number', true);
            $product_price = $this->input->post('product_price', true);
            $damaged_qty = $this->input->post('damaged_qty', true);
            $totaldiscount = $this->input->post('disca', true);
            $product_subtotal = $this->input->post('product_subtotal', true);
            $store_id = $this->input->post('store_id', true);
            $customer_id = $this->input->post('customer_id', true);
            $income_account_number = $this->input->post('income_account_number', true);
            // $transaction_number = $this->input->post('transaction_number', true);
            $order_discount = $this->input->post('order_discount', true);
            $product_cost = $this->input->post('product_cost', true);
            $order_discount_percentage = $this->input->post('order_discount_percentage', true);
             
            // erp2024 19-12-2024 load default accounts
            $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
            $default_inventory_account = default_chart_of_account('inventory');
            foreach($return_qty as $key=>$row){
                if($return_qty[$key]>0)
                {
                    $items = [];
                    $items['product_id'] = $product_id[$key];
                    $items['approved_return_qty'] = $return_qty[$key];
                    $items['approved_damaged_qty'] = ($damaged_qty[$key])?$damaged_qty[$key]:0;
                    $items['approved_return_amount'] = rev_amountExchange_s($product_subtotal[$key], 0, $this->aauth->get_user()->loc);
                    // echo $product_id[$key];
                    // print_r($items);
                    // Update the grand totals
                    $grandsubtotal = $grandsubtotal + $product_subtotal[$key];
                    $grandtax = $grandtax + $items['totaltax'];
                    $granddiscount = $granddiscount + $totaldiscount[$key];
                    $this->db->update('cberp_delivery_return_items',$items,['delivery_return_number'=>$delivery_return_number, 'product_id' => $items['product_id']]);
                    //erp2024 06-01-2025 detailed history log starts
                    detailed_log_history('Deliveryreturn',$delivery_return_number,'Return Approved', $_POST['changedFields']);
                    //erp2024 06-01-2025 detailed history log ends 


                     //erp2024  update delivered quantity to cberp_delivery_note_items table 09-07-2024
                     //$this->deliverynote->delivered_qty_update_to_delivery_note_items_table($masterdata['deliverynote_id'], $items['product_id'], $return_qty[$key]);
                    //erp2024  update delivered quantity to cberp_delivery_note_items table 09-07-2024 ends 
                    
                    //erp2024 product stock update 09-07-2024
                    $currentprdstock = $this->deliverynote->productstockbyid($items['product_id']);

                    // if($currentprdstock>0){      
                        $actualupdatestock = $items['approved_return_qty'] -  $items['approved_damaged_qty'];                        
                        
                        $currentprdstock = $currentprdstock + $actualupdatestock;  
                            
                        $this->db->where('pid', $items['product_id']);
                        $this->db->update('cberp_products', ['qty'=>$currentprdstock]);
                            
                    // }
                    $warehouseprdstock = $this->deliverynote->warehouseprdstock_byprdid($items['product_id']);
                    $warhouse_prdstock = $warhouse_prdstock + $actualupdatestock;            
                    $this->db->where('store_id', $store_id);
                    $this->db->where('product_id', $items['product_id']);
                    $this->db->update('cberp_product_to_store', ['stock_qty'=>$currentprdstock]);

                   
                    $actulprice1 = $product_price[$key]*$return_qty[$key];
                    $order_discount_perc = convert_order_discount_percentage_to_amount($actulprice1,$order_discount_percentage);
                    $actulprice = $actulprice1;
                    // $actulprice = $product_subtotal[$key]-$order_discount_perc;

                    //commented on 15-02-2025
                    // $producttransdata =  [
                    //     'acid' => $income_account_number[$key],
                    //     'type' => 'Asset',
                    //     'cat' => 'Delivery Return',
                    //     'debit' => $actulprice,
                    //     'eid' => $this->session->userdata('id'),
                    //     'date' => date('Y-m-d'),
                    //     'transaction_number'=>$transaction_number,
                    //     // 'invoice_number'=>$invoice_number
                    // ];
                    // $this->db->set('lastbal', 'lastbal + ' . $actulprice, FALSE);
                    // $this->db->where('acn', $income_account_number[$key]);
                    // $this->db->update('cberp_accounts'); 
                    // $this->db->insert('cberp_transactions', $producttransdata);
                    // $this->db->update('cberp_transactions', $producttransdata,['transaction_number'=>$transaction_number,'acid'=>$income_account_number[$key]]);

                    
                    // cost of goods transaction
                    $total_product_cost = $product_cost[$key]*numberClean($return_qty[$key]);
                    $grand_product_cost += numberClean($total_product_cost);

                   
                    
                }
            }    


            // erp2024 transactions starts 25-10-2024
              
            // $invoice_receivable_account_details = default_chart_of_account('accounts_receivable');
            // $receivable_data = [
            //     'acid' => $invoice_receivable_account_details,
            //     'type' => 'Asset',
            //     'cat' => 'Delivery Return',
            //     'credit' => $total,
            //     'eid' => $this->session->userdata('id'),
            //     'date' => date('Y-m-d'),
            //     'transaction_number'=>$transaction_number,
            // ];
            // $this->db->insert('cberp_transactions',$receivable_data);
    
            // $this->db->set('lastbal', 'lastbal - ' .$total, FALSE);
            // $this->db->where('acn', $invoice_receivable_account_details);
            // $this->db->update('cberp_accounts'); 
            // erp2024 transactions ends 25-10-2024 order_discount

            //erp2024 totaldiscount transaction 11-11-2024 starts
            // if($granddiscount>0)
            // {
            //     $discount_account_details = default_chart_of_account('sales_discount');
            //     $discount_data = [
            //         'acid' => $discount_account_details,
            //         'type' => 'Asset',
            //         'cat' => 'Delivery Return',
            //         'credit' => $granddiscount,
            //         'eid' => $this->session->userdata('id'),
            //         'date' => date('Y-m-d'),
            //         'transaction_number'=>$transaction_number,
            //         // 'invoice_number'=>$invoice_number
            //     ];
     
            //     $this->db->insert('cberp_transactions',$discount_data);
            //     $this->db->set('lastbal', 'lastbal - ' .$granddiscount, FALSE);
            //     $this->db->where('acn', $discount_account_details);
            //     $this->db->update('cberp_accounts'); 
            // }

            // if($order_discount)
            // {
            //     $order_discount_account_number = default_chart_of_account('order_discount');
            //     $discount_data1 = [
            //         'acid' => $order_discount_account_number,
            //         'type' => 'Asset',
            //         'cat' => 'Delivery Return',
            //         'credit' => $order_discount,
            //         'eid' => $this->session->userdata('id'),
            //         'date' => date('Y-m-d'),
            //         'transaction_number'=>$transaction_number,
            //         // 'invoice_number'=>$invoice_number
            //     ];
            //     $this->db->insert('cberp_transactions',$discount_data1);
            //     $this->db->set('lastbal', 'lastbal - ' .$order_discount, FALSE);
            //     $this->db->where('acn', $order_discount_account_number);
            //     $this->db->update('cberp_accounts'); 
            // }

            // erp2024 update customer table available credit limit 10-09-2024
            if($deliverynote_status != 'Invoiced')
            {
                $this->load->model('transactions_model', 'transactions');
                $custdata = $this->transactions->check_customer_account_details($customer_id);
                $custcredit_limit = $custdata['credit_limit'];
                $cust_avalable_credit_limit = (!empty($custdata['avalable_credit_limit'])) ? $custdata['avalable_credit_limit']: 0;

                $subamount = $cust_avalable_credit_limit + $grandsubtotal;
                $this->db->set('avalable_credit_limit', $subamount, FALSE);
                $this->db->where('id', $customer_id);
                $this->db->update('cberp_customers');
            }


            $cost_of_goods_data =  [
                'acid' => $default_cost_of_goods_account,
                'type' => 'Expense',
                'cat' => 'Delivery Return',
                'credit' => $grand_product_cost,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->set('lastbal', 'lastbal - ' . $grand_product_cost, FALSE);
            $this->db->where('acn', $default_cost_of_goods_account);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions', $cost_of_goods_data);

            // Inventory transaction
            $inventory_data =  [
                'acid' => $default_inventory_account,
                'type' => 'Asset',
                'cat' => 'Delivery Return',
                'debit' => $grand_product_cost,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d'),
                'transaction_number'=>$transaction_number,
            ];
            $this->db->set('lastbal', 'lastbal + ' . $grand_product_cost, FALSE);
            $this->db->where('acn', $default_inventory_account);
            $this->db->update('cberp_accounts'); 
            $this->db->insert('cberp_transactions', $inventory_data);
            // erp2024 update customer table available credit limit 10-09-2024 ends

            
            echo json_encode(array('status' => 'Success'));
            
        }
        else{
           echo json_encode(array('status' => 'Error', 'message' =>'Must enter at least one return quantity'));
        }
       

    }


    public function print_picking_list()
    {

       
        $delivery_note_number = $this->input->get('delivery', true);
        $salesorder_number = $this->input->get('sales', true);
        $customer_id = $this->input->get('cust', true);
        $priceFlg = $this->input->get('priceFlg', true);
        $data["salesorder_number"] = $salesorder_number;
        $client = "";
        $data['CustDetails'] = $this->invocies->salesorder_cust($customer_id);
        $data['salesman'] = $this->customers->customer_salesman($customer_id);
        if(!empty($data['CustDetails'])){
            $client = '' . $data['CustDetails'][0]['name'] . '<br>' . $data['CustDetails'][0]['address'] . ','. $data['CustDetails'][0]['city'] .' <br>' . $data['CustDetails'][0]['phone'] . '<br>' .$data['CustDetails'][0]['email'] ;
        
            $data["custId"]=$data['CustDetails'][0]['customer_id'];
        }
        $data["client"]=$client;
       
        $loc = location($this->aauth->get_user()->loc);
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;
        $data['priceFlg']=$priceFlg; 
        // ====================================================================
        $this->db->select('cberp_delivery_notes.created_date,cberp_delivery_notes.salesorder_number,cberp_delivery_notes.delivery_note_number');
        $this->db->from('cberp_delivery_notes');
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
        $query11 = $this->db->get();
        $res11 =  $query11->row_array();
        $data["deleverynote_createddate"] = $res11['created_date'];
        $data["salesorder_number"] = $res11['delivery_note_number'];
        // ==================================================================
        $this->db->select('cberp_delivery_notes.customer_id, cberp_delivery_notes.salesorder_number, cberp_delivery_notes.discount, cberp_delivery_notes.subtotal, cberp_delivery_notes.tax, cberp_delivery_notes.total_amount, cberp_delivery_note_items.*,cberp_products.product_code,cberp_product_description.product_name,cberp_products.unit AS productunit,cberp_product_locations.aisel,cberp_product_locations.rack_number,cberp_product_locations.shelf_number,cberp_product_locations.bin_number');
        $this->db->from('cberp_delivery_notes');
        $this->db->join('cberp_delivery_note_items', 'cberp_delivery_note_items.salesorder_number = cberp_delivery_notes.salesorder_number AND cberp_delivery_note_items.delivery_note_number = cberp_delivery_notes.delivery_note_number');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_delivery_note_items.product_code');

        $this->db->join('cberp_product_locations', 'cberp_product_locations.product_code = cberp_products.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');

        $this->db->where('cberp_delivery_notes.salesorder_number', $salesorder_number);
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
        $this->db->where('cberp_delivery_note_items.delivery_returned_quantity != cberp_delivery_note_items.quantity');        
        $query = $this->db->get();
        $data['employee'] = $this->deliverynote->employee($this->session->userdata('id'));
        $data['products'] = $query->result_array();
         $html = $this->load->view('deliverynotes/printpickinglist-' . LTR, $data, true);
         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('reprint-note1' . $pay_acc . '.pdf', 'I');
        
            
    }


    public function pickticket_print_status()
    {
        $delevery_note_id = $this->input->post('delevery_note_id');
        $this->db->update('cberp_delivery_notes', ['pick_ticket_status' => '1'], ['delevery_note_id'=> $delevery_note_id]);

        echo json_encode(array('status' => 'success'));
    }
    

    // erp2024 filter count 16-10-2024
    
    public function get_deliverynote_count_filter()
    {
        $filter_status = $this->input->post('filter_status');
        
        $filter_expiry_date_from = !empty($this->input->post('filter_expiry_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_from'))) : ""; 

        $filter_expiry_date_to = !empty($this->input->post('filter_expiry_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_to'))) : "";

        $filter_price_from = !empty($this->input->post('filter_price_from')) ? $this->input->post('filter_price_from') : 0;
        $filter_price_to = !empty($this->input->post('filter_price_to')) ? $this->input->post('filter_price_to'): 0;

        $filter_customer = !empty($this->input->post('filter_customer')) ?$this->input->post('filter_customer') : "";
        $salesorder_number = !empty($this->input->post('salesorder_number')) ?$this->input->post('salesorder_number') : "";
 
        $results = $this->deliverynote->get_deliverynote_count_filter('created_date','total_amount',$filter_status,$filter_expiry_date_from,$filter_expiry_date_to,$filter_price_from,$filter_price_to,$salesorder_number,$filter_customer);        
        foreach ($results as $key => $value) {
            if (empty($value)) {
                $results[$key] = 0;
            }
        }        
        echo json_encode(array('status' => 'success','data'=>$results));
    }

    //erp2024 21-10-2024 CREATE SALESORDER starts 
    public function create2()
    {
    //    $data['permissions'] = load_permissions('Sales','Sales','Delivery Notes','Create Page');
    //    print_r($data['permissions']); die();
        $this->load->model('plugins_model', 'plugins');
        $data['emp'] = $this->plugins->universal_api(69);
        if ($data['emp']['key1']) {
            $this->load->model('employee_model', 'employee');
            $data['employee'] = $this->employee->list_employee();
        }
        $prefix = $this->plugins->universal_api(72);
        $data['prefix'] = $prefix['name'];
        $this->load->library("Common");
        $data['retailflg'] = 0;
        $delevery_note_id = $this->input->get('id', true);
        if(!empty($delevery_note_id))
        {
            $data['delnotedetails'] = $this->deliverynote->deliverynotedetails_byid($delevery_note_id);
            $data['products'] = $this->deliverynote->deliverynote_products($delevery_note_id);          
            // echo "<pre>"; print_r($data['delnotedetails']); die();
            $data['customer'] = $this->deliverynote->customerByDeliverynoteid($delevery_note_id);
            $data['retailflg'] = 1;
        }
        $data['id'] = $this->deliverynote->deliverynote_number();
        $data['currency'] = $this->quote->currencies();
        // $head['title'] = "Quote To Sales Order #" . $data['invoice']['tid'];
        $head['title'] = "Create new Delivery Note";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = warehouse_list();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);                
        $data['configurations'] = $this->configurations;
        $this->load->view('fixed/header', $head);
        $this->load->view('deliverynotes/create-new-deliverynote', $data);
        $this->load->view('fixed/footer');
    }

        
    public function deliverynote_save_and_new_action()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  

        //commented all accounts transactions except - inventory and cost of goods(07-02-2025)
        $masterdata = [];
        $order_discount=0;
        $tax =0;
        $transaction_number = get_latest_trans_number();
        $discountchecked = $this->input->post('discountchecked');
    
        if (isset($discountchecked)) {
            $discountchecked = $discountchecked;
        } else {
            $discountchecked = 0;
        }
        $customer_id = $this->input->post('customer_id');
        $delivery_note_number = $this->input->post('invocieno_demo');
        $total_tax = 0;
        $total_discount = 0;
        $proposal = $this->input->post('proposal');
    
        $currency = $this->input->post('currency');
        $note = $this->input->post('note');
        $store_id = $this->input->post('s_warehouses');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = numberClean($this->input->post('subtotal'));
        $shipping = numberClean($this->input->post('shipping'));
        $shipping_tax = numberClean($this->input->post('ship_tax'));
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $reference_date = $this->input->post('reference_date', true);
        $total = numberClean($this->input->post('total'));
        $order_discount = (numberClean($this->input->post('order_discount'))) ? numberClean($this->input->post('order_discount')) : 0.00;

        
        // 24-10-2024 erp2024 newlyadded fields
        $customer_po_reference = $this->input->post('customer_po_reference', true);
        $customer_contact_person = $this->input->post('customer_contact_person', true);
        $customer_contact_number = $this->input->post('customer_contact_number', true);
        $customer_contact_email = $this->input->post('customer_contact_email', true);
        $delivery_note_number = $this->input->post('delivery_note_number', true);
        $salesorder_number = $this->input->post('salesorder_number', true);
        $action_type = $this->input->post('action_type', true);
        $created_date = $this->input->post('created_date', true);
        $created_date = ($created_date) ? $created_date : date('Y-m-d H:i:s');
        // 24-10-2024 erp2024 newlyadded fields
       
        
        $shop_type = $this->input->post('shoptype');
        
        //    echo $customer_id; die();
        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('Please add a new client')));
            exit;
        }
    
        $masterdata = [
            'shipping' => $shipping,
            'tax' => $tax,
            'note' => $note,
            'status' => 'Created',
            'customer_id' => $customer_id,
            'store_id' => $store_id,
            'reference' => $refer,
            'reference_date' => $reference_date,
            'shop_type' => $shop_type,
            'customer_contact_person' => $customer_contact_person,
            'customer_contact_number' => $customer_contact_number,
            'customer_contact_email' => $customer_contact_email,
            'order_discount' => $order_discount,
            'transaction_number'=>$transaction_number,
            'due_date'=> $this->input->post('deliveryduedate', true),            
        ];

        if($shop_type=='Retail Shop'){
            $masterdata['status'] = "Completed";
            $masterdata['pick_ticket_status'] = '1';
            $masterdata['pick_item_recieved_status'] = '1';
            $masterdata['completed_status'] = '1';
        }
        if($this->input->post('pick_item_recieved_status')=='1')
        {
            $masterdata['status'] = 'Completed';
            $masterdata['completed_status']='1';
        }
        //    $existresult = $this->deliverynote->deliverynote_already_exist_or_not($delivery_note_number);
       if($action_type)
       {
            $this->db->update('cberp_delivery_notes',$masterdata,['delivery_note_number'=>$delivery_note_number]);
            detailed_log_history('Deliverynote',$delivery_note_number,'Created/Updated', $_POST['changedFields']);

       }
       else{
             $masterdata['created_date'] = date('Y-m-d H:i:s');
             $masterdata['delivery_note_date'] = date('Y-m-d H:i:s');
             $masterdata['created_by'] =  $this->session->userdata('id');
             $delivery_note_number = $this->deliverynote->deliverynote_number();
             $masterdata['delivery_note_number'] = $delivery_note_number;
             $this->db->insert('cberp_delivery_notes',$masterdata);
             // file upload section starts 22-01-2025 
             if($_FILES['upfile'])
             {
                 upload_files($_FILES['upfile'], 'Deliverynote',$delivery_note_number);
             }
             // file upload section ends 22-01-2025
             //erp2024 06-01-2025 detailed history log starts
             detailed_log_history('Deliverynote',$delivery_note_number,'Created', '');
             //erp2024 06-01-2025 detailed history log ends 
             //insert to tracking table
             insertion_to_tracking_table('deliverynote_number', $delivery_note_number);            
            
       }
        

        //sales order items starts
        $productlist = array();
        $prodindex = 0;
        $itc = 0;
        $flag = false;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $code = $this->input->post('code', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        // $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $max_disrate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt'); 
        $total_product_qty = array_sum($product_qty);
        // $product_wise_discount = order_discount_percentage_for_single_product($order_discount,$total_product_qty);
        $total_records  = count($product_id);
        $product_wise_order_discount = ($order_discount>0) ?round(($order_discount/$total_records),2):0;
        //erp@2024 new field 06-12-2024
        $income_account_number = $this->input->post('income_account_number', true);
        $expense_account_number = $this->input->post('expense_account_number', true);
        $product_cost = $this->input->post('product_cost', true);
        $product_tax =0;
        $grandtotal=0;
        $grandprice=0;

        // erp2024 19-12-2024 load default accounts
        $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
        $default_inventory_account = default_chart_of_account('inventory');
       $i=0;
       $cost_of_goods_data = [];
        //    $cost_data_list = [];
       $grand_product_cost = 0;
       $grand_inventory = 0;
       $producttransdata1 = [];
        foreach ($product_hsn as $key => $value) {
            if(!empty($product_hsn[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
            {
               
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
               
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                if($this->configurations["config_tax"]!="0"){ 
                    $product_tax = numberClean($product_tax[$key]);
                }
                $data = array(
                    'delivery_note_number' => $delivery_note_number,
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'product_price' => numberClean($product_price[$key]),
                    'product_tax' => $product_tax,
                    'product_discount' => $discountamount,
                    'subtotal' => numberClean($product_subtotal[$key]),
                    'total_tax' => numberClean($ptotal_tax[$key]),
                    'total_discount' => numberClean($ptotal_disc[$key]),
                    'discount_type' => $discount_type[$key],
                    'product_cost' => $product_cost[$key],
                    'lowest_price' => $lowest_price[$key],                    
    
                );
                $actulprice = numberClean($product_price[$key])*numberClean($product_qty[$key]);
                $productprice = numberClean($product_subtotal[$key])-numberClean($product_wise_order_discount);
                $grandprice +=  numberClean($actulprice);
               
                
                if($shop_type=='Retail Shop' || (!empty($salesorder_number))){
                    $product_code = $product_hsn[$key];
                    $qty = numberClean($product_qty[$key]);
                    $this->db->select('onhand_quantity');
                    $this->db->from('cberp_products');
                    $this->db->where('product_code', $product_code);
                    $prdQry = $this->db->get();  
                    $prdresult = $prdQry->row_array();
                    $this->update_warehouse_products($product_code, $store_id, $qty);
                    if ($prdresult) {
                        $onhand = intval($prdresult['onhand_quantity']);
                        $updateQty = $onhand - $qty;          
                        $upqty = array('onhand_quantity' => $updateQty);
                        $this->db->where('product_code', $product_code);
                        $this->db->update('cberp_products', $upqty);   
                    }
                    //erp2024 data insert to average cost 25-02-2025
                    // insert_data_to_average_cost_table($product_hsn[$key], $product_cost[$key],numberClean($product_qty[$key]), get_costing_transation_type("Sales"));

                   
                    //commented on 07-02-2025
                    // $producttransdata =  [
                    //     'acid' => $income_account_number[$key],
                    //     'type' => 'Asset',
                    //     'cat' => 'Deliverynote',
                    //     // 'credit' => $productprice,
                    //     'credit' => $actulprice,
                    //     'eid' => $this->session->userdata('id'),
                    //     'date' => date('Y-m-d'),
                    //     'transaction_number'=>$transaction_number,
                    // ];
                    // $this->db->set('lastbal', 'lastbal - ' . $actulprice, FALSE);
                    // $this->db->where('acn', $income_account_number[$key]);
                    // $this->db->update('cberp_accounts'); 
                    // $this->db->insert('cberp_transactions', $producttransdata);

                    $producttransdata1[$income_account_number[$key]][] =  [
                        'acid' => $income_account_number[$key],
                        'credit' => numberClean($actulprice)
                    ];
              

                    // cost of goods transaction
                    $productQuantity = numberClean($product_qty[$key]);
                    $total_product_cost = $product_cost[$key]*intval($productQuantity);
                    $grand_product_cost += numberClean($total_product_cost);
                                       

                }
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $grandtotal += numberClean($product_subtotal[$key]); 
        }

        if($producttransdata1)
        {
            foreach ($producttransdata1 as $acid => $transactions) {
                $totalCredit = 0;
        
                foreach ($transactions as $transaction) {
                    $totalCredit += $transaction['credit'];
                }
        
                // Store the summed data for each `acid`
                $groupedData[] = [
                    'acid' => $acid,
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'credit' => $totalCredit,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->set('lastbal', 'lastbal - ' . $totalCredit, FALSE);
                $this->db->where('acn', $acid);
                $this->db->update('cberp_accounts'); 
            }
        }

        if ($prodindex > 0) {
            $grandtotal = $grandtotal - $order_discount;
            $order_discount_percentage = order_discount_percentage($order_discount,$grandprice);
            $this->db->delete('cberp_delivery_note_items', ['delivery_note_number'=>$delivery_note_number]);
            $this->db->insert_batch('cberp_delivery_note_items', $productlist);
            $this->db->set(array('discount' => numberClean(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => numberClean(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc),  'subtotal'=>$grandtotal,'total_amount'=>$grandtotal,'order_discount_percentage'=>$order_discount_percentage));
            $this->db->where('delivery_note_number', $delivery_note_number);
            $this->db->update('cberp_delivery_notes');
            //erp2024 commented shoptype 06-03-2024
            if($shop_type=='Retail Shop'  || (!empty($salesorder_number))){
                
                //commented all accounts transactions except - inventory and cost of goods(07-02-2025)
                // cost of goods transactions transaction 07-02-2025
                $cost_of_goods_data =  [
                    'acid' => $default_cost_of_goods_account,
                    'type' => 'Expense',
                    'cat' => 'Deliverynote',
                    'debit' => $grand_product_cost,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal + ' . $grand_product_cost, FALSE);
                $this->db->where('acn', $default_cost_of_goods_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $cost_of_goods_data);
                // Inventory transaction 07-02-2025
                $inventory_data =  [
                    'acid' => $default_inventory_account,
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'credit' => numberClean($grand_product_cost),
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal - ' . numberClean($grand_product_cost), FALSE);
                $this->db->where('acn', $default_inventory_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $inventory_data);


                // erp2024 transactions starts 06-12-2024
               //$grandprice grand total price of all products without discount
                // $invoice_receivable_account_details = default_chart_of_account('accounts_receivable');
                // $latest_total = $total;
                // $receivable_data = [
                //     'acid' => $invoice_receivable_account_details,
                //     'type' => 'Asset',
                //     'cat' => 'Deliverynote',
                //     'debit' => numberClean($grandprice),
                //     // 'debit' => $total,
                //     'eid' => $this->session->userdata('id'),
                //     'date' => date('Y-m-d'),
                //     'transaction_number'=>$transaction_number,
                // ];
                // $this->db->insert('cberp_transactions',$receivable_data);
                // $this->db->set('lastbal', 'lastbal + ' .$total, FALSE);
                // $this->db->where('acn', $invoice_receivable_account_details);
                // $this->db->update('cberp_accounts'); 


                if (($groupedData)) {
                    // $this->db->insert_batch('cberp_transactions', $groupedData);
                }
                // erp2024 transactions ends 06-12-2024

                //erp2024 totaldiscount transaction 06-12-2024 starts
                // if($total_discount>0)
                // {
                //     $discount_account_details = default_chart_of_account('sales_discount');
                //     $discount_data = [
                //         'acid' => $discount_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'debit' => $total_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$discount_data);
                
                //     $this->db->set('lastbal', 'lastbal + ' .$total_discount, FALSE);
                //     $this->db->where('acn', $discount_account_details);
                //     $this->db->update('cberp_accounts'); 

                    
                //     $total_discount_credit = [
                //         'acid' => $invoice_receivable_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'credit' => $total_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$total_discount_credit);
                //     $this->db->set('lastbal', 'lastbal - ' .$total_discount, FALSE);
                //     $this->db->where('acn', $invoice_receivable_account_details);
                //     $this->db->update('cberp_accounts'); 
                // }
                // if($order_discount)
                // {
                //     $order_discount_account_number = default_chart_of_account('order_discount');
                //     $discount_data1 = [
                //         'acid' => $order_discount_account_number,
                //         // 'account' => $order_discount_account_details['holder'],
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'debit' => $order_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];

                //     $this->db->insert('cberp_transactions',$discount_data1);
                //     $this->db->set('lastbal', 'lastbal + ' .$order_discount, FALSE);
                //     $this->db->where('acn', $order_discount_account_number);
                //     $this->db->update('cberp_accounts'); 

                //     $order_discount_data_credit = [
                //         'acid' => $invoice_receivable_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'credit' => $order_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$order_discount_data_credit);
                //     $this->db->set('lastbal', 'lastbal - ' .$order_discount, FALSE);
                //     $this->db->where('acn', $invoice_receivable_account_details);
                //     $this->db->update('cberp_accounts');
                // }


            }
            
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        } 
        //sales order items ends
    
    
        // $this->db->trans_start();
        $flag = false;
        $transok = true;
        
        echo json_encode(array('status' => 'Success','id'=>$delivery_note_number));
            $transok = false;
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

 
  
    public function deliverynoteaction()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  
        $masterdata = [];
        $order_discount=0;
        $discountchecked = $this->input->post('discountchecked');
    
        if (isset($discountchecked)) {
            $discountchecked = $discountchecked;
        } else {
            $discountchecked = 0;
        }
        $customer_id = $this->input->post('customer_id');
        $invocieno_n = $this->input->post('invocieno_demo');
        $total_tax = 0;
        $total_discount = 0;
        $proposal = $this->input->post('proposal');
    
        $currency = $this->input->post('currency');
        $note = $this->input->post('note');
        $store_id = $this->input->post('s_warehouses');
        $ship_taxtype = $this->input->post('ship_taxtype');
         
        // $subtotal = rev_amountExchange_s($this->input->post('product_subtotal'), $currency, $this->aauth->get_user()->loc);
        
        $shipping = $this->input->post('shipping');
        $shipping_tax = $this->input->post('ship_tax');
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $reference_date = $this->input->post('reference_date', true);
        // 24-10-2024 erp2024 newlyadded fields
        $customer_po_reference = $this->input->post('customer_po_reference', true);
        $customer_contact_person = $this->input->post('customer_contact_person', true);
        $customer_contact_number = $this->input->post('customer_contact_number', true);
        $customer_contact_email = $this->input->post('customer_contact_email', true);
        $delivery_note_number = $this->input->post('delivery_note_number', true);
        $action_type = $this->input->post('action_type', true);
        // 24-10-2024 erp2024 newlyadded fields
       
       
        // 08-08-2024 erp2024 newlyadded fields
        // $customer_purchase_order = $this->input->post('customer_purchase_order', true);
        // $customer_order_date = date('Y-m-d', strtotime($this->input->post('customer_order_date')));    
        // $order_discount = rev_amountExchange_s($this->input->post('order_discount'), $currency, $this->aauth->get_user()->loc);

        
        $shop_type = $this->input->post('shoptype');
        // if ($customer_id == 0) {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         $this->lang->line('Please add a new client')));
        //     exit;
        // }
    
        $masterdata = [
            'shipping' => $shipping,
            'tax' => $tax,
            'note' => $note,
            'status' => 'Created',
            'customer_id' => $customer_id,
            'store_id' => $store_id,
            'reference' => $refer,
            'reference_date' => $reference_date,
            'shop_type' => $shop_type,
            'customer_contact_person' => $customer_contact_person,
            'customer_contact_number' => $customer_contact_number,
            'customer_contact_email' => $customer_contact_email,
            'order_discount' => $order_discount,
            'transaction_number'=>$transaction_number,
            'due_date'=> $this->input->post('deliveryduedate', true),            
        ];
        
        // if($shop_type=='Retail Shop'){
            $masterdata['status'] = "Draft";
            $masterdata['pick_ticket_status'] = "0";
            $masterdata['pick_item_recieved_status'] = "0";
            $masterdata['completed_status'] = "0";
        // }
        // $existresult = $this->deliverynote->deliverynote_already_exist_or_not($invocieno_n);
       
       if($action_type)
       {
            $this->db->update('cberp_delivery_notes',$masterdata,['delivery_note_number'=>$delivery_note_number]);
            detailed_log_history('Deliverynote',$delivery_note_number,'Data Saved As Draft', $_POST['changedFields']);
       }
       else{
            $delivery_note_number = $this->deliverynote->deliverynote_number();
            $masterdata['delivery_note_number'] = $delivery_note_number;
            $masterdata['created_date'] = date('Y-m-d H:i:s');
            $masterdata['created_by'] =  $this->session->userdata('id');
            $this->db->insert('cberp_delivery_notes',$masterdata);
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Deliverynote',$delivery_note_number,'Data Saved As Draft', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
            //insert to tracking table
            $this->db->insert('cberp_transaction_tracking',['delivery_note_number'=>$delivery_note_number,'deliverynote_number'=>$delivery_note_number]);
       }
         
        if($_FILES['upfile'])
        {
            upload_files($_FILES['upfile'], 'Deliverynote',$delivery_note_number);
        }

        //sales order items starts
        $productlist = array();
        $prodindex = 0;
        $itc = 0;
        $flag = false;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $code = $this->input->post('code', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        // $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $max_disrate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt');
         //erp@2024 new field 06-12-2024
        $income_account_number = $this->input->post('income_account_number', true);
        $expense_account_number = $this->input->post('expense_account_number', true);
        $product_cost = $this->input->post('product_cost', true);
        $product_tax =0;
        $grandtotal=0;
        $grandprice=0;
        foreach ($product_hsn as $key => $value) {
            if(!empty($product_hsn[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
            {
               
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
                
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                if($this->configurations["config_tax"]!="0"){ 
                    $product_tax = numberClean($product_tax[$key]);
                }
                $data = array(
                    'delivery_note_number' => $delivery_note_number,
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'product_price' => numberClean($product_price[$key]),
                    'product_tax' => $product_tax,
                    'product_discount' => $discountamount,
                    'subtotal' => numberClean($product_subtotal[$key]),
                    'total_tax' => numberClean($ptotal_tax[$key]),
                    'total_discount' => numberClean($ptotal_disc[$key]),
                    'discount_type' => $discount_type[$key],
                    'product_cost' => $product_cost[$key],
                    'lowest_price' => $lowest_price[$key],     
                );
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $grandtotal += numberClean($product_subtotal[$key]); 
        }
    
        if ($prodindex > 0) {
            $grandtotal = $grandtotal - $order_discount;
            $this->db->delete('cberp_delivery_note_items', ['delivery_note_number'=>$delivery_note_number]);
            $this->db->insert_batch('cberp_delivery_note_items', $productlist);
            $this->db->set(array('discount' => numberClean(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => numberClean(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc),  'subtotal'=>$grandtotal,'total_amount'=>$grandtotal));
            $this->db->where('delivery_note_number', $delivery_note_number);
            $this->db->update('cberp_delivery_notes');
            
        } 
        // else {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         "Please choose product from product list. Go to Item manager section if you have not added the products."));
        //     $transok = false;
        // } 
        //sales order items ends
    
    
        // $this->db->trans_start();
        $flag = false;
        $transok = true;
        
        echo json_encode(array('status' => 'Success','id'=>$delivery_note_number));
            $transok = false;
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }



    
    public function deliverynote_shop_print()
    {
        //  ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);

        $delivery_note_number = $this->input->get('deliverynoteid');
        // ====================================================================
        $client = "";
       
        $loc = location($this->aauth->get_user()->loc);
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;
        $this->db->select('cberp_delivery_notes.created_date,cberp_delivery_notes.status,cberp_delivery_notes.customer_id,cberp_delivery_notes.delivery_note_number,cberp_delivery_notes.customer_po_reference');
        $this->db->from('cberp_delivery_notes');
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
        $query11 = $this->db->get();
        $res11 =  $query11->row_array();
        $data["deleverynote_createddate"] = $res11['created_date'];
        $data["deleverynote_status"] = $res11['status'];
        $data["delivery_note_number"] = $res11['delivery_note_number'];
        $data["customer_po_reference"] = $res11['customer_po_reference'];
        $data['CustDetails'] = $this->deliverynote->customerByDeliverynoteid($delivery_note_number);
        if(!empty($data['CustDetails'])){
            $client = '' . $data['CustDetails']['name'] . '<br>' . $data['CustDetails']['address'] . ','. $data['CustDetails']['city'] .' <br>' . $data['CustDetails']['phone'] . '<br>' .$data['CustDetails']['email'] ;
        
            $data["custId"]=$data['CustDetails']['id'];
        }
        $data["client"]=$client;


        // ==================================================================
        $this->db->select('cberp_delivery_notes.customer_id, cberp_delivery_notes.salesorder_number, cberp_delivery_notes.discount, cberp_delivery_notes.subtotal, cberp_delivery_notes.tax, cberp_delivery_notes.total_amount, cberp_delivery_note_items.*,cberp_products.product_code,cberp_product_description.product_name,cberp_products.unit AS productunit');
        $this->db->from('cberp_delivery_notes');
        $this->db->join('cberp_delivery_note_items', 'cberp_delivery_note_items.delivery_note_number = cberp_delivery_notes.delivery_note_number');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_delivery_note_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
        $query = $this->db->get();
        $data['products'] = $query->result_array();
        $data['employee'] = $this->deliverynote->employee($this->session->userdata('id'));           
        
        $html = $this->load->view('deliverynotes/deliverynotereprintshoppdf-' . LTR, $data, true);
        
            ini_set('memory_limit', '64M');
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->WriteHTML($html);       
            $pdf->Output('reprint-note' . $pay_acc . '.pdf', 'I');
        
            
    }

    public function delivery_print_action()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 

        $current_value = $this->session->userdata('repeatsubmit');      
        $delivery_note_number = $this->input->post('delivery_note_number');  
        //erp2024 note 26-09-2024
        $data1['note'] = $this->input->post('note');
        $data1['store_id'] = $this->input->post('store_id');

        $data1['delivery_note_number'] = $this->input->post('invocieno_demo');
        $data1['salesorder_number'] = $this->input->post('salesorder_number');
        $data1['total_amount'] = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $totalamountcust = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $data1['subtotal'] = numberClean($this->input->post('subtotal'));
        $data1['shipping'] = $this->input->post('shipping');
        $data1['shipping_tax'] = $this->input->post('ship_tax');
        $data1['customer_id'] = $customer_id = $this->input->post('customer_id');
        $data1['created_date'] = date("Y-m-d H:i:s");
        $data1['pick_ticket_status'] = '1';
        $data1['status'] = 'In Progress';
        $data1['due_date'] = $this->input->post('deliveryduedate');
        
        $checkres = $this->quote->check_deliverynote_creation_once_completed($data1['salesorder_number']);
        if (!empty($checkres)) {
            $salesorder_deltid = $this->input->post('delivery_note_number');
        } else {
            $salesorder_deltid = $delivery_note_number;
        }
        $existingdeliverynoteid =  $this->quote->deliverynoteid_by_salesorder_number($data1['salesorder_number'],$data1['delivery_note_number']);
        $last_insert_id =$this->input->post('delivery_note_number');
        $this->db->update('cberp_delivery_notes', $data1,['delivery_note_number'=>$delivery_note_number]);

        $changedProducts = [];
        $wholeProducts = [];            
        if (!empty($this->input->post('changedProducts'))) {
            $changedProducts = json_decode($this->input->post('changedProducts'), true);
        }            
        if (!empty($this->input->post('wholeProducts'))) {
            $wholeProducts = json_decode($this->input->post('wholeProducts'), true);
        }            
        $changedSet = !empty($changedProducts) ? array_flip($changedProducts) : [];
        $wholeSet = !empty($wholeProducts) ? array_flip($wholeProducts) : [];
        // die($this->db->last_query());
        // $this->db->delete('cberp_delivery_note_items',['delivery_note_number'=>$delivery_note_number]);

        $this->session->set_userdata("deliverymaster", $delivery_note_number);

        $i = 0;

        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;
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
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $tid = $this->session->userdata('salesorderid');
        // print_r($product_hsn); die();
        //erp2024 19-12-2024        
        $product_cost = $this->input->post('product_cost', true);

        $total_discount =0;
        $total_tax  =0;        
        $wholestatus = 1;
        
        $deleted_items = $this->input->post('deleted_item');
        $deleted_items_array = explode(",", $deleted_items);
 
        // if($deleted_items_array)
        // {
        //     $this->db->where('delivery_note_number', $delivery_note_number);
        //     $this->db->where_in('product_code', $deleted_items_array);
        //     $this->db->delete('cberp_delivery_note_items'); 
        // }
        foreach ($product_hsn as $key => $value) {
            $status ="";
            $total_discount += numberClean(@$ptotal_disc[$key]);
            $total_tax += numberClean($ptotal_tax[$key]);
            foreach ($statusList as $item) {            
                if($item['product_code']==$product_hsn[$key]){
                    $status = $item['status'];
                }            
            }

            $data = array(
                'salesorder_number' => $data1['salesorder_number'],
                'delivery_note_number' => $this->input->post('delivery_note_number'),
                'product_code' => $product_hsn[$key],
                'quantity' => numberClean($product_qty[$key]),
                'salesorder_product_quantity' => numberClean($old_product_qty[$key]),
                'product_price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                'product_tax' => numberClean($product_tax[$key]),
                'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                'discount_type' => $discount_type[$key],
                'product_cost' => $product_cost[$key]
            );
            if(numberClean($product_qty[$key])<1){
                $data['product_discount'] =  0;
            }
            else{
                $data['product_discount'] =  numberClean($product_discount[$key]);
            }

            $code = trim($product_hsn[$key]);     
            $isChanged = !empty($changedSet) && isset($changedSet[$code]);
            $isInWhole = !empty($wholeSet) && isset($wholeSet[$code]);   

            if($isChanged && in_array($code, $product_hsn)) {
                $this->db->update('cberp_delivery_note_items', $data, ['delivery_note_number'=>$delivery_note_number, 'product_code'=>$code]);
            }
            elseif (!$isInWhole && in_array($code, $product_hsn)) 
            {
                $this->db->insert('cberp_delivery_note_items', $data);
            }
            $existornot = $this->deliverynote->check_product_existornot($delivery_note_number,$product_hsn[$key]);
            if($existornot==1)
            {
                $this->db->update('cberp_delivery_note_items', $data, ['delivery_note_number'=>$delivery_note_number, 'product_code'=>$product_hsn[$key]]);             
            }
            else{
                $this->db->insert('cberp_delivery_note_items', $data);
           
            }
            $flag = true;
            $productlist[$prodindex] = $data;
            $i += numberClean($product_qty[$key]);
            $prodindex++;

        }
        
        if ($productlist) {
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Deliverynote',$delivery_note_number,'Print Picking List', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
	       // file upload section starts 22-01-2025
                if($_FILES['upfile'])
                {
                    upload_files($_FILES['upfile'], 'Deliverynote',$delivery_note_number);
                }
                // file upload section ends 22-01-2025
            echo json_encode(array('status' => 'Success', 'message' => 'Successfully Printed', 'data' => $data1['salesorder_number']));   

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in invoice $invocieno"));
            $transok = false;
        }
    }

    public function pick_item_recieved_status()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);  
        $delivery_note_number = $this->input->post('delivery_note_number');
        $pick_item_recieved_note = $this->input->post('extradata');

        $deleverynotetid = $this->input->post('invocieno');         
        // $store_id = $this->input->post('store_id');
        //erp2024 note 26-09-2024 status
        $data1['note'] = $this->input->post('note');
        $data1['store_id'] = $this->input->post('store_id');

        $data1['delivery_note_number'] = $this->input->post('invocieno_demo');
        $data1['salesorder_number'] = $this->input->post('salesorder_number');
        $data1['total_amount'] = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $totalamountcust = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $data1['subtotal'] = numberClean($this->input->post('subtotal'));
        $data1['shipping'] = $this->input->post('shipping');
        $data1['shipping_tax'] = $this->input->post('ship_tax');
        $data1['customer_id'] = $customer_id = $this->input->post('customer_id');
        $data1['created_date'] = date("Y-m-d H:i:s");
        $data1['pick_item_recieved_status'] = '1';
        $data1['status'] = 'In Progress';
        $data1['pick_item_recieved_note'] = $pick_item_recieved_note;

        $this->db->update('cberp_delivery_notes', $data1,['delivery_note_number'=>$delivery_note_number]);   
        $changedProducts = [];
        $wholeProducts = [];            
        if (!empty($this->input->post('changedProducts'))) {
            $changedProducts = json_decode($this->input->post('changedProducts'), true);
        }            
        if (!empty($this->input->post('wholeProducts'))) {
            $wholeProducts = json_decode($this->input->post('wholeProducts'), true);
        }            
        $changedSet = !empty($changedProducts) ? array_flip($changedProducts) : [];
        $wholeSet = !empty($wholeProducts) ? array_flip($wholeProducts) : [];     
        // $this->db->delete('cberp_delivery_note_items',['delivery_note_number'=>$delivery_note_number]);
        $this->session->set_userdata("deliverymaster", $delivery_note_number);

        $i = 0;

        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;
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
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $tid = $this->session->userdata('salesorderid');
        //erp2024 19-12-2024        
        $product_cost = $this->input->post('product_cost', true);

        $total_discount =0;
        $total_tax  =0;        
        $wholestatus = 1;
        $deleted_items = $this->input->post('deleted_item');
        $deleted_items_array = explode(",", $deleted_items);
        if($deleted_items_array)
        {
            $this->db->where('delivery_note_number', $delivery_note_number);
            $this->db->where_in('product_code', $deleted_items_array);
            $this->db->delete('cberp_delivery_note_items'); 
        }
        foreach ($product_hsn as $key => $value) {
            $status ="";
            $total_discount += numberClean(@$ptotal_disc[$key]);
            $total_tax += numberClean($ptotal_tax[$key]);
            // foreach ($statusList as $item) {            
            //     if($item['pid']==$product_id[$key]){
            //         $status = $item['status'];
            //     }            
            // }

            $data = array(
                'salesorder_number' => $data1['salesorder_number'],
                'delivery_note_number' => $this->input->post('delivery_note_number'),
                'product_code' => $product_hsn[$key],
                'quantity' => numberClean($product_qty[$key]),
                'salesorder_product_quantity' => numberClean($old_product_qty[$key]),
                'product_price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                'product_tax' => numberClean($product_tax[$key]),
                'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                'total_tax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                'total_discount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                'discount_type' => $discount_type[$key],
                'product_cost' => $product_cost[$key]
            );
            if(numberClean($product_qty[$key])<1){
                $data['product_discount'] =  0;
            }
            else{
                $data['product_discount'] =  numberClean($product_discount[$key]);
            }
            $flag = true;
            $productlist[$prodindex] = $data;
            $i += numberClean($product_qty[$key]);
            $prodindex++;

            $code = trim($product_hsn[$key]);     
            $isChanged = !empty($changedSet) && isset($changedSet[$code]);
            $isInWhole = !empty($wholeSet) && isset($wholeSet[$code]);   

            if($isChanged && in_array($code, $product_hsn)) {
                $this->db->update('cberp_delivery_note_items', $data, ['delivery_note_number'=>$delivery_note_number, 'product_code'=>$code]);
            }
            elseif (!$isInWhole && in_array($code, $product_hsn)) 
            {
                $this->db->insert('cberp_delivery_note_items', $data);
            }
            $existornot = $this->deliverynote->check_product_existornot($delivery_note_number,$product_hsn[$key]);
            if($existornot==1)
            {
                $this->db->update('cberp_delivery_note_items', $data, ['delivery_note_number'=>$delivery_note_number, 'product_code'=>$product_hsn[$key]]);
            }
            else{
                $this->db->insert('cberp_delivery_note_items', $data);
            }

        }

        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('Deliverynote',$delivery_note_number,'Items Picked', $_POST['changedFields']);
        //erp2024 06-01-2025 detailed history log ends 
        // $this->db->update('cberp_delivery_notes', ['pick_item_recieved_status' => '1', 'pick_item_recieved_note'=> $pick_item_recieved_note,'store_id'=>$store_id], ['delivery_note_number'=> $delivery_note_number]);
        echo json_encode(array('status' => 'success'));
    }

     public function deliverynote_save_for_existing_action()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 

        //commented all accounts transactions except - inventory and cost of goods(07-02-2025) order_discount_percentage
        $masterdata = [];
        $order_discount=0;
        $tax =0;
        $transaction_number = get_latest_trans_number();
        $discountchecked = $this->input->post('discountchecked');
    
        if (isset($discountchecked)) {
            $discountchecked = $discountchecked;
        } else {
            $discountchecked = 0;
        }
        $customer_id = $this->input->post('customer_id');
        $delivery_note_number = $this->input->post('invocieno_demo');
        $total_tax = 0;
        $total_discount = 0;
        $proposal = $this->input->post('proposal');
    
        $currency = $this->input->post('currency');
        $note = $this->input->post('note');
        $store_id = $this->input->post('s_warehouses');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = numberClean($this->input->post('subtotal'));
        $shipping = numberClean($this->input->post('shipping'));
        $shipping_tax = numberClean($this->input->post('ship_tax'));
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = numberClean($this->input->post('total'));
        $order_discount = (numberClean($this->input->post('order_discount'))) ? numberClean($this->input->post('order_discount')) : 0.00;

        
        // 24-10-2024 erp2024 newlyadded fields
        $customer_po_reference = $this->input->post('customer_po_reference', true);
        $customer_contact_person = $this->input->post('customer_contact_person', true);
        $customer_contact_number = $this->input->post('customer_contact_number', true);
        $customer_contact_email = $this->input->post('customer_contact_email', true);
        $delivery_note_number = $this->input->post('delivery_note_number', true);
        $reference_date = $this->input->post('reference_date', true);
        $action_type = $this->input->post('action_type', true);
        $created_date = $this->input->post('created_date', true);
        $created_date = ($created_date) ? $created_date : date('Y-m-d');
        // 24-10-2024 erp2024 newlyadded fields
       
        
        $shop_type = $this->input->post('shoptype');
        
        //    echo $customer_id; die();
        // if ($customer_id == 0) {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         $this->lang->line('Please add a new client')));
        //     exit;
        // }
        $masterdata = [
            'delivery_note_number' => $delivery_note_number,
            'shipping' => $shipping,
            'tax' => $tax,
            'note' => $note,
            'status' => 'Assigned',
            // 'customer_id' => $customer_id,
            'store_id' => $store_id,
            'reference' => $refer,
            'reference_date' => $reference_date,
            'shop_type' => $shop_type,
            'customer_contact_person' => $customer_contact_person,
            'customer_contact_number' => $customer_contact_number,
            'customer_contact_email' => $customer_contact_email,
            'order_discount' => $order_discount,
            'transaction_number'=>$transaction_number,
            'due_date'=> $this->input->post('deliveryduedate', true),              
        ];

        // if($shop_type=='Retail Shop'){
            $masterdata['status'] = "Completed";
            $masterdata['pick_ticket_status'] = '1';
            $masterdata['pick_item_recieved_status'] = '1';
            $masterdata['completed_status'] = '1';
        // }

        // $existresult = $this->deliverynote->deliverynote_already_exist_or_not($delivery_note_number);
       if($action_type)
       {
            $this->db->update('cberp_delivery_notes',$masterdata,['delivery_note_number'=>$delivery_note_number]);
            detailed_log_history('Deliverynote',$delivery_note_number,'Created', $_POST['changedFields']);

       }
       else{
             $masterdata['created_date'] = date('Y-m-d H:i:s');
             $masterdata['delivery_note_date'] = date('Y-m-d H:i:s');
             $masterdata['created_by'] =  $this->session->userdata('id');
             $delivery_note_number = $this->deliverynote->deliverynote_number();
             $masterdata['delivery_note_number'] = $delivery_note_number;
             $this->db->insert('cberp_delivery_notes',$masterdata);
             // file upload section starts 22-01-2025
             if($_FILES['upfile'])
             {
                 upload_files($_FILES['upfile'], 'Deliverynote',$delivery_note_number);
             }
             // file upload section ends 22-01-2025
            //erp2024 06-01-2025 detailed history log starts
            detailed_log_history('Deliverynote',$delivery_note_number,'Created', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 
            //insert to tracking table
            $this->db->insert('cberp_transaction_tracking',['delivery_note_number'=>$delivery_note_number,'deliverynote_number'=>$delivery_note_number]);
       }
        

        //sales order items starts
        $productlist = array();
        $prodindex = 0;
        $itc = 0;
        $flag = false;
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $code = $this->input->post('code', true);
        $product_qty = $this->input->post('product_qty');
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        // $product_des = $this->input->post('product_description', true);
        $product_hsn = $this->input->post('hsn');
        $product_unit = $this->input->post('unit');
        $discount_type = $this->input->post('discount_type');
        $lowest_price = $this->input->post('lowest_price');
        $max_disrate = $this->input->post('maxdiscountrate');
        $product_amt = $this->input->post('product_amt'); 
        $total_product_qty = array_sum($product_qty);
        // $product_wise_discount = order_discount_percentage_for_single_product($order_discount,$total_product_qty);
        $total_records  = count($product_id);
        $product_wise_order_discount = ($order_discount>0) ?round(($order_discount/$total_records),2):0;
        //erp@2024 new field 06-12-2024
        $income_account_number = $this->input->post('income_account_number', true);
        $expense_account_number = $this->input->post('expense_account_number', true);
        $product_cost = $this->input->post('product_cost', true);
        $product_tax =0;
        $grandtotal=0;
        $grandprice=0;

        // erp2024 19-12-2024 load default accounts
        $default_cost_of_goods_account = default_chart_of_account('cost_of_goods_solid');
        $default_inventory_account = default_chart_of_account('inventory');
       $i=0;
       $cost_of_goods_data = [];
        //    $cost_data_list = [];
       $grand_product_cost = 0;
       $grand_inventory = 0;
       $producttransdata1 = [];
        foreach ($product_hsn as $key => $value) {
            if(!empty($product_hsn[$key]) && !empty($product_name1[$key]) && $product_qty[$key]>0)
            {
               
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);
               
                if($discount_type[$key]=="Amttype"){
                    $discountamount = numberClean($product_amt[$key]);
                }
                else{
                    $discountamount = numberClean($product_discount[$key]);
                }
                if($this->configurations["config_tax"]!="0"){ 
                    $product_tax = numberClean($product_tax[$key]);
                }
                
                $data = array(
                    'delivery_note_number' => $delivery_note_number,
                    'product_code' => $product_hsn[$key],
                    'quantity' => numberClean($product_qty[$key]),
                    'product_price' => numberClean($product_price[$key]),
                    'product_tax' => $product_tax,
                    'product_discount' => $discountamount,
                    'subtotal' => numberClean($product_subtotal[$key]),
                    'total_tax' => numberClean($ptotal_tax[$key]),
                    'total_discount' => numberClean($ptotal_disc[$key]),
                    'discount_type' => $discount_type[$key],
                    'product_cost' => $product_cost[$key],
                    'lowest_price' => $lowest_price[$key],  
                );

                $actulprice = numberClean($product_price[$key])*numberClean($product_qty[$key]);
                $productprice = numberClean($product_subtotal[$key])-numberClean($product_wise_order_discount);
                $grandprice +=  numberClean($actulprice);
               
                // if($shop_type=='Retail Shop'){
                    
             
                    $product_code = $product_hsn[$key];
                    $pid = numberClean($product_id[$key]);
                    $qty = numberClean($product_qty[$key]);
                    $this->db->select('onhand_quantity');
                    $this->db->from('cberp_products');
                    $this->db->where('product_code', $product_code);
                    $prdQry = $this->db->get();        
                    $prdresult = $prdQry->row_array();

                    $this->update_warehouse_products($product_code, $store_id, $qty);
                    if ($prdresult) {
                        $onhand = intval($prdresult['onhand_quantity']);
                        $updateQty = $onhand - $qty;          
                        $upqty = array('onhand_quantity' => $updateQty);
                        $this->db->where('product_code', $product_code);
                        $this->db->update('cberp_products', $upqty);
                    }

                    //erp2024 data insert to average cost 25-02-2025
                    // insert_data_to_average_cost_table($product_code, $product_cost[$key],numberClean($product_qty[$key]), get_costing_transation_type("Sales"));


                    //commented on 07-02-2025
                    // $producttransdata =  [
                    //     'acid' => $income_account_number[$key],
                    //     'type' => 'Asset',
                    //     'cat' => 'Deliverynote',
                    //     // 'credit' => $productprice,
                    //     'credit' => $actulprice,
                    //     'eid' => $this->session->userdata('id'),
                    //     'date' => date('Y-m-d'),
                    //     'transaction_number'=>$transaction_number,
                    // ];
                    // $this->db->set('lastbal', 'lastbal - ' . $actulprice, FALSE);
                    // $this->db->where('acn', $income_account_number[$key]);
                    // $this->db->update('cberp_accounts'); 
                    // $this->db->insert('cberp_transactions', $producttransdata);

                    $producttransdata1[$income_account_number[$key]][] =  [
                        'acid' => $income_account_number[$key],
                        'credit' => $actulprice
                    ];
              

                    // cost of goods transaction
                    $productQuantity = numberClean($product_qty[$key]);
                    $total_product_cost = $product_cost[$key]*intval($productQuantity);
                    $grand_product_cost += numberClean($total_product_cost);
                                       

                // }
                $flag = true;
                $productlist[$prodindex] = $data;
            }   
            $i++;
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $grandtotal += numberClean($product_subtotal[$key]); 
        }

        if($producttransdata1)
        {
            foreach ($producttransdata1 as $acid => $transactions) {
                $totalCredit = 0;
        
                foreach ($transactions as $transaction) {
                    $totalCredit += $transaction['credit'];
                }
        
                // Store the summed data for each `acid`
                $groupedData[] = [
                    'acid' => $acid,
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'credit' => $totalCredit,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number
                ];
                $this->db->set('lastbal', 'lastbal - ' . $totalCredit, FALSE);
                $this->db->where('acn', $acid);
                $this->db->update('cberp_accounts'); 

            }
        }

        if ($prodindex > 0) {
            $grandtotal = $grandtotal - $order_discount;
            $order_discount_percentage = order_discount_percentage($order_discount,$grandprice);
            $this->db->delete('cberp_delivery_note_items', ['delivery_note_number'=>$delivery_note_number]);
            $this->db->insert_batch('cberp_delivery_note_items', $productlist);
            // $this->db->insert_batch('cberp_average_cost', $cost_data_list);
            $this->db->set(array('discount' => numberClean(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => numberClean(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc),  'subtotal'=>$grandtotal,'total_amount'=>$grandtotal,'order_discount_percentage'=>$order_discount_percentage));
            $this->db->where('delivery_note_number', $delivery_note_number);
            $this->db->update('cberp_delivery_notes');
            //erp2024 commented shoptype 06-03-2024
            // if($shop_type=='Retail Shop'){
                
                //commented all accounts transactions except - inventory and cost of goods(07-02-2025)
                // cost of goods transactions transaction 07-02-2025
                $cost_of_goods_data =  [
                    'acid' => $default_cost_of_goods_account,
                    'type' => 'Expense',
                    'cat' => 'Deliverynote',
                    'debit' => $grand_product_cost,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal + ' . $grand_product_cost, FALSE);
                $this->db->where('acn', $default_cost_of_goods_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $cost_of_goods_data);
                // Inventory transaction 07-02-2025
                $inventory_data =  [
                    'acid' => $default_inventory_account,
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'credit' => $grand_product_cost,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->set('lastbal', 'lastbal - ' . $grand_product_cost, FALSE);
                $this->db->where('acn', $default_inventory_account);
                $this->db->update('cberp_accounts'); 
                $this->db->insert('cberp_transactions', $inventory_data);


                // erp2024 transactions starts 06-12-2024
               //$grandprice grand total price of all products without discount
                $invoice_receivable_account_details = default_chart_of_account('accounts_receivable');
                $latest_total = $total;
                $receivable_data = [
                    'acid' => $invoice_receivable_account_details,
                    'type' => 'Asset',
                    'cat' => 'Deliverynote',
                    'debit' => numberClean($grandprice),
                    // 'debit' => $total,
                    'eid' => $this->session->userdata('id'),
                    'date' => date('Y-m-d'),
                    'transaction_number'=>$transaction_number,
                ];
                $this->db->insert('cberp_transactions',$receivable_data);
                $this->db->set('lastbal', 'lastbal + ' .$total, FALSE);
                $this->db->where('acn', $invoice_receivable_account_details);
                $this->db->update('cberp_accounts'); 


                if (($groupedData)) {
                    $this->db->insert_batch('cberp_transactions', $groupedData);
                }
                // erp2024 transactions ends 06-12-2024

                //erp2024 totaldiscount transaction 06-12-2024 starts
                // if($total_discount>0)
                // {
                //     $discount_account_details = default_chart_of_account('sales_discount');
                //     $discount_data = [
                //         'acid' => $discount_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'debit' => $total_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$discount_data);
                
                //     $this->db->set('lastbal', 'lastbal + ' .$total_discount, FALSE);
                //     $this->db->where('acn', $discount_account_details);
                //     $this->db->update('cberp_accounts'); 

                    
                //     $total_discount_credit = [
                //         'acid' => $invoice_receivable_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'credit' => $total_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$total_discount_credit);
                //     $this->db->set('lastbal', 'lastbal - ' .$total_discount, FALSE);
                //     $this->db->where('acn', $invoice_receivable_account_details);
                //     $this->db->update('cberp_accounts'); 
                // }
                // if($order_discount)
                // {
                //     $order_discount_account_number = default_chart_of_account('order_discount');
                //     $discount_data1 = [
                //         'acid' => $order_discount_account_number,
                //         // 'account' => $order_discount_account_details['holder'],
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'debit' => $order_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];

                //     $this->db->insert('cberp_transactions',$discount_data1);
                //     $this->db->set('lastbal', 'lastbal + ' .$order_discount, FALSE);
                //     $this->db->where('acn', $order_discount_account_number);
                //     $this->db->update('cberp_accounts'); 

                //     $order_discount_data_credit = [
                //         'acid' => $invoice_receivable_account_details,
                //         'type' => 'Asset',
                //         'cat' => 'Deliverynote',
                //         'credit' => $order_discount,
                //         'eid' => $this->session->userdata('id'),
                //         'date' => date('Y-m-d'),
                //         'transaction_number'=>$transaction_number,
                //         // 'invoice_number'=>$invoice_number
                //     ];
                //     $this->db->insert('cberp_transactions',$order_discount_data_credit);
                //     $this->db->set('lastbal', 'lastbal - ' .$order_discount, FALSE);
                //     $this->db->where('acn', $invoice_receivable_account_details);
                //     $this->db->update('cberp_accounts');
                // }


            // }
            
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please choose product from product list. Go to Item manager section if you have not added the products."));
            $transok = false;
        } 
        //sales order items ends
    
    
        // $this->db->trans_start();
        $flag = false;
        $transok = true;
        
        echo json_encode(array('status' => 'Success','id'=>$delivery_note_number));
            $transok = false;
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }
}
