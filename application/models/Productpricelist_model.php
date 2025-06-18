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

class Productpricelist_model extends CI_Model
{
    var $table = 'cberp_products';
    var $column_order = array(null, 'cberp_products.product_code','cberp_products.product_name','cberp_products.onhand_quantity','cberp_product_ai.min_price','cberp_product_ai.item_cost','cberp_product_ai.web_price','cberp_product_ai.wholesale_price','cberp_products.product_name', null);
    var $column_search = array('cberp_products.product_code','cberp_products.unit','cberp_products.onhand_quantity','cberp_product_ai.min_price','cberp_product_ai.item_cost','cberp_product_ai.wholesale_price','cberp_products.unit',);
    var $order = array('cberp_products.pid' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    public function view($id)
    {

        $this->db->from('cberp_product_min_price');
        $this->db->where('id', $id);

        $query = $this->db->get();
        $result = $query->row_array();
        return $result;


    }
    public function create($price_perc, $selling_price_perc, $whole_price_perc, $web_price_perc)
    {
        $data = array(
            'price_perc' => $price_perc,
            'selling_price_perc' => $selling_price_perc,
            'whole_price_perc' => $whole_price_perc,
            'web_price_perc' => $web_price_perc,
        );
        if ($this->db->insert('cberp_product_min_price', $data)) {
            echo json_encode(array('status' => 'Success', 'message' =>  $this->lang->line('ADDED') . ' <a href="' . base_url('productpricing') . '" class="btn btn-blue btn-sm"><span class="fa fa-eye" aria-hidden="true"></span> </a>' ));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }
    public function edit($id, $price_perc, $selling_price_perc, $whole_price_perc, $web_price_perc)
    {
        $data = array(
            'price_perc' => $price_perc,
            'selling_price_perc' => $selling_price_perc,
            'whole_price_perc' => $whole_price_perc,
            'web_price_perc' => $web_price_perc,
        );

        $this->db->set($data);
        $this->db->where('id', $id);

        if ($this->db->update('cberp_product_min_price')) {
            echo json_encode(array('status' => 'Success', 'message' =>  $this->lang->line('UPDATED') . ' <a href="' . base_url('productpricing') . '" class="btn btn-blue btn-sm"><span class="fa fa-eye" aria-hidden="true"></span> </a>' ));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }

        

    }

    private function _get_datatables_query()
    {
        
        $this->db->select('cberp_products.pid, cberp_products.product_price, cberp_products.product_code, cberp_products.product_name, cberp_products.onhand_quantity, cberp_product_ai.min_price, cberp_product_ai.item_cost, cberp_product_ai.web_price, cberp_product_ai.wholesale_price, cberp_products.unit');
        $this->db->select('COALESCE(purchase_summary.total_qty, 0) AS purchase_order', FALSE);
        $this->db->select('COALESCE(sales_summary.total_qty, 0) AS customer_order', FALSE);
        $this->db->from('cberp_products');
        $this->db->join('cberp_product_ai', 'cberp_product_ai.product_id = cberp_products.pid', 'left');
        $this->db->join(
            '(SELECT cberp_purchase_order_items.pid, SUM(cberp_purchase_order_items.qty) AS total_qty 
            FROM cberp_purchase_order_items 
            JOIN cberp_purchase_orders ON cberp_purchase_order_items.tid = cberp_purchase_orders.id 
            WHERE cberp_purchase_orders.status IN ("paid", "due", "canceled", "partial") 
            GROUP BY cberp_purchase_order_items.pid) AS purchase_summary', 
            'purchase_summary.pid = cberp_products.pid', 
            'left'
        );
        $this->db->join(
            '(SELECT cberp_sales_orders_items.pid, SUM(cberp_sales_orders_items.qty) AS total_qty 
            FROM cberp_sales_orders_items 
            JOIN cberp_sales_orders ON cberp_sales_orders.id = cberp_sales_orders_items.tid 
            WHERE cberp_sales_orders.status IN ("pending", "accepted", "rejected", "customer_approved") 
            GROUP BY cberp_sales_orders_items.pid) AS sales_summary', 
            'sales_summary.pid = cberp_products.pid', 
            'left'
        );

        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
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
    }

    function get_datatables($dategap="")
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();       
        // die($this->db->last_query());
        return $query->result_array();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
		// $this->db->where('cberp_product_min_price.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
		// $this->db->where('cberp_product_min_price.customer_id', $this->session->userdata('user_details')[0]->cid);
        return $this->db->count_all_results();
    }

     public function update_status($id)
    {
        $this->db->set('status', 'customer_approved');
                $this->db->where('id', $id);
               return $this->db->update('cberp_quotes');
    }

}
