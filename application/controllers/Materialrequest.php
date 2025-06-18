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

class Materialrequest extends CI_Controller
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
        $this->load->model('products_model', 'products');
        $this->load->model('materialrequest_modal', 'matrialrequest');
        $this->li_a = 'Material Request';
       

    }

    public function index()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['permissions'] = load_permissions('Stock','Stock Transfer','Internal Material Request');
        $head['title'] = "Material Request";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model');       
        $condition = "";
        $data['counts'] = $this->invoices_model->get_dynamic_count('material_request','requested_date','requested_qty',$condition);
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/material-request-index',$data);
        $this->load->view('fixed/footer');
    }


    public function ajax_list()
    {
        $catid = $this->input->get('id');
        $sub = $this->input->get('sub');

        if ($catid > 0) {
            $list = $this->matrialrequest->get_datatables($catid, '', $sub);
        } else {
            $list = $this->matrialrequest->get_datatables();
        }
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $row[] = $no;
            $pid = $prd->id;
            $row[] = $prd->product_name;
            $row[] = $prd->product_code;            
            $row[] = $prd->warehouse_from_title;
            $row[] = $prd->warehouse_to_title;
            $row[] = $prd->requested_qty;
            $row[] = date('d-m-Y H:i:s', strtotime($prd->requested_date));
            if($prd->requested_status=="Pending"){
                $status = "<span class='st-pending'>".$prd->requested_status."</span>";
            }
            else{
                $status = "<span class='st-accepted'>".$prd->requested_status."</span>";
            }
            $row[] = $status;
            // $row[] = "--";
            // Add the product ID to the row data
            $row[] = $prd->id;
            $row[] = $pid + 1000;
            
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->matrialrequest->count_all($catid, '', $sub),
            "recordsFiltered" => $this->matrialrequest->count_filtered($catid, '', $sub),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    function add()
    {
                
        // if (!$this->aauth->premission(10)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $data['permissions'] = load_permissions('Stock','Stock Transfer','Internal Material Request','','Add New');
        $data['warehouses'] = $this->products->warehouse_list();
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/matrial-request-add', $data);
        $this->load->view('fixed/footer');            
    }

    public function addaction()  {
      
        $products = $this->input->post('productid');
        $warehousefrom = $this->input->post('warehousefrom');
        $transferqty = $this->input->post('transferqty');
        $product_qty = $this->input->post('product_qty');
        $totalPrdts = count($products);
        $warehouse_to = $this->input->post('warehouse_to');
        $requestlist = [];
        if ($totalPrdts > 0) {
            $requestMade = false;
        
            for ($i = 0; $i < $totalPrdts; $i++) {
                if (!empty($products[$i]) && !empty($warehousefrom[$i]) && !empty($transferqty[$i])) {                
                    $requestlist = array(
                        'product_id'     => $products[$i],
                        'warehouse_from' => $warehousefrom[$i],
                        'requested_qty'  => $transferqty[$i],   
                        'warehouse_to'   => $warehouse_to,
                        'requested_date'   => date('Y-m-d H:i:s'),
                        'requested_by'   => $this->session->userdata('id')
                    );
        
                    if (!empty($requestlist)) {
                       $this->db->insert('material_request', $requestlist);
                        // echo $this->db->last_query();
                        $requestMade = true;
                    }
                }
            }
            if ($requestMade) {
                $target = base_url() . "Materialrequest/";
                echo json_encode(array(
                    'status' => 'Success',
                    'message' => $this->lang->line('Material request has been sent') . $this->lang->line('Back to sales order') . " <a href='$target' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span></a> &nbsp;"
                ));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => "Please choose all fields."));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' => "Please choose product."));
        }
        
    }

    public function materialrqst_to_stocktransfer(){
        $selectedIds = $this->input->post('selecteditems');
        $results = $this->matrialrequest->request_data_details($selectedIds);
        if(!empty($results))
        {
            $this->session->set_userdata("requestids", $results);
            echo json_encode(array('status' => '1', 'data' => $results ));
        }
        else{
            echo json_encode(array('status' => '0' ));
        }
    }
    public function materialrqst_to_stocktransfer_list(){
        $head['title'] = "Material Request to Stock Transfer";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['requestdata'] = $this->session->userdata('requestids');
        
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/matrialrequest-to-stocktransfer', $data);
        $this->load->view('fixed/footer');   
    }

    //erp2024 newly added function 13-06-2024
    public function stock_transfer_submit()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        if ($this->input->post()) {
            $products_l = $this->input->post('productid');
            $from_warehouse = $this->input->post('warehouse_from_id');
            $to_warehouse = $this->input->post('warehouse_to_id');
            $qty = $this->input->post('transfer_qty');
            $requested_qty = $this->input->post('requested_qty');
            $requested_id = $this->input->post('request_id');
            $this->session->unset_userdata('requestids');
            $this->matrialrequest->stock_transfer_submit($from_warehouse, $products_l, $to_warehouse, $qty, $requested_id,$requested_qty);
        }
    }

    
}
