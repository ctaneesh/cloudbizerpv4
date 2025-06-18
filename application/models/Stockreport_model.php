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

class Stockreport_model extends CI_Model
{
    var $table = 'cberp_products';
    var $column_order = array(null, 'cberp_products.pid', 'cberp_products.product_name', 'cberp_products.product_code', 'cberp_products.product_price', 'cberp_products.onhand_quantity','cberp_products.product_cost','cberp_product_category.title', null);
    var $column_search = array('cberp_products.pid', 'cberp_products.product_name', 'cberp_products.product_code', 'cberp_products.product_price','cberp_products.onhand_quantity','cberp_products.product_cost','cberp_product_category.title');
    var $order = array('cberp_products.pid' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    public function warehouses()
    {
        $this->db->select('*');
        $this->db->from('cberp_store');  
        $this->db->order_by('title', 'ASC');     
        $query = $this->db->get();
        return $query->result_array();

    }
    public function categories()
    {
        $this->db->select('id,title');
        $this->db->from('cberp_product_category');  
        $this->db->order_by('title', 'ASC');     
        $query = $this->db->get();
        return $query->result_array();

    }



    private function _get_datatables_query($eid)
    {

       
        if ($this->input->post('warehouse'))
        {
            $this->db->select('cberp_products.pid,cberp_products.product_name,cberp_products.product_code,cberp_products.product_price,cberp_product_to_store.stock_qty as qty,cberp_products.product_cost,cberp_product_category.title');
            $this->db->from('cberp_products');
    
            $this->db->join('cberp_product_to_store', 'cberp_product_to_store.product_id=cberp_products.pid');
            $this->db->where("cberp_product_to_store.store_id",$this->input->post('warehouse'));
        }
        else{
            $this->db->select('cberp_products.pid,cberp_products.product_name,cberp_products.product_code,cberp_products.product_price,cberp_products.onhand_quantity as qty,cberp_products.product_cost,cberp_product_category.title');
            $this->db->from('cberp_products');
        }

        if($this->input->post('category'))
        {
            $this->db->where_in("cberp_products.pcat",$this->input->post('category'));
        }

        $this->db->join('cberp_product_category', 'cberp_product_category.id=cberp_products.pcat');

        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        else{
            $order = array('cberp_products.pid' => 'desc');
        }
    }

    function get_datatables($eid)
    {

        $this->_get_datatables_query($eid);
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);        }  
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }
    function get_all_data($eid)
    {

        $this->_get_datatables_query($eid);
      
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered($eid)
    {
        $this->_get_datatables_query($eid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($eid)
    {
        // $this->db->select('cberp_products.pid');
        $this->db->from($this->table);

        //  if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }

        return $this->db->count_all_results();
    }


    public function currencies()
    {

        $this->db->select('*');
        $this->db->from('cberp_currencies');

        $query = $this->db->get();
        return $query->result_array();

    }

    public function currency_d($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_currencies');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function stock_list_pdf($warehouse="",$category="")
    {
        if (!empty($category))
        {
            $this->db->select('cberp_products.pid,cberp_products.product_name,cberp_products.product_code,cberp_products.product_price,cberp_product_to_store.stock_qty as qty,cberp_products.product_cost');
            $this->db->from('cberp_products');
    
            $this->db->join('cberp_product_to_store', 'cberp_product_to_store.product_id=cberp_products.pid');
            $this->db->where("cberp_product_to_store.store_id",$warehouse);
        }
        else{
            $this->db->select('cberp_products.pid,cberp_products.product_name,cberp_products.product_code,cberp_products.product_price,cberp_products.onhand_quantity as qty,cberp_products.product_cost');
            $this->db->from('cberp_products');
        }

        if(!empty($category))
        {
            $this->db->where_in("cberp_products.pcat",$category);
        }
        $this->db->limit(1000);
        $query = $this->db->get();
        return $query->result_array();
    }

}
