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

class Productrequest extends CI_Controller
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
        $this->load->model('Productrequest_modal', 'productrequest');
        $this->li_a = 'Product Request';
       

    }

    public function index()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['permissions'] = load_permissions('Sales','Purchase Requests','Manage Buy Requests');
        $head['title'] = "Product Request";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model');       
        $condition = "";
        $data['counts'] = $this->invoices_model->get_dynamic_count('product_request','requested_date','requested_qty',$condition);
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/product-request-index',$data);
        $this->load->view('fixed/footer');
    }


    public function ajax_list()
    {
        $catid = $this->input->get('id');
        $sub = $this->input->get('sub');

        if ($catid > 0) {
            $list = $this->productrequest->get_datatables($catid, '', $sub);
        } else {
            $list = $this->productrequest->get_datatables();
        }
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $row[] = $no;
            $pid = $prd->id;
            $row[] =  $prd->product_name;
            $row[] = $prd->product_code;            
            $row[] = $prd->requested_qty;
            $row[] = $prd->name;
            $row[] = $prd->warehouse_from_title;            
            // $row[] = $prd->warehouse_to_title;            
            $row[] = $prd->priority;
            $row[] = $prd->expectedby;
            
            if($prd->requested_status=="Pending"){
                $status = "<span class='st-partial'>".$prd->requested_status."</span>";
            }
            else{
                $status = "<span class='st-active'>".$prd->requested_status."</span>";
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
            "recordsTotal" => $this->productrequest->count_all($catid, '', $sub),
            "recordsFiltered" => $this->productrequest->count_filtered($catid, '', $sub),
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
        $data['warehouses'] = $this->products->warehouse_list();
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/product-request-add', $data);
        $this->load->view('fixed/footer');            
    }

    public function addaction()  {
      
        $products = $this->input->post('productid');
        $transferqty = $this->input->post('transferqty');
        $product_qty = $this->input->post('product_qty');
        $totalPrdts = count($products);
        // $warehouse_to = $this->input->post('warehouse_to');
        $priority = $this->input->post('priority');
        $expectedby = $this->input->post('expectedby');
        $note = $this->input->post('note');

        $warehouse_from = $this->productrequest->fetchWarehouse($this->session->userdata('id'));

        if ($totalPrdts > 0) {
            $requestMade = false;        
            for ($i = 0; $i < $totalPrdts; $i++) {
                if (!empty($products[$i]) && !empty($transferqty[$i])) {                
                    $requestlist = array(
                        'product_id'     => $products[$i],
                        'requested_qty'  => $transferqty[$i],   
                        // 'warehouse_to_id'=> $warehouse_to,
                        'priority'       => $priority,
                        'expectedby'     => $expectedby,
                        'note'           => $note,
                        'warehouse_from_id'=> $warehouse_from,
                        'requested_date'   => date('Y-m-d H:i:s'),
                        'requested_by'   => $this->session->userdata('id')
                    );
        
                    if (!empty($requestlist)) {
                        $this->db->insert('product_request', $requestlist);
                        // echo $this->db->last_query();
                        $requestMade = true;
                    }
                }
            }
        
            if ($requestMade) {
                $target = base_url() . "Productrequest/";
                echo json_encode(array(
                    'status' => 'Success',
                    'message' => $this->lang->line('Product request has been sent') . " <a href='$target' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> View Buy Requests </a> &nbsp;"
                ));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => "Please choose all fields."));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' => "Please choose product."));
        }
        
    }

    public function Productrqst_to_stocktransfer(){
        $selectedIds = $this->input->post('selecteditems');
        $results = $this->productrequest->request_data_details($selectedIds);
        if(!empty($results))
        {
            $this->session->set_userdata("requestids", $results);
            echo json_encode(array('status' => '1', 'data' => $results ));
        }
        else{
            echo json_encode(array('status' => '0' ));
        }
    }
    public function Productrqst_to_stocktransfer_list(){
        $head['title'] = "Product Request to Stock Transfer";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['requestdata'] = $this->session->userdata('requestids');
        
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/productrequest-to-stocktransfer', $data);
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
            $this->session->unset_userdata('requestids');
            $this->productrequest->stock_transfer_submit($from_warehouse, $products_l, $to_warehouse, $qty);
        }
    }

    //erp2024 newly added function for salesorder to purchase request 18-06-2024
    public function purchaserequest()
    {
        
        
        // if (!$this->aauth->premission(10)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $salesPro = $this->input->post('selectedProducts');
        $data['selectedProducts'] = $salesPro;
        $tid = $this->session->userdata("orderid");
        $productIds = explode(",",$salesPro);
        $data['products'] = $this->products->products_list_by_id($productIds);
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/product-request-add-from-salesorder', $data);
        $this->load->view('fixed/footer');            
    }

    public function purchaserequestaction()  {
      
        $products = $this->input->post('product-name');
        $warehousefrom = $this->input->post('warehousefrom');
        $transferqty = $this->input->post('transferqty');
        $product_qty = $this->input->post('product_qty');
        $totalPrdts = count($products);
        $warehouse_to = $this->input->post('warehouse_to');
        
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
                $target = base_url() . "SalesOrders/";
                echo json_encode(array(
                    'status' => 'Success',
                    'message' => $this->lang->line('Material request has been sent') . $this->lang->line('Back to sales order') . " <a href='$target' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span> Back To Sales Order </a> &nbsp;"
                ));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => "Please choose all fields."));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' => "Please choose product."));
        }
        
    }

    public function purchase_request_to_quote()
    {       
        $data =[];
        $head['title'] = "Purchase Request To Quote";
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/purchase-request-to-quote', $data);
        $this->load->view('fixed/footer');            
    }
    public function purchase_request_to_purchase_order()
    {       
        $data =[];
        $head['title'] = "Purchase Request To Quote";
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/purchase-request-to-purchase-order', $data);
        $this->load->view('fixed/footer');            
    }
    //erp2024 newly added function for salesorder to purchase request 18-06-2024 ends
}
