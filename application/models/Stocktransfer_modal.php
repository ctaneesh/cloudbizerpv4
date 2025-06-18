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

class Stocktransfer_modal extends CI_Model
{
    var $table = 'stock_transfer_wh_to_wh';
    var $column_order = array(null,'stock_transfer_wh_to_wh.id','cberp_products.product_code', 'cberp_products.product_name', 'cberp_store.title', 'stock_transfer_wh_to_wh.transfer_qty', 'stock_transfer_wh_to_wh.transfered_dt','transferemp.name','cberp_products.unit','recievedemp.name','stock_transfer_wh_to_wh.requested_qty','stock_transfer_wh_to_wh.intransit_qty', null); //set column field database for datatable orderable

    var $column_search = array('stock_transfer_wh_to_wh.id','cberp_products.product_code', 'cberp_products.product_name', 'frmwarehouse.title', 'towarehouse.title', 'stock_transfer_wh_to_wh.transfer_qty', 'stock_transfer_wh_to_wh.transfered_dt','transferemp.name','cberp_products.unit','recievedemp.name','stock_transfer_wh_to_wh.requested_qty','stock_transfer_wh_to_wh.intransit_qty'); //set column field database for datatable searchable
    var $order = array('stock_transfer_wh_to_wh.id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id = '', $w = '', $sub = '')
    {
       

        $this->db->select('stock_transfer_wh_to_wh.id,cberp_products.product_code, cberp_products.product_name, cberp_products.unit, stock_transfer_wh_to_wh.transfer_qty, stock_transfer_wh_to_wh.transfered_dt, frmwarehouse.title AS fromwarehouse, towarehouse.title AS towarehouse, transferemp.name AS transferemployee, recievedemp.name AS recievedemployee,stock_transfer_wh_to_wh.received_dt,stock_transfer_wh_to_wh.status,stock_transfer_wh_to_wh.requested_qty,stock_transfer_wh_to_wh.intransit_qty');
        $this->db->from('stock_transfer_wh_to_wh');
        $this->db->join('cberp_products', 'cberp_products.pid = stock_transfer_wh_to_wh.product_id');
        $this->db->join('cberp_employees AS transferemp', 'transferemp.id = stock_transfer_wh_to_wh.transfered_by', 'left');
        $this->db->join('cberp_employees AS recievedemp', 'recievedemp.id = stock_transfer_wh_to_wh.received_by', 'left');
        $this->db->join('cberp_store AS frmwarehouse', 'frmwarehouse.id = stock_transfer_wh_to_wh.warehouse_from');
        $this->db->join('cberp_store AS towarehouse', 'towarehouse.id = stock_transfer_wh_to_wh.warehouse_to');
        $i = 0;

        if ($this->input->post('start_date') && $this->input->post('end_date'))
        {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(stock_transfer_wh_to_wh.transfered_dt) BETWEEN '$start_date' AND '$end_date'");
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
        // $this->db->join('cberp_store', 'cberp_store.id = material_request.warehouse');
        // if ($this->aauth->get_user()->loc) {

        //     $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('cberp_store.loc', 0);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_store.loc', 0);
        // }
        return $this->db->count_all_results();
    }

    //erp2024 new functions 07-06-2024 ends
    //erp2024 new functions 19-06-2024 starts
    public function receive_data_details($selectedIds) {
        
        $this->db->select('stock_transfer_wh_to_wh.transfer_qty, 
                   stock_transfer_wh_to_wh.id,
                   stock_transfer_wh_to_wh.intransit_qty,
                   stock_transfer_wh_to_wh.requested_qty,
                   cberp_products.product_name,
                   cberp_products.unit, 
                   cberp_products.product_code, 
                   cberp_products.pid as productid, 
                   warehouse_from_warehouse.title AS warehouse_from_title, 
                   warehouse_from_warehouse.id AS warehouse_from_id,
                   warehouse_to_warehouse.title AS warehouse_to_title,
                   warehouse_to_warehouse.id AS warehouse_to_id');
        $this->db->from('stock_transfer_wh_to_wh');
        $this->db->join('cberp_store AS warehouse_from_warehouse', 'warehouse_from_warehouse.id = stock_transfer_wh_to_wh.warehouse_from');
        $this->db->join('cberp_store AS warehouse_to_warehouse', 'warehouse_to_warehouse.id = stock_transfer_wh_to_wh.warehouse_to');
        $this->db->join('cberp_products', 'cberp_products.pid = stock_transfer_wh_to_wh.product_id');
        $this->db->where_in('stock_transfer_wh_to_wh.id', $selectedIds);
        $query = $this->db->get();
        // echo ($this->db->last_query()); die();
        return $query->result_array();
    }
    public function item_recieve_submit($products_l, $to_warehouse1, $qty, $requested_ids)
    {
        // $this->session->unset_userdata('requestids');
        $qtyArray = $qty;
        // $qtyArray = explode(',', $qty);
        $i = 0;  // Initialize the index for qtyArray
        $j=0;
        $flag=1;
       
        foreach ($products_l as $row1) {
            $transferqty = 0;
            if (array_key_exists($j, $qtyArray)) {
                $transferqty = $qtyArray[$j];  
            } 
            if (array_key_exists($j, $qtyArray)) {
                $to_warehouse = $to_warehouse1[$j];  
            }              
            if (array_key_exists($j, $requested_ids)) {
                $material_reqid = $requested_ids[$j];  
            } 

            //erp2024 check transfer warehoues
            $this->db->select('id,stock_qty,intransit_qty');
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
                $data['intransit_qty'] = 0;
                $data['updated_by'] = $this->session->userdata('id');
                $data['updated_dt'] = date('Y-m-d H:i:s');
                $this->db->where('id', $chekedID);
                $this->db->update('cberp_product_to_store', $data);
            }
            
            //erp2024 newly stock moved to intransist 19-06-2024 ends

            $j++;
            
        }
        $receivestatus =  [
            "received_by" => $this->session->userdata('id'),
            "received_dt" => date('Y-m-d H:i:s'),  
            'status'    => "Receieved"
        ];
        $this->db->where_in('id', $requested_ids);
        $this->db->update('stock_transfer_wh_to_wh',$receivestatus);
        $target = base_url() . "stocktransfer/";
        echo json_encode(array(
            'status' => 'Success',
            'message' => $this->lang->line('ADDED') . " <a href='$target' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span></a> "
        ));
        
         //erp2024 newly added function 13-06-2024 ends


    }

    public function to_warehousedetails($id){
        $this->db->select('cberp_store.*');
        $this->db->from('stock_transfer_wh_to_wh');
        $this->db->join('cberp_store', 'cberp_store.id = stock_transfer_wh_to_wh.warehouse_to');
        $this->db->where('stock_transfer_wh_to_wh.material_reqid', $id);
        $this->db->group_by('stock_transfer_wh_to_wh.warehouse_to');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function from_warehousedetails($id){
        $this->db->select('cberp_store.*');
        $this->db->from('stock_transfer_wh_to_wh');
        $this->db->join('cberp_store', 'cberp_store.id = stock_transfer_wh_to_wh.warehouse_from');
        $this->db->where('stock_transfer_wh_to_wh.material_reqid', $id);
        $this->db->group_by('stock_transfer_wh_to_wh.warehouse_from');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function print_products_details($selectedIds) {        
        $this->db->select('stock_transfer_wh_to_wh.transfer_qty, 
                   stock_transfer_wh_to_wh.id,
                   stock_transfer_wh_to_wh.intransit_qty,
                   stock_transfer_wh_to_wh.requested_qty,
                   cberp_products.product_name,
                   cberp_products.unit, 
                   cberp_products.product_code, 
                   cberp_products.pid as productid');
        $this->db->from('stock_transfer_wh_to_wh');
        $this->db->join('cberp_products', 'cberp_products.pid = stock_transfer_wh_to_wh.product_id');
        $this->db->where_in('stock_transfer_wh_to_wh.material_reqid', $selectedIds);
        $query = $this->db->get();
        // echo ($this->db->last_query()); die();
        return $query->result_array();
    }

    public function to_warehousedetails_byid($id){
        $this->db->select('cberp_store.*');
        $this->db->from('stock_transfer_wh_to_wh');
        $this->db->join('cberp_store', 'cberp_store.id = stock_transfer_wh_to_wh.warehouse_to');
        $this->db->where('stock_transfer_wh_to_wh.id', $id);
        $this->db->group_by('stock_transfer_wh_to_wh.warehouse_to');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function from_warehousedetails_byid($id){
        $this->db->select('cberp_store.*');
        $this->db->from('stock_transfer_wh_to_wh');
        $this->db->join('cberp_store', 'cberp_store.id = stock_transfer_wh_to_wh.warehouse_from');
        $this->db->where('stock_transfer_wh_to_wh.id', $id);
        $this->db->group_by('stock_transfer_wh_to_wh.warehouse_from');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function print_products_details_byid($id) {        
        $this->db->select('stock_transfer_wh_to_wh.transfer_qty, 
                   stock_transfer_wh_to_wh.id,
                   stock_transfer_wh_to_wh.intransit_qty,
                   stock_transfer_wh_to_wh.requested_qty,
                   cberp_products.product_name,
                   cberp_products.unit, 
                   cberp_products.product_code, 
                   cberp_products.pid as productid');
        $this->db->from('stock_transfer_wh_to_wh');
        $this->db->join('cberp_products', 'cberp_products.pid = stock_transfer_wh_to_wh.product_id');
        $this->db->where_in('stock_transfer_wh_to_wh.id', $id);
        $query = $this->db->get();
        // echo ($this->db->last_query()); die();
        return $query->result_array();
    }
}
