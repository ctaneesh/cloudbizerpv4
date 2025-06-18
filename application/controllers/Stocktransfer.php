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

class Stocktransfer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");      
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(2)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $this->load->model('Stocktransfer_modal', 'stocktransfer');
        $this->li_a = 'Stock Transfer';
       

    }

    public function index()
    {
        
        $data['permissions'] = load_permissions('Stock','Stock Transfer','Stock Transfer List');
        $head['title'] = "Stock Transfer Lists";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model');       
        $condition = "";
        $data['counts'] = $this->invoices_model->get_dynamic_count('stock_transfer_wh_to_wh','transfered_dt','intransit_qty',$condition);
        $this->load->view('fixed/header', $head);
        $this->load->view('products/stock-transfer-list', $data);
        $this->load->view('fixed/footer');
    }


    public function ajax_list()
    {
        $catid = $this->input->get('id');
        $sub = $this->input->get('sub');

        if ($catid > 0) {
            $list = $this->stocktransfer->get_datatables($catid, '', $sub);
        } else {
            $list = $this->stocktransfer->get_datatables();
        }
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $row[] = $no;
            $pid = $prd->id;
            $row[] =  $prd->product_code;
            $row[] = $prd->product_name;
            $row[] = $prd->unit;
            $row[] = $prd->requested_qty;            
            $row[] = $prd->fromwarehouse;
            $row[] = $prd->towarehouse;
            $transferdata = $prd->transferemployee . '<br>' . date('d-m-Y H:i:s', strtotime($prd->transfered_dt));
            $row[] = (!empty($prd->transferemployee))?$transferdata:"";    
            $row[] = $prd->intransit_qty;
            $receiveddata = $prd->recievedemployee . '<br>' . date('d-m-Y H:i:s', strtotime($prd->received_dt));
            $row[] = (!empty($prd->recievedemployee))?$receiveddata:"";
            if($prd->status=="Intransit"){
                $status = "<span class='st-pending'>".$prd->status."</span>";
            }
            else{
                
                $status = "<span class='st-accepted'>" . $prd->status . "</span>";

            }
            $row[] = $status;
            // $row[] = $prd->name;

            $row[] = '<a href="' . base_url("stocktransfer/print_single_trasfernote?report=$prd->id") . '" target="_blank" class="btn btn-sm btn-secondary">Reprint</a>';

            $row[] = $prd->id;
            $row[] = $pid + 1000;
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->stocktransfer->count_all($catid, '', $sub),
            "recordsFiltered" => $this->stocktransfer->count_filtered($catid, '', $sub),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    //erp2024 receive item section 19-06-2024
    public function receive_item(){
        $selectedIds = $this->input->post('selecteditems');
        $results = $this->stocktransfer->receive_data_details($selectedIds);
        if(!empty($results))
        {
            $this->session->set_userdata("itemlistforreceiving", $results);
            echo json_encode(array('status' => '1', 'data' => $results ));
        }
        else{
            echo json_encode(array('status' => '0' ));
        }
    }
    public function receive_item_list(){
        $data['permissions'] = load_permissions('Stock','Stock Transfer','Stock Transfer List','','Receive Items');
        $head['title'] = "Items For Receiving";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['requestdata'] = $this->session->userdata('itemlistforreceiving');        
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/items-for-receiving', $data);
        $this->load->view('fixed/footer');   
    }

    public function item_recieve_submit()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        if ($this->input->post()) {
            $products_l = $this->input->post('productid');
            $to_warehouse = $this->input->post('warehouse_to_id');
            $qty = $this->input->post('intransit_qty');
            $requested_id = $this->input->post('request_id');
            $this->session->unset_userdata('itemlistforreceiving');
            $this->stocktransfer->item_recieve_submit($products_l, $to_warehouse, $qty, $requested_id);
        }
    }

    
    public function print_trasfernote()
    {        

        $printed_ids = $this->session->userdata('printedids'); 
        $this->session->unset_userdata('printedids');
        $warehousefrom = $this->stocktransfer->from_warehousedetails($printed_ids[0]);
        $warehouseto = $this->stocktransfer->to_warehousedetails($printed_ids[0]);
        $data['warehousefrom'] = $warehousefrom['title'].'<br>'.$warehousefrom['extra'];
        $data['warehouseto'] = $warehouseto['title'].'<br>'.$warehouseto['extra'];        
        $data['products'] = $this->stocktransfer->print_products_details($printed_ids);
        $loc = location($this->aauth->get_user()->loc);
        $data['companyName']= $loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['company'] = $company;

        $html = $this->load->view('deliverynotes/item-transferpdf', $data, true);         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('transfer-note' . $pay_acc . '.pdf', 'I');
    }

    public function print_single_trasfernote()
    {  
        
        $orderid = $this->input->get('report');        
        $warehousefrom = $this->stocktransfer->from_warehousedetails_byid($orderid);
        $warehouseto = $this->stocktransfer->to_warehousedetails_byid($orderid);
        $data['products'] = $this->stocktransfer->print_products_details_byid($orderid);
        // print_r($data['products']);
        // die();
        
        $data['warehousefrom'] = $warehousefrom['title'].'<br>'.$warehousefrom['extra'];
        $data['warehouseto'] = $warehouseto['title'].'<br>'.$warehouseto['extra'];        
        $loc = location($this->aauth->get_user()->loc);
        $data['companyName']= $loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['company'] = $company;

        $html = $this->load->view('deliverynotes/item-transferpdf', $data, true);         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('transfer-note' . $pay_acc . '.pdf', 'I');
    }

    public function print_trasfernote_direct()
    {        

        $printed_ids = $this->session->userdata('directtransferids'); 
        // $this->session->unset_userdata('directtransferids');
        $warehousefrom = $this->stocktransfer->from_warehousedetails_byid($printed_ids[0]);
        $warehouseto = $this->stocktransfer->to_warehousedetails_byid($printed_ids[0]);
        $data['warehousefrom'] = $warehousefrom['title'].'<br>'.$warehousefrom['extra'];
        $data['warehouseto'] = $warehouseto['title'].'<br>'.$warehouseto['extra'];        
        $data['products'] = $this->stocktransfer->print_products_details_byid($printed_ids);
        $loc = location($this->aauth->get_user()->loc);
        $data['companyName']= $loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['company'] = $company;

        // echo "<pre>"; print_r($printed_ids); print_r($data); die();
        $html = $this->load->view('deliverynotes/item-transferpdf', $data, true);         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('transfer-note' . $pay_acc . '.pdf', 'I');
    }
}