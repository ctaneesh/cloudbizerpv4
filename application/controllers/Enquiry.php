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

class Enquiry extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();
        $this->load->model('enquiry_model', 'enquiry');        
        $this->load->model('invoices_model', 'invocies');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(3)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        
    }


    //invoices list
    public function index()
    {
        
        $head['title'] = "Customer Enquiry";
        $data['enquirycounts'] =  $this->invocies->get_enquiry_count();
        $this->load->view('fixed/header', $head);
        $this->load->view('enquiry/enquiry',$data);
        $this->load->view('fixed/footer');
    }

    public function ajax_list()
    {

        
        
        $permissions = load_permissions('CRM','Leads','Manage Lead');
        $functions = array_column($permissions, 'function');
        $accept_convert = !in_array('Accept and Convert', $functions) ? 'd-none' : '';
        // echo "<pre>"; print_r($functions);  die();

        // $dategap = !empty($this->input->post('dategap'))?$this->input->post('dategap'):"";
        $list = $this->enquiry->get_datatables($this->limited);
        
        $data = array();

        $no = $this->input->post('start');

       

        foreach ($list as $enquiry) {
            $no++;
            $row = array();
            $row[] = $no;
            // $row[] = $enquiry->lead_number;
            $row[] = '<a  href="' .base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" class="breaklink">' .$enquiry->lead_number. '</a>';
            $row[] = $enquiry->customer_name;
            $row[] = ucfirst($enquiry->customer_type);
            $row[] = $enquiry->customer_phone;
            $row[] = number_format($enquiry->total,2);
            // $row[] = $enquiry->customer_reference_number;
            $created_date = (!empty($enquiry->created_date))?dateformat($enquiry->created_date):""; 
            $row[] = $created_date."<br>".$enquiry->createdbyname;
            // $row[] = (!empty($enquiry->created_date))?dateformat($enquiry->created_date):"";            
            $row[] = $enquiry->assigned_person;
            $colorcode = get_color_code($enquiry->due_date);
            $dudate = (!empty($enquiry->due_date))?dateformat($enquiry->due_date):"";
            $row[] = '<b style="color:'.$colorcode.'">'.$dudate.'</b>';
            if($enquiry->enquiry_status=="Open"){
                $status1 = '<span class="st-inactive">' . ucwords($enquiry->enquiry_status) . '</span>';
            }
            else if($enquiry->enquiry_status=="Closed"){
                $status1 = '<span class="st-active">Converted To Quote</span>';
            }
            else if($enquiry->enquiry_status=="Completed"){
                $status1 = '<span class="st-created">Created</span><br>';
                // $status1 = '<span class="st-active">Completed</span><br>';
                // $status1 .= '<span class="st-active">Waiting for approval</span>';
            }
            else if($enquiry->enquiry_status=="Draft"){
                $status1 = '<span class="st-draft">Draft</span><br>';
            }
            else{
                $status1 = '<span class="st-pending">Created</span>';
            }
            $row[] = $status1;
            $checkflg = true;
            // if((($enquiry->pickup_flag=='1') && ((!empty($enquiry->picked_by))) && ($this->session->userdata('id') != $enquiry->picked_by))){
            //     $checkflg = false;
            // }
            
            if(($enquiry->enquiry_status=='Assigned') && $checkflg ==true && ($enquiry->assigned_to == $this->session->userdata('id')))
            {
                $row[] = '<a  href="' . base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" class="btn btn-crud btn-secondary btn-sm" ><i class="fa fa-pencil"></i></a> <a   href="' . base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" class="btn btn-crud btn-secondary btn-sm '.$accept_convert.'"><i class="fa fa-exchange"></i> Accept & Convert</a>';
            }
            else  if(($enquiry->enquiry_status=='Open')){
                $row[] = '<a  href="' . base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" class="btn btn-crud btn-secondary btn-sm" ><i class="fa fa-pencil"></i></a>';
            }
            else  if(($enquiry->enquiry_status=='Closed')){
                $row[] = "";
            }
            else if($enquiry->enquiry_status=="Completed"){
                $row[] = '<a  href="' . base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" class="btn btn-crud btn-secondary btn-sm" ><i class="fa fa-exchange"></i> Converted to Quote</a>';
            }
            else if($enquiry->enquiry_status=="Draft"){
                $row[] = '<a  href="' . base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" class="btn btn-crud btn-secondary btn-sm" ><i class="fa fa-pencil"></i></a>';
            }
            else{
                $row[] = "";
               
                // $row[] = "<button class='btn btn-sm btn-secondary' disabled>Processing</button>'";
            }
            
            $data[] = $row;
        }
        // print_r($data); 
        // die();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->enquiry->count_all($this->limited),
            "recordsFiltered" => $this->enquiry->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }
    
    // public function ajax_list()
    // {
    //     $list = $this->invocies->get_datatables($this->limited);
    //     $data = array();
    //     $no = $this->input->post('start');
    //     foreach ($list as $enquiry) {
    //         $no++;
    //         $row = array();
    //         $row[] = $no;
    //         // $row[] = $enquiry->lead_number;
    //         $row[] = '<a  href="' .base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" target="_blank">&nbsp; ' .$enquiry->lead_number. '</a>';
    //         $row[] = $enquiry->customer_name;
    //         $row[] = ucfirst($enquiry->customer_type);
    //         $row[] = $enquiry->customer_phone;
    //         $row[] = $enquiry->customer_email;
    //         $row[] = $enquiry->assigned_to;
    //         $row[] = (!empty($enquiry->created_date))?dateformat($enquiry->created_date):"";
    //         $row[] = (!empty($enquiry->due_date))?dateformat($enquiry->due_date):"";
    //         $row[] = '<span class="st-' . $enquiry->enquiry_status . '">' . ucwords($enquiry->enquiry_status) . '</span>';
    //         $row[] = '<a  href="' . base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" class="btn btn-secondary btn-sm" target="_blank"><i class="fa fa-pencil"></i></a> <a target="_blank" href="' . base_url("quote/create"). '" class="btn btn-secondary btn-sm"><i class="fa fa-plus"></i></a>';
            
    //         $data[] = $row;
    //     }
    //     $output = array(
    //         "draw" => $this->input->post('draw'),
    //         "recordsTotal" => $this->invocies->count_all($this->limited),
    //         "recordsFiltered" => $this->invocies->count_filtered($this->limited),
    //         "data" => $data,
    //     );
    //     //output to json format
    //     echo json_encode($output);
    // }

    public function ajax_list_for_enquiry()
    {
        $dategap = !empty($this->input->post('dategap'))?$this->input->post('dategap'):"";
        $list = $this->enquiry->get_datatables($dategap);
        
        $data = array();

        $no = $this->input->post('start');


        foreach ($list as $enquiry) {
            $no++;
            $row = array();
            $row[] = $no;
            // $row[] = $enquiry->lead_number;
            $row[] = '<a  href="' .base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" target="_blank">&nbsp; ' .$enquiry->lead_number. '</a>';
            $row[] = $enquiry->customer_name;
            $row[] = ucfirst($enquiry->customer_type);
            $row[] = $enquiry->customer_phone;
            $row[] = $enquiry->total;
            $row[] = $enquiry->assigned_person;
            $row[] = (!empty($enquiry->created_date))?dateformat($enquiry->created_date):"";
            $row[] = (!empty($enquiry->due_date))?dateformat($enquiry->due_date):"";
            if($enquiry->enquiry_status=="Assigned"){
                $status = '<span class="st-active">' . ucwords($enquiry->enquiry_status) . '</span>';
            }
            
            else if($enquiry->enquiry_status=="Open"){
                $status = '<span class="st-partial">' . ucwords($enquiry->enquiry_status) . '</span>';
            }
            
            else{
                $status = '<span class="st-inactive">' . ucwords($enquiry->enquiry_status) . '</span>';
            }
            $row[] = $status;
            $row[] = '<a  href="' . base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" class="btn btn-crud btn-secondary btn-sm" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a> <a target="_blank" href="' . base_url("quote/convert_to_quote?id=$enquiry->lead_id"). '" class="btn btn-crud btn-secondary btn-sm" title="Convert to quote"><i class="fa fa-exchange"></i> Convert To Quote</a>';
            
            $data[] = $row;
        }
        // print_r($data); 
        // die();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->enquiry->count_all(),
            "recordsFiltered" => $this->enquiry->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function ajax_list_condition()
    {
        
        $list = $this->enquiry->get_datatables();
        
        $data = array();

        $no = $this->input->post('start');


        foreach ($list as $enquiry) {
            $no++;
            $row = array();
            $row[] = $no;
            // $row[] = $enquiry->lead_number;
            $row[] = '<a  href="' .base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" target="_blank">&nbsp; ' .$enquiry->lead_number. '</a>';
            $row[] = $enquiry->customer_name;
            $row[] = ucfirst($enquiry->customer_type);
            $row[] = $enquiry->customer_phone;
            $row[] = $enquiry->customer_email;
            $row[] = $enquiry->assigned_to;
            $row[] = (!empty($enquiry->created_date))?dateformat($enquiry->created_date):"";
            $row[] = (!empty($enquiry->due_date))?dateformat($enquiry->due_date):"";
            $row[] = '<span class="st-' . $enquiry->enquiry_status . '">' . ucwords($enquiry->enquiry_status) . '</span>';
            $row[] = '<a  href="' . base_url("invoices/customer_leads?id=$enquiry->lead_id"). '" class="btn btn-crud btn-secondary btn-sm" target="_blank"><i class="fa fa-pencil"></i> ' . $this->lang->line('Edit') . '</a> &nbsp;<a target="_blank" href="' . base_url("quote/convert_to_quote?id=$enquiry->lead_id"). '" class="btn btn-crud btn-secondary btn-sm"><i class="fa fa-plus"></i> Quote</a>';
            
            $data[] = $row;
        }
        // print_r($data); 
        // die();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->enquiry->count_all(),
            "recordsFiltered" => $this->enquiry->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }
    public function view()
    {
        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $data['enqurymain'] = $this->enquiry->enquiry_details($tid);
        if ($data['enqurymain']['customer_id'] == $this->session->userdata('user_details')[0]->cid) {
            $enqID = $data['enqurymain']['lead_id'];
            $head['title'] = "Enquiry $enqID";
            $data['products'] = $this->enquiry->enquiry_products($enqID);
            $this->load->view('fixed/header', $head);
            $this->load->view('enquiry/view', $data);
            $this->load->view('fixed/footer');
        }
        
    }

    public function approve()
    {
        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $head['title'] = "Quote $tid";
        $data['invoice'] = $this->enquiry->quote_details($tid);
        if ($data['invoice']['csd'] == $this->session->userdata('user_details')[0]->cid) {
            $this->enquiry->update_status($tid);
            $data['products'] = $this->enquiry->quote_products($tid);


            $data['employee'] = $this->enquiry->employee($data['invoice']['eid']);
            $m=array('message'=>'Approved!');
            $this->session->set_flashdata('item',$m);
            $this->session->keep_flashdata('item',$m);
            redirect(base_url('quote/view?id=' . $tid));


        }

    }
    public function edit()
    {
       
        $tid = intval($this->input->get('id'));
        $data['enquirymain'] = $this->enquiry->enquiry_details($tid);
        $enqID = $data['enquirymain']['lead_id'];
        $data['products'] = $this->enquiry->enquiry_products($enqID);
        $head['title'] = "Edit Quote #" . $enqID;
        $this->load->view('fixed/header', $head);
        $this->load->view('enquiry/edit', $data);
        $this->load->view('fixed/footer');
    }
    public function editaction()
    {    
        
        $totalPrdts = count($this->input->post()['product_name']);
        $lead_id = $this->input->post('lead_id');
        $id = $this->input->post('prid');
        $products = $this->input->post();
        $productlist =[];     
        $this->db->trans_start();
        $transok = true;
            $enquiryMain['enquiry_requested_date'] = $this->input->post('enquiry_requested_date'); 
            $enquiryMain['enquiry_note'] = $this->input->post('enquiry_note'); 
            $enquiryMain['enquiry_message'] = $this->input->post('enquiry_message'); 
            $enquiryMain['enquiry_date'] =  date("Y-m-d"); 
            $enquiryMain['status'] = "pending";
            $enquiryMain['lead_id'] = $lead_id;
            
            if($totalPrdts>0){
                $this->db->where('lead_id', $lead_id);
                $this->db->delete('customer_enquiry_items');

                $this->db->where('id', $id);
                $this->db->update('customer_enquiry', $enquiryMain);
                
                for ($i = 0; $i < $totalPrdts; $i++) {
                    if(!empty($products['product_name'][$i])){                            
                        $productlist['lead_id'] = $lead_id;
                        $productlist['product_qty'] = $products['product_qty'][$i];
                        $productlist['product_id'] = $products['pid'][$i];
                        if (!empty($productlist)) {
                            $this->db->insert('customer_enquiry_items', $productlist);
                        }
                    }
                }
            
                if(empty($productlist)) {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product."));
                    $transok = false;
                }
                else{
                    $target = base_url()."enquiry/";
                    echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Enquiry has  been updated') . " <a href='$target' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> View </a> &nbsp; &nbsp;<a href='create' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Create Enquiry') . "  </a>"));
                }
                
                if ($transok) {
                    $this->db->trans_complete();
                } else {
                    $this->db->trans_rollback();
                }
            }
            else{
                echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product."));
                    $transok = false;
            }
            if ($transok) {
                $this->db->trans_complete();
            } else {
                $this->db->trans_rollback();
            }
    }
    public function convert_to_quote()
    {    
       
        $totalPrdts = count($this->input->post()['product_name']);
        $lead_id = $this->input->post('lead_id');
        $id = $this->input->post('prid');
        $products = $this->input->post();
        $productlist =[];
        $transok = true;
            $enquiryMain['enquiry_requested_date'] = $this->input->post('enquiry_requested_date'); 
            // $enquiryMain['enquiry_note'] = $this->input->post('enquiry_note'); 
            // $enquiryMain['enquiry_message'] = $this->input->post('enquiry_message'); 
            // $enquiryMain['enquiry_date'] =  date("Y-m-d"); 
            $enquiryMain['status'] = "Reviewed";
            $enquiryMain['lead_id'] = $lead_id;
            if($totalPrdts>0){
                $this->db->where('lead_id', $lead_id);
                $this->db->delete('customer_enquiry_items');

                $this->db->where('id', $id);
                $this->db->update('customer_enquiry', $enquiryMain);
                $subtotal = 0;
                $total = 0;
                $quotemain = $this->enquiry->enquiry_details($id);
                if(!empty($quotemain)){
                    $quote_m['tid'] = $this->enquiry->find_tid();
                    $quote_m['invoicedate'] = date("Y-m-d");
                    $quote_m['csd'] = $quotemain['customer_id'];
                    $quote_m['eid'] = (int)$_SESSION['id'];
                    $quote_m['items'] = $totalPrdts;
                    $this->db->insert('cberp_quotes', $quote_m);
                    $last_insert_id = $this->db->insert_id();
                    $quoteItems=[];
                    for ($i = 0; $i < $totalPrdts; $i++) {
                        if(!empty($products['product_name'][$i])){          
                            $productID =  $products['pid'][$i];                 
                            $productlist['lead_id'] = $lead_id;
                            $productlist['product_qty'] = $products['product_qty'][$i];
                            $productlist['product_id'] = $productID;
                            $subtotal = $this->enquiry->product_price($productID,$products['product_qty'][$i]); 
                            $productDetails = $this->enquiry->product_details($productID);
                            $total += $subtotal;
                            $quoteItems['tid'] = (int)$last_insert_id;
                            $quoteItems['pid'] = (int)$productID;
                            $quoteItems['product'] = $productDetails->product_name;
                            $quoteItems['code'] = $productDetails->product_code;
                            $quoteItems['qty'] = $products['product_qty'][$i];
                            $quoteItems['price'] = $productDetails->product_price;
                            $quoteItems['subtotal'] = $subtotal;
                            $quoteItems['product_des'] = $productDetails->product_des;
                            if (!empty($productlist)) {
                                $this->db->insert('customer_enquiry_items', $productlist);
                            }
                            if(!empty($quoteItems)){
                                $this->db->insert('cberp_quotes_items', $quoteItems);
                             }
                        }
                    }
                   
                    
                    $quote_m['total'] = $total;                    
                    $quote_m['subtotal'] = $total;
                    $this->db->where('id', $last_insert_id);
                    $this->db->update('cberp_quotes', $quote_m);
                }
                
                if(empty($productlist)) {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product."));
                    $transok = false;
                }
                else{
                    $target = base_url()."enquiry/";
                    $quoteurl = base_url()."quote/";
                    echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('Enquiry has been successfully converted into a quote!') . " <a href='$target' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> Convert other enquirie to quote </a> &nbsp; &nbsp;<a href='$quoteurl' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span> " . $this->lang->line('Go to quote') . "  </a>"));
                }
            }
            else{
                echo json_encode(array('status' => 'Error', 'message' =>
                        "Please choose product."));
                    $transok = false;
            }
    }

    public function search()
	{
		$result = array();
		$out = array();
		$row_num = $this->input->post('row_num', true);
		$name = $this->input->post('name_startsWith', true);
		$wid = $this->input->post('wid', true);
		$query = $this->db->query("SELECT cberp_products.pid,cberp_products.product_name FROM cberp_products  WHERE  (UPPER(cberp_products.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(cberp_products.product_code) LIKE '" . strtoupper($name) . "%') LIMIT 6");
        // print_r($query); die();
		$result = $query->result_array();

        foreach ($result as $row) {
            $name = array($row['product_name'], $row['pid']);
            array_push($out, $name);
        }
        echo json_encode($out);

	}
 
   
}
