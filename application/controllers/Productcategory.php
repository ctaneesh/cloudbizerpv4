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

class Productcategory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model', 'products_category');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->li_a = 'stock';
    }

    public function index()
    {
        $data['permissions'] = load_permissions('Stock','Products','Product Categories','List');
        $data['cat'] = $this->products_category->category_stock();
        $head['title'] = "Product Categories";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/category', $data);
        $this->load->view('fixed/footer');
    }

    public function warehouse()
    {
        $data['permissions'] = load_permissions('Stock','Warehouses','Manage Warehouses');
        $data['cat'] = $this->products_category->warehouse();
        // echo "<pre>"; print_r($data['cat']); die();
        $head['title'] = "Product Warehouse";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/warehouse', $data);
        $this->load->view('fixed/footer');
    }


    public function view()
    {
        $data['id'] = $this->input->get('id');
        $data['sub'] = $this->input->get('sub');
        $data['cat'] = $this->products_category->category_sub_stock($data['id']);
        $head['title'] = "View Product Category";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/category_view', $data);
        $this->load->view('fixed/footer');
    }

    public function viewwarehouse()
    {
        $data['cat'] = $this->products_category->warehouse();
        $head['title'] = "View Product Warehouses";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/warehouse_view', $data);
        $this->load->view('fixed/footer');
    }

    public function add()
    {
        $data['permissions'] = load_permissions('Stock','Products','Product Categories','List','Add New Category');
        // $data['cat'] = $this->products_category->category_list();
        // $this->load->model('locations_model');
        $data['stores'] = warehouse_list_with_type();
        $data['categories'] = product_category_list();
        $head['title'] = "Add Product Category";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/category_add', $data);
        $this->load->view('fixed/footer');
    }

    public function add_sub()
    {
        $data['permissions'] = load_permissions('Stock','Products','Product Categories','List','Add New - Sub Category');
        $data['cat'] = $this->products_category->category_list();
        $this->load->model('locations_model');
        $data['locations'] = $this->locations_model->locations_list2();
        $head['title'] = "Add Product Category";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/category_add_sub', $data);
        $this->load->view('fixed/footer');
    }

    public function addwarehouse()
    {
        $data['countries'] = country_list();
        $data['currencies'] = currency_list();
        if ($this->input->post()) {
            $data1 = [
                'store_name' => $this->input->post('store_name'),
                'store_owner' => $this->input->post('store_owner'),
                'store_address' => $this->input->post('store_address'),
                'store_address2' => $this->input->post('store_address2'),
                'store_email' => $this->input->post('store_email'),
                'store_phone' => $this->input->post('store_phone'),
                'store_phone2' => $this->input->post('store_phone2'),
                'store_fax' => $this->input->post('store_fax'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'country_id' => $this->input->post('country_id'),
                'warehouse_type' => $this->input->post('warehouse_type'),
                'currency_id' => $this->input->post('currency_id'),
            ];
            $this->db->insert('cberp_store',$data1);
        } 
        else {
            $head['title'] = "Add Product Warehouse";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/warehouse_add', $data);
            $this->load->view('fixed/footer');
        }
    }

    public function addcat()
    {
        $name = $this->input->post('product_catname', true);
        $description = $this->input->post('description', true);
        $language_id = $this->input->post('language_id', true);
        $arabic_name = $this->input->post('product_catname1', true);
        $arabic_description = $this->input->post('description1', true);
        $arabic_language_id = $this->input->post('language_id1', true);
        $store_ids = $this->input->post('store_id', true);
        $parent_id = $this->input->post('parent_id', true);
        $parent_id = empty($parent_id) ? 0 : $parent_id;
        $this->db->insert('cberp_product_category',['date_added'=> date('Y-m-d H:i:s'),'parent_id'=>$parent_id]);
        $category_id = $this->db->insert_id();

        $this->db->insert('cberp_product_category_description',['category_id'=>$category_id,'language_id'=>$language_id, 'name'=> $name,'description'=>$description]);
        if($arabic_name)
        {
            $this->db->insert('cberp_product_category_description',['category_id'=>$category_id,'language_id'=>$arabic_language_id, 'name'=> $arabic_name,'description'=>$arabic_description]);
        }
        if($store_ids)
        {
            foreach($store_ids as $store_id)
            {
                $this->db->insert('cberp_category_to_store',['category_id'=>$category_id,'store_id'=>$store_id]);
            }
            
        }
        echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED')));
    }


    public function delete_i()
    {
       // if ($this->aauth->premission(11)) {
            $id = intval($this->input->post('deleteid'));
            if ($id) {

                $query = $this->db->query("DELETE cberp_movers FROM cberp_movers LEFT JOIN cberp_products ON  cberp_movers.rid1=cberp_products.pid LEFT JOIN cberp_product_category ON  cberp_products.pcat=cberp_product_category.id WHERE cberp_product_category.id='$id' AND  cberp_movers.d_type='1'");

                $this->db->delete('cberp_products', array('pcat' => $id));
                $this->db->delete('cberp_product_category', array('id' => $id));
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Product Category with products')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        // } else {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         $this->lang->line('ERROR')));
        // }
    }

    public function delete_i_sub()
    {
       // if ($this->aauth->premission(11)) {
            $id = intval($this->input->post('deleteid'));
            if ($id) {

                $query = $this->db->query("DELETE cberp_movers FROM cberp_movers LEFT JOIN cberp_products ON  cberp_movers.rid1=cberp_products.pid LEFT JOIN cberp_product_category ON  cberp_products.sub_id=cberp_product_category.id WHERE cberp_product_category.id='$id' AND  cberp_movers.d_type='1'");

                $this->db->delete('cberp_products', array('sub_id' => $id));
                $this->db->delete('cberp_product_category', array('id' => $id));
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Product Category with products')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        // } else {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         $this->lang->line('ERROR')));
        // }

    }

    public function delete_warehouse()
    {
            $id = $this->input->post('deleteid');
            if ($id) {
                $this->db->delete('cberp_products', array('warehouse' => $id));
                $this->db->delete('cberp_store', array('id' => $id));
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Product Warehouse with products')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }

    }

//view for edit
    public function edit()
    {
        $catid = $this->input->get('id');
        $this->db->select('*');
        $this->db->from('cberp_product_category');
        $this->db->where('id', $catid);
        $query = $this->db->get();
        // $data['productcat'] = $query->row_array();
        $data['category'] = $this->products_category->category_list_by_id($catid);
        $data['categorystore'] = $this->products_category->category_warehouse_list($catid);
        // print_r($data['categorystore']); die();
        $data['stores'] = warehouse_list_with_type();
        $head['title'] = "Edit Product Category";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/product-cat-edit', $data);
        $this->load->view('fixed/footer');

    }

    public function editwarehouse()
    {
        //         ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        if ($this->input->post()) {
            $cid = $this->input->post('catid');
            $cat_name = $this->input->post('product_cat_name', true);
            $cat_desc = $this->input->post('product_cat_desc', true);
            $lid = $this->input->post('lid');
            $warehouse_type = $this->input->post('warehouse_type');
            if ($this->aauth->get_user()->loc) {
                if ($lid == 0 or $this->aauth->get_user()->loc == $lid) {

                } else {
                    exit();
                }
            }


            if ($cat_name) {
                if($warehouse_type=='Main')
                {
                    $this->db->update('cberp_store', ['warehouse_type' => 'Normal']);
                }                
                $this->products_category->editwarehouse($cid, $cat_name, $cat_desc, $lid, $warehouse_type);
            }
        } else {
            $catid = $this->input->get('id');
            $this->db->select('*');
            $this->db->from('cberp_store');
            $this->db->where('store_id', $catid);
            $query = $this->db->get();
            $data['warehouse'] = $query->row_array();
            $this->load->model('locations_model');
            $data['locations'] = $this->locations_model->locations_list2();
            $head['title'] = "Edit Product Warehouse";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/product-warehouse-edit', $data);
            $this->load->view('fixed/footer');
        }

    }

    public function editcat()
    {
        $cid = $this->input->post('catid');
        $product_cat_name = $this->input->post('product_cat_name');
        $product_cat_desc = $this->input->post('product_cat_desc');
        $cat_type = $this->input->post('cat_type', true);
        $cat_rel = $this->input->post('cat_rel', true);
        $old_cat_type = $this->input->post('old_cat_type', true);
        if ($cid) {
            $this->products_category->edit($cid, $product_cat_name, $product_cat_desc, $cat_type, $cat_rel, $old_cat_type);
        }

        $name = $this->input->post('product_catname', true);
        $description = $this->input->post('description', true);
        $language_id = $this->input->post('language_id', true);
        $arabic_name = $this->input->post('product_catname1', true);
        $arabic_description = $this->input->post('description1', true);
        $arabic_language_id = $this->input->post('language_id1', true);
        $store_ids = $this->input->post('store_id', true);
        $parent_id = $this->input->post('parent_id', true);
        $parent_id = empty($parent_id) ? 0 : $parent_id;
        $category_id = $this->input->post('category_id', true);
        $this->db->update('cberp_product_category',['date_modified'=> date('Y-m-d H:i:s'),'parent_id'=>$parent_id],['category_id'=>$category_id]);

        $this->db->update('cberp_product_category_description',['category_id'=>$category_id,'language_id'=>$language_id, 'name'=> $name,'description'=>$description],['category_id'=>$category_id,'language_id'=>$language_id]);
        if($arabic_name)
        {
            $this->db->update('cberp_product_category_description',['category_id'=>$category_id,'language_id'=>$arabic_language_id, 'name'=> $arabic_name,'description'=>$arabic_description],['category_id'=>$category_id,'language_id'=>$arabic_language_id]);
        }
        if($store_ids)
        {
            $this->db->delete('cberp_category_to_store',['category_id'=>$category_id]);
            foreach($store_ids as $store_id)
            {
                $this->db->insert('cberp_category_to_store',['category_id'=>$category_id,'store_id'=>$store_id]);
            }
            
        }
         echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED')));
    }


    public function report_product()
    {
        $pid = intval($this->input->post('id'));

        $r_type = intval($this->input->post('r_type'));
        $s_date = datefordatabase($this->input->post('s_date'));
        $e_date = datefordatabase($this->input->post('e_date'));
        $sub_date = $this->input->post('sub');
        $filter = 'pcat';
        if ($sub_date) $filter = 'sub_id';

        if ($pid && $r_type) {
            $qj = '';
            $wr = '';
            // if ($this->aauth->get_user()->loc) {
            //     $qj = "LEFT JOIN cberp_store ON cberp_products.warehouse=cberp_store.id";

            //     $wr = " AND cberp_store.loc='" . $this->aauth->get_user()->loc . "'";
            // }


            switch ($r_type) {
                case 1 :
                    $query = $this->db->query("SELECT cberp_invoices.tid,cberp_invoice_items.qty,cberp_invoice_items.price,cberp_invoices.invoicedate FROM cberp_invoice_items LEFT JOIN cberp_invoices ON cberp_invoices.id=cberp_invoice_items.tid LEFT JOIN cberp_products ON cberp_products.pid=cberp_invoice_items.pid  LEFT JOIN cberp_product_category ON cberp_product_category.id=cberp_products.$filter  $qj WHERE cberp_invoices.status!='canceled' AND (DATE(cberp_invoices.invoicedate) BETWEEN DATE('$s_date') AND DATE('$e_date')) AND cberp_products.$filter='$pid' $wr");
                    $result = $query->result_array();
                    break;

                case 2 :
                    $query = $this->db->query("SELECT cberp_purchase_orders.tid,cberp_purchase_order_items.qty,cberp_purchase_order_items.price,cberp_purchase_orders.invoicedate FROM cberp_purchase_order_items LEFT JOIN cberp_purchase_orders ON cberp_purchase_orders.id=cberp_purchase_order_items.tid LEFT JOIN cberp_products ON cberp_products.pid=cberp_purchase_order_items.pid  LEFT JOIN cberp_product_category ON cberp_product_category.id=cberp_products.$filter  WHERE cberp_purchase_orders.status!='canceled' AND (DATE(cberp_purchase_orders.invoicedate) BETWEEN DATE('$s_date') AND DATE('$e_date')) AND cberp_products.$filter='$pid' ");
                    $result = $query->result_array();
                    break;

                case 3 :
                    $query = $this->db->query("SELECT cberp_movers.rid2 AS qty, DATE(cberp_movers.d_time) AS  invoicedate,cberp_movers.note,cberp_products.product_price AS price,cberp_products.product_name   FROM cberp_movers LEFT JOIN cberp_products ON cberp_products.pid=cberp_movers.rid1  WHERE cberp_movers.d_type='1' AND cberp_products.$filter='$pid'  AND (DATE(cberp_movers.d_time) BETWEEN DATE('$s_date') AND DATE('$e_date'))");
                    $result = $query->result_array();
                    break;
            }
            $this->db->select('*');
            $this->db->from('cberp_product_category');
            $this->db->where('id', $pid);
            $query = $this->db->get();
            $product = $query->row_array();

            $html = $this->load->view('products/cat_statementpdf-ltr', array('report' => $result, 'product' => $product, 'r_type' => $r_type), true);
            ini_set('memory_limit', '64M');

            //PDF Rendering
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($pid . 'report.pdf', 'I');
        } else {
            $pid = intval($this->input->get('id'));
            $sub = $this->input->get('sub');
            $this->db->select('*');
            $this->db->from('cberp_product_category');
            $this->db->where('id', $pid);
            $query = $this->db->get();
            $product = $query->row_array();

            $head['title'] = "Product Sales";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/cat_statement', array('id' => $pid, 'product' => $product, 'sub' => $sub));
            $this->load->view('fixed/footer');
        }
    }

    public function warehouse_report()
    {
        $pid = intval($this->input->post('id'));

        $r_type = intval($this->input->post('r_type'));
        $s_date = datefordatabase($this->input->post('s_date'));
        $e_date = datefordatabase($this->input->post('e_date'));

        if ($pid && $r_type) {
            $qj = '';
            $wr = '';
            // if ($this->aauth->get_user()->loc) {
            //     $qj = "LEFT JOIN cberp_store ON cberp_products.warehouse=cberp_store.id";

            //     $wr = " AND cberp_store.loc='" . $this->aauth->get_user()->loc . "'";
            // }

            switch ($r_type) {
                case 1 :
                    $query = $this->db->query("SELECT cberp_invoices.tid,cberp_invoice_items.qty,cberp_invoice_items.price,cberp_invoices.invoicedate FROM cberp_invoice_items LEFT JOIN cberp_invoices ON cberp_invoices.id=cberp_invoice_items.tid LEFT JOIN cberp_products ON cberp_products.pid=cberp_invoice_items.pid $qj WHERE cberp_invoices.status!='canceled'  AND (DATE(cberp_invoices.invoicedate) BETWEEN DATE('$s_date') AND DATE('$e_date')) AND cberp_products.warehouse='$pid' $wr");
                    $result = $query->result_array();
                    break;

                case 2 :
                    $query = $this->db->query("SELECT cberp_purchase_orders.tid,cberp_purchase_order_items.qty,cberp_purchase_order_items.price,cberp_purchase_orders.invoicedate FROM cberp_purchase_order_items LEFT JOIN cberp_purchase_orders ON cberp_purchase_orders.id=cberp_purchase_order_items.tid LEFT JOIN cberp_products ON cberp_products.pid=cberp_purchase_order_items.pid  LEFT JOIN cberp_product_category ON cberp_product_category.id=cberp_products.pcat  WHERE cberp_purchase_orders.status!='canceled' AND (DATE(cberp_purchase_orders.invoicedate) BETWEEN DATE('$s_date') AND DATE('$e_date')) AND cberp_products.pcat='$pid' ");
                    $result = $query->result_array();
                    break;

                case 3 :
                    $query = $this->db->query("SELECT cberp_movers.rid2 AS qty, DATE(cberp_movers.d_time) AS  invoicedate,cberp_movers.note,cberp_products.product_price AS price,cberp_products.product_name  FROM cberp_movers LEFT JOIN cberp_products ON cberp_products.pid=cberp_movers.rid1  WHERE cberp_movers.d_type='1' AND cberp_products.warehouse='$pid'  AND (DATE(cberp_movers.d_time) BETWEEN DATE('$s_date') AND DATE('$e_date'))");
                    $result = $query->result_array();
                    break;
            }


            $this->db->select('*');
            $this->db->from('cberp_store');
            $this->db->where('id', $pid);
            $query = $this->db->get();
            $product = $query->row_array();

            $html = $this->load->view('products/ware_statementpdf-ltr', array('report' => $result, 'product' => $product, 'r_type' => $r_type), true);
            ini_set('memory_limit', '64M');


            //PDF Rendering
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($pid . 'report.pdf', 'I');
        } else {
            $pid = intval($this->input->get('id'));
            $this->db->select('*');
            $this->db->from('cberp_store');
            $this->db->where('id', $pid);
            $query = $this->db->get();
            $product = $query->row_array();

            $head['title'] = "Product Sales";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/ware_statement', array('id' => $pid, 'product' => $product));
            $this->load->view('fixed/footer');
        }
    }

    //erp2024 18-10-2024 starts
    public function check_deafult_warehouse_found()
    {
        $result = $this->products_category->check_deafult_warehouse_found();
        echo json_encode(array('status' => 'success', 'data'=> $result));
    }
    public function check_deafult_warehouse_found_without_me()
    { 
        $result=0;
        if($this->input->post('warehouse_type')=='Main')
        {            
            $result = $this->products_category->check_deafult_warehouse_found_without_me($this->input->post('store_id'));
        }
        echo json_encode(array('status' => 'success', 'data'=> $result));
    }

    //erp2024 18-10-2024 ends

public function get_category_tree()
{
    $sql = "
        SELECT 
            c.category_id,
            c.parent_id,
            d.name AS category_name,
            c.status
        FROM cberp_product_category c
        JOIN cberp_product_category_description d 
            ON c.category_id = d.category_id
        WHERE d.language_id = 1 AND c.status = 1
    ";

    $query = $this->db->query($sql);
    $categories = $query->result_array();

    // Use associative array to avoid duplicates
    $tree = [];
    $added = [];

    foreach ($categories as $cat) {
        $cat_id = $cat['category_id'];

        if (!isset($added[$cat_id])) {
            $tree[] = [
                'id' => $cat_id,
                'parent' => $cat['parent_id'] == 0 ? '#' : $cat['parent_id'],
                'text' => $cat['category_name'],
            ];
            $added[$cat_id] = true;
        }
    }

    echo json_encode($tree);
}



}
