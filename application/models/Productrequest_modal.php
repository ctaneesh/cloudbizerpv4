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

class Productrequest_modal extends CI_Model
{
    var $table = 'product_request';
    var $column_order = array(null, 'product_request.requested_qty', 'cberp_products.product_code', 'cberp_products.product_name', 'warehouse_from_warehouse.title', 'product_request.requested_status', null); //set column field database for datatable orderable
    var $column_search = array('product_request.requested_qty', 'cberp_products.product_code', 'cberp_products.product_name', 'warehouse_from_warehouse.title', 'product_request.requested_status'); //set column field database for datatable searchable
    var $order = array('product_request.id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id = '', $w = '', $sub = '')
    {
       

        $this->db->select('product_request.requested_qty, 
                   product_request.id,
                   cberp_products.product_name, 
                   cberp_products.product_code,
                   product_request.priority ,
                   product_request.expectedby ,
                   warehouse_from_warehouse.title AS warehouse_from_title, 
                   warehouse_from_warehouse.id AS warehouse_from_id, 
                   cberp_employees.name,
                   product_request.requested_status');
        $this->db->from('product_request');
        $this->db->join('cberp_store AS warehouse_from_warehouse', 'warehouse_from_warehouse.id = product_request.warehouse_from_id');
        // $this->db->join('cberp_store AS warehouse_to_warehouse', 'warehouse_to_warehouse.id = product_request.warehouse_to_id');
        $this->db->join('cberp_products', 'cberp_products.pid = product_request.product_id');
        $this->db->join('cberp_employees', 'cberp_employees.id = product_request.requested_by');
        $i = 0;
        if ($this->input->post('start_date') && $this->input->post('end_date'))
        {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(product_request.requested_date) BETWEEN '$start_date' AND '$end_date'");
        }

        foreach ($this->column_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) // here order processing
        {
            $this->db->order_by($this->column_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    
    }

    function get_datatables($id = '', $w = '', $sub = '')
    {
        if ($id > 0) {
            $this->_get_datatables_query($id, $w, $sub);
        } else {
            $this->_get_datatables_query();
        }
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered($id, $w = '', $sub = '')
    {
        if ($id > 0) {
            $this->_get_datatables_query($id, $w, $sub);
        } else {
            $this->_get_datatables_query();
        }

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        // $this->db->join('cberp_store', 'cberp_store.id = product_request.warehouse');
        // if ($this->aauth->get_user()->loc) {

        //     $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('cberp_store.loc', 0);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_store.loc', 0);
        // }
        return $this->db->count_all_results();
    }

    //erp2024 new functions 07-06-2024 ends
    //erp2024 new functions 13-06-2024 starts
    public function request_data_details($selectedIds) {
        
        $this->db->select('product_request.requested_qty, 
                   product_request.id,
                   cberp_products.product_name,
                   cberp_products.unit, 
                   cberp_products.product_code, 
                   cberp_products.pid as productid, 
                   warehouse_from_warehouse.title AS warehouse_from_title, 
                   warehouse_from_warehouse.id AS warehouse_from_id, 
                   warehouse_to_warehouse.title AS warehouse_to_title,
                   warehouse_to_warehouse.id AS warehouse_to_id,
                   product_request.requested_status');
        $this->db->from('product_request');
        $this->db->join('cberp_store AS warehouse_from_warehouse', 'warehouse_from_warehouse.id = product_request.warehouse_from');
        $this->db->join('cberp_store AS warehouse_to_warehouse', 'warehouse_to_warehouse.id = product_request.warehouse_to');
        $this->db->join('cberp_products', 'cberp_products.pid = product_request.product_id');
        $this->db->where_in('product_request.id', $selectedIds);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }
    
    public function stock_transfer_submit($from_warehouse1, $products_l, $to_warehouse1, $qty)
    {

        // echo "<pre>";
        // print_r($products_l);
        // die();
        $qtyArray = $qty;
        // $qtyArray = explode(',', $qty);
        $i = 0;  // Initialize the index for qtyArray
        $j=0;
        $flag=1;
        foreach ($products_l as $row) {
            $transferqty = 0;

            // Check if the current index exists in the qtyArray and get the transfer quantity
            if (array_key_exists($i, $qtyArray)) {
                $transferqty = $qtyArray[$i];  
            }

            // Fetch the stock quantity for the current product from the database
            $this->db->select('stock_qty');
            $this->db->from('cberp_product_to_store');
            $this->db->where('product_id', $row);
            $query = $this->db->get();
            $stock_result = $query->row_array();

            // Get the stock quantity from the query result
            $stock_qty = $stock_result['stock_qty'];
            // If the transfer quantity exceeds the stock quantity, return an error and stop execution
            if ($transferqty > $stock_qty) {
                $flag = 2;
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR-RECORD')));
                exit;  // Stop further execution of the script
            }

            $i++;  // Increment the index for the next iteration
        }
    // echo "<pre>";
        // print_r($products_l);
        // die();
        if($flag==1){
            foreach ($products_l as $row1) {
                $transferqty = 0;
                if (array_key_exists($j, $qtyArray)) {
                    $transferqty = $qtyArray[$j];  
                } 
                if (array_key_exists($j, $qtyArray)) {
                    $from_warehouse = $from_warehouse1[$j];  
                } 
                if (array_key_exists($j, $qtyArray)) {
                    $to_warehouse = $to_warehouse1[$j];  
                }                

                $this->db->select('stock_qty');
                $this->db->from('cberp_product_to_store');
                $this->db->where('product_id', $row1);
                $query = $this->db->get();
                $stock_result = $query->row_array();
                $stock_qty = $stock_result['stock_qty'];
                
                //
                //created_by created_dt updated_by updated_dt
                $data = [
                        "product_id" => $row1,
                        "store_id" => $to_warehouse,
                ];
                //erp2024 check transfer warehoues
                $this->db->select('id,stock_qty');
                $this->db->from('cberp_product_to_store');
                $this->db->where('product_id', $row1);
                $this->db->where('store_id', $to_warehouse);
                $checkquery = $this->db->get();
                $check_result = $checkquery->row_array();
                $chekedID = (!empty($check_result))?$check_result['id']:"0";
                if($chekedID>0){
                    $existingQty = $check_result['stock_qty'];
                    $current_stock = ($existingQty>0)? $existingQty+$transferqty :$transferqty;
                    $data['stock_qty'] = $current_stock;
                    $data['updated_by'] = $this->session->userdata('id');
                    $data['updated_dt'] = date('Y-m-d H:i:s');
                    $this->db->where('id', $chekedID);
                    $this->db->update('cberp_product_to_store', $data);
                }
                else{
                    $data['stock_qty'] = $transferqty;
                    $data['created_by'] = $this->session->userdata('id');
                    $data['created_dt'] = date('Y-m-d H:i:s');
                    $this->db->insert("cberp_product_to_store", $data);
                }

                //erp2024 check transfer warehoues
                $this->db->select('id,stock_qty');
                $this->db->from('cberp_product_to_store');
                $this->db->where('product_id', $row1);
                $this->db->where('store_id', $from_warehouse);
                $fromwh_query = $this->db->get();
                $fromwh_result = $fromwh_query->row_array();
                $fromwh_whID = $fromwh_result['id'];
                $fromwh_wh_qty = $fromwh_result['stock_qty'];
                $update_stock = ($fromwh_wh_qty>0)? $fromwh_wh_qty-$transferqty:0;
                $whfrom_data = [
                    "stock_qty" => $update_stock,
                    "updated_by" => $this->session->userdata('id'),
                    "updated_dt" => date('Y-m-d H:i:s'),
                ];
                $this->db->where('id', $fromwh_whID);
                $this->db->update('cberp_product_to_store', $whfrom_data);

                $stock_tranfer = [
                    "product_id" => $row1,
                    "transfer_qty" => $transferqty,
                    "warehouse_from" => $from_warehouse,
                    "warehouse_to" => $to_warehouse,
                    "transfered_by" => $this->session->userdata('id'),
                    "transfered_dt" => date('Y-m-d H:i:s'),
                ];
                $this->db->insert("stock_transfer_wh_to_wh", $stock_tranfer);

                //erp2024 operation in transfer from warehouse

                $j++;
                
            }
            $target = base_url() . "stocktransfer/";
            echo json_encode(array(
                'status' => 'Success',
                'message' => $this->lang->line('ADDED') . " <a href='$target' class='btn btn-info btn-sm'><span class='fa fa-eye' aria-hidden='true'></span>".$this->lang->line('Go to Stock Transfer List')." </a> &nbsp;"
            ));
        }
        else{
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR-RECORD')));
        }
         //erp2024 newly added function 13-06-2024 ends
    }

    public function fetchWarehouse($id){
        $this->db->select('emp_work_location');
        $this->db->from('cberp_employees');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $stock_result = $query->row_array();
        return($stock_result['emp_work_location']);
    }
    
}
