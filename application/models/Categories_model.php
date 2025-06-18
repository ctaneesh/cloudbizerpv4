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

class Categories_model extends CI_Model
{

    public function category_list($type = 0, $rel = 0)
    {
        // $query = $this->db->query("SELECT id,title
        // FROM cberp_product_category WHERE c_type='$type' AND rel_id='$rel'

        // ORDER BY id DESC");

        $this->db->select('cberp_product_category_description.*,cberp_product_category_description.name AS title,cberp_product_category.category_id as id ');
        $this->db->from('cberp_product_category');
        $this->db->join(
            'cberp_product_category_description',
            'cberp_product_category_description.category_id = cberp_product_category.category_id'
        );
        $this->db->where('cberp_product_category_description.language_id', 1);
        $this->db->order_by('cberp_product_category.category_id','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_category_tree()
    {
        $sql = "
            WITH RECURSIVE category_path AS (
                SELECT 
                    cberp_product_category.category_id,
                    cberp_product_category.parent_id,
                    cberp_product_category_description.name AS name,
                    cberp_product_category_description.name AS full_path,
                    cberp_product_category.status
                FROM cberp_product_category
                JOIN cberp_product_category_description 
                    ON cberp_product_category.category_id = cberp_product_category_description.category_id
                WHERE cberp_product_category.parent_id = 0 
                AND cberp_product_category_description.language_id = 1

                UNION ALL

                SELECT 
                    cberp_product_category.category_id as id,
                    cberp_product_category.parent_id,
                    cberp_product_category_description.name AS title,
                    CONCAT(category_path.full_path, ' > ', cberp_product_category_description.name),
                    cberp_product_category.status
                FROM cberp_product_category
                JOIN cberp_product_category_description 
                    ON cberp_product_category.category_id = cberp_product_category_description.category_id
                JOIN category_path 
                    ON cberp_product_category.parent_id = category_path.category_id
                WHERE cberp_product_category_description.language_id = 1
            )
            SELECT * FROM category_path
            WHERE status = 1
            ORDER BY full_path
        ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function category_list_by_id($category_id)
    {
        // $query = $this->db->query("SELECT id,title
        // FROM cberp_product_category WHERE c_type='$type' AND rel_id='$rel'

        // ORDER BY id DESC");

        $this->db->select('cberp_product_category_description.*,cberp_product_category_description.name AS title,cberp_product_category.category_id as id,cberp_product_category.parent_id ');
        $this->db->from('cberp_product_category');
        $this->db->join(
            'cberp_product_category_description',
            'cberp_product_category_description.category_id = cberp_product_category.category_id'
        );
        $this->db->group_start();
        $this->db->where('cberp_product_category_description.language_id', 1);
        $this->db->or_where('cberp_product_category_description.language_id', 2);
        $this->db->group_end();

        $this->db->where('cberp_product_category_description.category_id', $category_id);
        $this->db->order_by('cberp_product_category.category_id','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function warehouse_list()
    {
        $where = '';
        $query = $this->db->query("SELECT store_id,store_name
        FROM cberp_store $where 
        ORDER BY store_id DESC");
        return $query->result_array();
    }



    public function category_stock()
    {
        $whr = '';
  
        // $query = $this->db->query("SELECT c.*,p.pc,p.salessum,p.worthsum,p.qty FROM cberp_product_category AS c LEFT JOIN ( SELECT cberp_products.pcat,COUNT(cberp_products.pid) AS pc,SUM(cberp_products.product_price*cberp_products.onhand_quantity) AS salessum, SUM(cberp_products.product_cost*cberp_products.onhand_quantity) AS worthsum,SUM(cberp_products.onhand_quantity) AS qty FROM cberp_products LEFT JOIN cberp_store ON cberp_products.warehouse=cberp_store.id  $whr GROUP BY cberp_products.pcat ) AS p ON c.id=p.pcat WHERE c.c_type=0");
        $this->db->select('cberp_product_category_description.*');
        $this->db->from('cberp_product_category');
        $this->db->join(
            'cberp_product_category_description',
            'cberp_product_category_description.category_id = cberp_product_category.category_id'
        );
        $this->db->where('cberp_product_category_description.language_id', 1);
        $this->db->order_by('cberp_product_category.category_id','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function category_sub_stock($id = 0)
    {
        $whr = '';
        // if (!BDATA) $whr = "WHERE  (cberp_store.loc=0) ";
        // if ($this->aauth->get_user()->loc) {
        //     $whr = "WHERE  (cberp_store.loc=" . $this->aauth->get_user()->loc . " ) ";
        //     if (BDATA) $whr = "WHERE  (cberp_store.loc=" . $this->aauth->get_user()->loc . " OR cberp_store.loc=0) ";
        // }

        $whr2 = '';

        $query = $this->db->query("SELECT c.*,p.pc,p.salessum,p.worthsum,p.qty,p.sub_id FROM cberp_product_category AS c LEFT JOIN ( SELECT cberp_products.sub_id,COUNT(cberp_products.pid) AS pc,SUM(cberp_products.product_price*cberp_products.onhand_quantity) AS salessum, SUM(cberp_products.product_cost*cberp_products.onhand_quantity) AS worthsum,SUM(cberp_products.onhand_quantity) AS qty FROM cberp_products LEFT JOIN cberp_store ON cberp_products.warehouse=cberp_store.id  $whr GROUP BY cberp_products.sub_id ) AS p ON c.id=p.sub_id WHERE c.c_type=1 AND c.rel_id='$id'");
        return $query->result_array();
    }

    public function warehouse()
    {
        $where = '';
        $this->db->select('cberp_store.*, cberp_country.name, cberp_currencies.symbol');
        $this->db->from('cberp_store');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_store.country_id', 'left');
        $this->db->join('cberp_currencies', 'cberp_currencies.id = cberp_store.currency_id', 'left');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function cat_ware($id, $loc = 0)
    {
        $qj = '';
        if ($loc) $qj = "AND w.loc='$loc'";
        $query = $this->db->query("SELECT c.id AS cid, w.id AS wid,c.title AS catt,w.title AS watt FROM cberp_products AS p LEFT JOIN cberp_product_category AS c ON p.pcat=c.id LEFT JOIN cberp_store AS w ON p.warehouse=w.id WHERE
p.pid='$id' $qj ");
        return $query->row_array();
    }


    public function addnew($cat_name, $cat_desc, $cat_type = 0, $cat_rel = 0)
    {
        if (!$cat_type) $cat_type = 0;
        if (!$cat_rel) $cat_rel = 0;
        $data = array(
            'title' => $cat_name,
            'extra' => $cat_desc,
            'c_type' => $cat_type,
            'rel_id' => $cat_rel
        );

        if ($cat_type) {
            $url = "<a href='" . base_url('productcategory/add_sub') . "' class='btn btn-blue btn-lg'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('productcategory/view?id=' . $cat_rel) . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>";
        } else {
            $url = "<a href='" . base_url('productcategory/add') . "' class='btn btn-blue btn-lg'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('productcategory') . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>";
        }

        if ($this->db->insert('cberp_product_category', $data)) {
            $this->aauth->applog("[Category Created] $cat_name ID " . $this->db->insert_id(), $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED') . " $url"));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function addwarehouse($cat_name, $cat_desc, $lid, $warehouse_type)
    {
        $data = array(
            'title' => $cat_name,
            'extra' => $cat_desc,
            'loc' => $lid,
            'warehouse_type' => $warehouse_type
        );

        if ($this->db->insert('cberp_store', $data)) {
            $this->aauth->applog("[WareHouse Created] $cat_name ID " . $this->db->insert_id(), $this->aauth->get_user()->username);
               $url = "<a href='" . base_url('productcategory/addwarehouse') . "' class='btn btn-blue btn-lg'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('productcategory/warehouse') . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>";
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED') . $url));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function edit($catid, $product_cat_name, $product_cat_desc, $cat_type, $cat_rel, $old_cat_type)
    {
         if (!$cat_rel) $cat_rel = 0;
        $data = array(
            'title' => $product_cat_name,
            'extra' => $product_cat_desc,
            'c_type' => $cat_type,
            'rel_id' => $cat_rel
        );
        $this->db->set($data);
        $this->db->where('id', $catid);
        if ($this->db->update('cberp_product_category')) {
            if ($cat_type != $old_cat_type && $cat_type && $cat_type) {
                $data = array('pcat' => $cat_rel);
                $this->db->set($data);
                $this->db->where('sub_id', $catid);
                $this->db->update('cberp_products');
            }
            $this->aauth->applog("[Category Edited] $product_cat_name ID " . $catid, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function editwarehouse($catid, $product_cat_name, $product_cat_desc, $lid, $warehouse_type)
    {
        $data = array(
            'title' => $product_cat_name,
            'extra' => $product_cat_desc,
            'loc' => $lid,
            'warehouse_type' => $warehouse_type
        );


        $this->db->set($data);
        $this->db->where('id', $catid);

        if ($this->db->update('cberp_store')) {
            $this->aauth->applog("[Warehouse Edited] $product_cat_name ID " . $catid, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function sub_cat($id = 0)
    {
        $this->db->select('*');
        $this->db->from('cberp_product_category');
        $this->db->where('rel_id', $id);
        $this->db->where('c_type', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

       public function sub_cat_curr($id = 0)
    {
        $this->db->select('*');
        $this->db->from('cberp_product_category');
        $this->db->where('id', $id);
        $this->db->where('c_type', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function sub_cat_list_in($id = 0)
    {
        $this->db->select('*');
        $this->db->from('cberp_product_category');
        $this->db->where_in('rel_id', $id);
        $this->db->where('c_type', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function sub_cat_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('cberp_product_category');
        $this->db->where('rel_id', $id);
        $this->db->where('c_type', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function check_deafult_warehouse_found()
    {
        $this->db->select('id');
        $this->db->from('cberp_store');
        $this->db->where('cberp_store.warehouse_type', 'Main');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {           
            return(1);
        } else {
            return 0;
        }
    }
    public function check_deafult_warehouse_found_without_me($id)
    {
       

        $this->db->select('id');
        $this->db->from('cberp_store');
        $this->db->where('warehouse_type', 'Main');
        $this->db->where('id !=', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {           
            return(1);
        } else {
            return 0;
        }

    }

    public function warehouse_list_type()
    {
        $query = $this->db->query("SELECT store_id as id,store_name as title,warehouse_type
        FROM cberp_store 
        ORDER BY warehouse_type ASC");
        // echo $this->db->last_query(); die();
        return $query->result_array();
    }
    public function category_warehouse_list($category_id)
    {
        $this->db->select('cberp_category_to_store.store_id');
        $this->db->from('cberp_category_to_store');
        $this->db->join('cberp_product_category', 'cberp_product_category.category_id = cberp_category_to_store.category_id', 'inner');
        $this->db->where('cberp_category_to_store.category_id', $category_id);
        $query = $this->db->get();
        return $query->result_array();

    }
}
