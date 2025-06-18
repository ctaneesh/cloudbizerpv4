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

class Products_model extends CI_Model
{
    var $table = 'cberp_products';
    var $column_order = array(null,null, 'cberp_product_description.product_name','cberp_product_description.product_code', 'cberp_products.onhand_quantity','cberp_products.alert_quantity',   'cberp_products.product_price', 'cberp_products.date_avaialble','cberp_products.status', null); //set column field database for datatable orderable
    var $column_search = array('cberp_product_description.product_name', 'cberp_product_description.product_code','cberp_products.onhand_quantity','cberp_products.status'); //set column field database for datatable searchable
    var $order = array('cberp_products.created_date' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id = '', $w = '', $sub = '')
    {
        $filterflg=0;
        $filter_category = $this->input->post('filter_category');
        $filter_brand = $this->input->post('filter_brand');
        $filter_manufacturer = $this->input->post('filter_manufacturer');
        $filter_warehouse = $this->input->post('filter_warehouse');
        $filter_price_from = !empty($this->input->post('filter_price_from')) ? $this->input->post('filter_price_from') : 0;
        $filter_price_to = !empty($this->input->post('filter_price_to')) ? $this->input->post('filter_price_to'): 0;
        $filter_alerted_qty = !empty($this->input->post('filter_alerted_qty')) ? $this->input->post('filter_alerted_qty'): 0;

        $filter_expiry_date_from = !empty($this->input->post('filter_expiry_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_from'))) : "";
        $filter_expiry_date_to = !empty($this->input->post('filter_expiry_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_to'))) : "";

        $filter_available_date_from = !empty($this->input->post('filter_available_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_available_date_from'))) :"";
        $filter_available_date_to = !empty($this->input->post('filter_available_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_available_date_to'))) :"";


        // $this->db->select('cberp_products.*, cberp_product_category.title AS c_title,cberp_products.date_avaialble');
        // $this->db->join('cberp_product_to_category', 'cberp_product_to_category.product_code = cberp_products.product_code');    
        // $this->db->join('cberp_product_category', 'cberp_product_category.id = cberp_product_to_category.category_id');

         $this->db->select('cberp_products.*,cberp_products.product_code AS productcode, cberp_product_pricing.*, cberp_product_locations.*, cberp_product_description.*, cberp_product_barcode.*');
        $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code','left');
        $this->db->join('cberp_product_locations', 'cberp_product_locations.product_code = cberp_products.product_code','left');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code','left');
        $this->db->join('cberp_product_barcode', 'cberp_product_barcode.product_code = cberp_products.product_code', 'left');
        
       
        if($filter_category)
        {
            $filterflg = 1;
            $this->db->join('cberp_product_to_category', 'cberp_product_to_category.product_code = cberp_products.product_code');
            $this->db->where_in('cberp_product_to_category.category_id',$filter_category);
            // $this->db->order_by('cberp_product_category.title','ASC');
        }


        if($filter_price_to > 0){
            $this->db->where("cberp_products.product_price BETWEEN $filter_price_from AND $filter_price_to");
            // $this->db->where('cberp_products.product_price >=', $filter_price_from);
            // $this->db->where('cberp_products.product_price <=', $filter_price_to);
            (empty($filter_category)) ? $this->db->group_by('cberp_products.product_code') : "" ;
        }

        
        if(!empty($filter_expiry_date_to) && !empty($filter_expiry_date_from)){
            $this->db->where("cberp_products.expiry BETWEEN '$filter_expiry_date_from' AND '$filter_expiry_date_to'");
            // (empty($filter_category)) ? $this->db->group_by('cberp_products.product_code') : "" ;
        }

        
        if(!empty($filter_available_date_from) && !empty($filter_available_date_to)){
            $this->db->where("cberp_products.date_avaialble BETWEEN '$filter_available_date_from' AND '$filter_available_date_to'");
            // (empty($filter_category)) ? $this->db->group_by('cberp_products.product_code') : "" ;
        }

        if($filter_alerted_qty)
        {
            $filterflg = 1;
            $this->db->where('cberp_products.alert_quantity >= cberp_products.onhand_quantity');
            // (empty($filter_category)) ? $this->db->group_by('cberp_products.product_code') : "" ;
        }

        // if($filter_brand)
        // {
        //     $filterflg = 1;
        //     // $this->db->select('cberp_brands.brand_name');
        //     $this->db->join('cberp_product_to_brands', 'cberp_product_to_brands.product_code = cberp_products.product_code');    
        //     // $this->db->join('cberp_brands', 'cberp_brands.id = cberp_product_to_brands.brand_id');
        //     $this->db->where_in('cberp_product_to_brands.brand_id',$filter_brand);
        //     (empty($filter_category)) ? $this->db->group_by('cberp_product_to_brands.product_code') : "" ;
        // }
        if($filter_manufacturer)
        {
            $filterflg = 1;
            $this->db->select('cberp_manufacturer_ai.manufacturer_name');
            $this->db->join('cberp_manufacturer_ai', 'cberp_manufacturer_ai.manufacturer_id = cberp_products.manufacturer_id');   
            $this->db->where_in('cberp_manufacturer_ai.manufacturer_id',$filter_manufacturer);
            (empty($filter_category)) ? $this->db->group_by('cberp_products.manufacturer_id') : "" ;
        }
        if($filter_warehouse)
        {
            $filterflg = 1;
            // $this->db->select('cberp_store.title');
            $this->db->join('cberp_product_to_store', 'cberp_product_to_store.product_code = cberp_products.product_code');   
            // $this->db->join('cberp_store', 'cberp_store.id = cberp_product_to_store.store_id');   
            $this->db->where_in('cberp_product_to_store.store_id',$filter_warehouse);
            // (empty($filter_category)) ? $this->db->group_by('cberp_product_to_store.store_id') : "" ;
        }

        // //check no filters found
        // if($filterflg==0){
        //     $this->db->group_by('cberp_products.product_code');
        // }
        
        $this->db->from($this->table);

        // if ($sub) {
        //     $this->db->join('cberp_product_category', 'cberp_product_category.id = cberp_products.sub_id');
        //     $this->db->where("cberp_products.sub_id=$id");
        // } else {
        //     $this->db->join('cberp_product_category', 'cberp_product_category.id = cberp_products.pcat');
        //     if ($w && $id > 0) {
        //         $this->db->join('cberp_product_to_store', 'cberp_product_to_store.product_code = cberp_products.product_code');
        //         $this->db->where("cberp_product_to_store.store_id = $id");
        //     } else if ($id > 0) {
        //         $this->db->where("cberp_product_category.id = $id");
        //         $this->db->where('cberp_products.sub_id', 0);
        //     }
        // }

        $i = 0;
        foreach ($this->column_search as $item) {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if ($this->input->post('order')) {
            $this->db->order_by($this->column_order[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
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
        // $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse');
        // if ($this->aauth->get_user()->loc) {

        //     $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('cberp_store.loc', 0);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_store.loc', 0);
        // }
        return $this->db->count_all_results();
    }

    public function addnew($warehouse, $product_name, $product_code, $made_in, $product_price, $factoryprice, $taxrate, $disrate, $product_qty, $product_qty_alert, $product_desc, $image, $unit, $barcode, $v_type, $v_stock, $v_alert, $wdate, $code_type, $arabic_name, $manufacturer_id, $manufacturer_partno, $unit_weight, $kg_quantity, $prefered_vendor,$web_price, $w_type = '', $w_stock = '', $w_alert = '', $serial = '',$min_price=0, $kgQuantityCheck=0, $standard_pack=1, $wholesale_price=0, $price_unit="",$max_disrate=0,$status,$aisel='',$rack_no='',$shelf='',$bin='',$date_avaialble,$prd_length='',$prd_width='',$prd_height='',$length_class='',$income_account_number,$expense_account_number,$barcode2,$code_type2)
    {
        
        // $ware_valid = $this->valid_warehouse($warehouse);
        // if (!$sub_cat) $sub_cat = 0;
        // if (!$b_id) $b_id = 0;
        $datetime1 = new DateTime(date('Y-m-d'));

        $datetime2 = new DateTime($wdate);

        $difference = $datetime1->diff($datetime2);
        if (!$difference->d > 0) {
            $wdate = null;
        }
        $data_additional =[];
        $pid="";
        if ($this->aauth->get_user()->loc) {
            // if ($ware_valid['loc'] == $this->aauth->get_user()->loc OR $ware_valid['loc'] == '0' OR $warehouse == 0) {
                if (strlen($barcode) > 5 AND is_numeric($barcode)) {
                    $data = array(
                        // 'pcat' => $catid,
                        // 'warehouse' => $warehouse,
                        'product_name' => $product_name,
                        'product_code' => $product_code,
                        'product_price' => $product_price,
                        'product_cost' => $factoryprice,
                        'taxrate' => $taxrate,
                        'disrate' => $disrate,
                        'qty' => $product_qty,
                        'product_des' => $product_desc,
                        'alert' => $product_qty_alert,
                        'unit' => $unit,
                        'image' => $image,
                        'barcode' => $barcode,
                        'expiry' => $wdate,
                        'code_type' => $code_type,
                        'status' => $status,                        
                        'barcode2' => $barcode2,
                        'code_type2' => $code_type2,
                        // 'sub_id' => $sub_cat,
                        // 'b_id' => $b_id
                    );

                } else {

                    $barcode = rand(100, 999) . rand(0, 9) . rand(1000000, 9999999) . rand(0, 9);

                    $data = array(
                        // 'pcat' => $catid,
                        // 'warehouse' => $warehouse,
                        'product_name' => $product_name,
                        'product_code' => $product_code,
                        'product_price' => $product_price,
                        'product_cost' => $factoryprice,
                        'taxrate' => $taxrate,
                        'disrate' => $disrate,
                        'qty' => $product_qty,
                        'product_des' => $product_desc,
                        'alert' => $product_qty_alert,
                        'unit' => $unit,
                        'image' => $image,
                        'barcode' => $barcode,
                        'expiry' => $wdate,
                        'code_type' => $code_type,
                        'status' => $status,                        
                        'barcode2' => $barcode2,
                        'code_type2' => $code_type2,
                        // 'sub_id' => $sub_cat,
                        // 'b_id' => $b_id
                    );
                }                
                // $this->db->trans_start();
                if ($this->db->insert('cberp_products', $data)) {
                    $pid = $this->db->insert_id();
                    history_table_log('cberp_products_log','product_code',$pid,'Create');
                    $kg_quantity = (!empty($kg_quantity)) ?$kg_quantity:"0";
                    $data_additional = array(
                        "arabic_name" => $arabic_name,
                        "manufacturer_id" => $manufacturer_id,
                        "made_in" => $made_in,
                        "min_price" => $min_price,
                        "manufacturer_partno" => $manufacturer_partno,
                        "unit_weight" => $unit_weight,
                        // "kg_quantity" => $kg_quantity, //erp2024 old field
                        //erp2024 newly added 10-06-2024
                        "pieces_per_kg" => $kg_quantity,
                        "kgQuantityCheck" => $kgQuantityCheck,
                        "standard_pack" => $standard_pack,
                        'item_cost' => $factoryprice,
                        "wholesale_price" => $wholesale_price,
                        "price_unit" => $price_unit,
                        //erp2024 newly added ends 10-06-2024
                        "prefered_vendor" => $prefered_vendor,
                        "product_code" => $pid,
                        "web_price" => $web_price,
                        "item_cost" => $factoryprice,
                        "created_by" => $this->session->userdata('id'),
                        "created_dt" => date("Y-m-d H:i:s"),
                        "max_disrate" => $max_disrate,
                        "aisel" => $aisel,
                        "rack_no" => $rack_no,
                        "shelf" => $shelf,
                        "bin" => $bin,
                        "date_avaialble" => date('Y-m-d',strtotime($date_avaialble)),
                        "prd_length" => $prd_length,
                        "prd_width" => $prd_width,
                        "prd_height" => $prd_height,
                        "length_class" => $length_class,
                        "income_account_number" => $income_account_number,
                        "expense_account_number" => $expense_account_number,
                    );
                //    echo "<pre>"; print_r($pid); die();
                    $this->db->insert('cberp_product_ai', $data_additional);
                    $this->movers(1, $pid, $product_qty, 0, 'Stock Initialized');
                    $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                    //erp2024 newly added section 04-06-2024
                    return $pid;
                    echo json_encode(array('status' => 'Success', 'message' =>
                        $this->lang->line('ADDED') . "  <a href='add' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('products') . "' class='btn btn-secondary btn-sm'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>"));
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        $this->lang->line('ERROR')));
                }
                // if ($serial) {
                //     $serial_group = array();
                //     foreach ($serial as $key => $value) {
                //          if($value) $serial_group[] = array('product_code' => $pid, 'serial' => $value);
                //     }
                //     $this->db->insert_batch('cberp_product_serials', $serial_group);
                // }
                // if ($v_type) {
                //     foreach ($v_type as $key => $value) {
                //         if ($v_type[$key] && numberClean($v_stock[$key]) > 0.00) {
                //             $this->db->select('u.id,u.name,u2.name AS variation');
                //             $this->db->join('cberp_units u2', 'u.rid = u2.id', 'left');
                //             $this->db->where('u.id', $v_type[$key]);
                //             $query = $this->db->get('cberp_units u');
                //             $r_n = $query->row_array();
                //             $data['product_name'] = $product_name . '-' . $r_n['variation'] . '-' . $r_n['name'];
                //             $data['qty'] = numberClean($v_stock[$key]);
                //             $data['alert'] = numberClean($v_alert[$key]);
                //             $data['merge'] = 1;
                //             $data['sub'] = $pid;
                //             $data['vb'] = $v_type[$key];
                //             $this->db->insert('cberp_products', $data);
                //             $pidv = $this->db->insert_id();

                //             $data_additional = array(
                //                 "arabic_name" => $arabic_name,
                //                 "manufacturer_id" => $manufacturer_id,
                //                 "made_in" => $made_in,
                //                 "min_price" => $min_price,
                //                 "manufacturer_partno" => $manufacturer_partno,
                //                 "unit_weight" => $unit_weight,
                //                 "kg_quantity" => $kg_quantity,
                //                 "prefered_vendor" => $prefered_vendor,
                //                 "product_code" => $pidv,
                //                 "web_price" => $web_price,
                //                 "item_cost" => $factoryprice,
                //                 "created_by" => $this->session->userdata('id'),
                //                 "created_dt" => date("Y-m-d H:i:s")
                //             );
                //             $this->db->insert('cberp_product_ai', $data_additional);

                //             $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
                //             $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                //         }
                //     }
                // }
                // if ($w_type) {
                //     foreach ($w_type as $key => $value) {
                //         if ($w_type[$key] && numberClean($w_stock[$key]) > 0.00 && $w_type[$key] != $warehouse) {
                //             $data['product_name'] = $product_name;
                //             $data['warehouse'] = $w_type[$key];
                //             $data['qty'] = numberClean($w_stock[$key]);
                //             $data['alert'] = numberClean($w_alert[$key]);
                //             $data['merge'] = 2;
                //             $data['sub'] = $pid;
                //             $data['vb'] = $w_type[$key];
                //             $this->db->insert('cberp_products', $data);
                //             $pidv = $this->db->insert_id();

                //             $data_additional = array(
                //                 "arabic_name" => $arabic_name,
                //                 "manufacturer_id" => $manufacturer_id,
                //                 "made_in" => $made_in,
                //                 "min_price" => $min_price,
                //                 "manufacturer_partno" => $manufacturer_partno,
                //                 "unit_weight" => $unit_weight,
                //                 "kg_quantity" => $kg_quantity,
                //                 "prefered_vendor" => $prefered_vendor,
                //                 "product_code" => $pidv,
                //                 "web_price" => $web_price,
                //                 "item_cost" => $factoryprice,
                //                 "created_by" => $this->session->userdata('id'),
                //                 "created_dt" => date("Y-m-d H:i:s")
                //             );
                //             $this->db->insert('cberp_product_ai', $data_additional);

                //             $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
                //             $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                //         }
                //     }
                // }
                $this->db->trans_complete();
            // } else {
            //     echo json_encode(array('status' => 'Error', 'message' =>
            //         $this->lang->line('ERROR')));
            // }
        } 
        else {
            if (strlen($barcode) > 5 AND is_numeric($barcode)) 
            {
                $data = array(
                    // 'pcat' => $catid,
                    'warehouse' => $warehouse,
                    'product_name' => $product_name,
                    'product_code' => $product_code,
                    'product_price' => $product_price,
                    'product_cost' => $factoryprice,
                    'taxrate' => $taxrate,
                    'disrate' => $disrate,
                    'qty' => $product_qty,
                    'product_des' => $product_desc,
                    'alert' => $product_qty_alert,
                    'unit' => $unit,
                    'image' => $image,
                    'barcode' => $barcode,
                    'expiry' => $wdate,
                    'code_type' => $code_type,
                    'status' => $status
                    // 'sub_id' => $sub_cat,
                    // 'b_id' => $b_id
                );
            } else
            {
                $barcode = rand(100, 999) . rand(0, 9) . rand(1000000, 9999999) . rand(0, 9);
                $data = array(
                    // 'pcat' => $catid,
                    'warehouse' => $warehouse,
                    'product_name' => $product_name,
                    'product_code' => $product_code,
                    'product_price' => $product_price,
                    'product_cost' => $factoryprice,
                    'taxrate' => $taxrate,
                    'disrate' => $disrate,
                    'qty' => $product_qty,
                    'product_des' => $product_desc,
                    'alert' => $product_qty_alert,
                    'unit' => $unit,
                    'image' => $image,
                    'barcode' => $barcode,
                    'expiry' => $wdate,
                    'code_type' => 'EAN13',
                    'status' => $status
                    // 'sub_id' => $sub_cat,
                    // 'b_id' => $b_id
                );
            } 
            // $this->db->trans_start();
            if ($this->db->insert('cberp_products', $data)) {
                $pid = $this->db->insert_id(); //product_cost
                history_table_log('cberp_products_log','product_code',$pid,'Create');
                $data_additional = array(
                    "arabic_name" => $arabic_name,
                    "manufacturer_id" => $manufacturer_id,
                    "made_in" => $made_in,
                    "min_price" => $min_price,
                    "manufacturer_partno" => $manufacturer_partno,
                    "unit_weight" => $unit_weight,                    
                    'item_cost' => $factoryprice,
                    // "kg_quantity" => $kg_quantity, //erp2024 old field
                    //erp2024 newly added 10-06-2024
                    "pieces_per_kg" => $kg_quantity,
                    "kgQuantityCheck" => $kgQuantityCheck,
                    "standard_pack" => $standard_pack,
                    "wholesale_price" => $wholesale_price,
                    "price_unit" => $price_unit,
                    //erp2024 newly added ends 10-06-2024
                    "prefered_vendor" => $prefered_vendor,
                    "product_code" => $pid,
                    "web_price" => $web_price,
                    "item_cost" => $factoryprice,
                    "created_by" => $this->session->userdata('id'),
                    "created_dt" => date("Y-m-d H:i:s"),
                    // "max_disrate" => $max_disrate,
                    "aisel" => $aisel,
                    "rack_no" => $rack_no,
                    "shelf" => $shelf,
                    "bin" => $bin,
                    "date_avaialble" => date('Y-m-d',strtotime($date_avaialble)),
                    "prd_length" => $prd_length,
                    "prd_width" => $prd_width,
                    "prd_height" => $prd_height,
                    "length_class" => $length_class,
                    "income_account_number" => $income_account_number,
                    "expense_account_number" => $expense_account_number,
                );
                
                $this->db->insert('cberp_product_ai', $data_additional);
                $this->movers(1, $pid, $product_qty, 0, 'Stock Initialized');
                $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                //erp2024 newly added section 04-06-2024
                return $pid;
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('ADDED') . "  <a href='add' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('products') . "' class='btn btn-secondary btn-sm'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
            // if ($serial) {
            //     $serial_group = array();
            //     foreach ($serial as $key => $value) {
            //          if($value)  $serial_group[] = array('product_code' => $pid, 'serial' => $value);
            //     }
            //     $this->db->insert_batch('cberp_product_serials', $serial_group);
            // }
            // if ($v_type) {
            //     foreach ($v_type as $key => $value) {
            //         if ($v_type[$key] && numberClean($v_stock[$key]) > 0.00) {
            //             $this->db->select('u.id,u.name,u2.name AS variation');
            //             $this->db->join('cberp_units u2', 'u.rid = u2.id', 'left');
            //             $this->db->where('u.id', $v_type[$key]);

            //             $query = $this->db->get('cberp_units u');
            //             $r_n = $query->row_array();
            //             $data['product_name'] = $product_name . '-' . $r_n['variation'] . '-' . $r_n['name'];
            //             $data['qty'] = numberClean($v_stock[$key]);
            //             $data['alert'] = numberClean($v_alert[$key]);
            //             $data['merge'] = 1;
            //             $data['sub'] = $pid;
            //             $data['vb'] = $v_type[$key];
            //             $this->db->insert('cberp_products', $data);
            //             $pidv = $this->db->insert_id();

            //             $data_additional = array(
            //                 "arabic_name" => $arabic_name,
            //                 "manufacturer_id" => $manufacturer_id,
            //                 "made_in" => $made_in,
            //                 "min_price" => $min_price,
            //                 "manufacturer_partno" => $manufacturer_partno,
            //                 "unit_weight" => $unit_weight,
            //                 "kg_quantity" => $kg_quantity,
            //                 "prefered_vendor" => $prefered_vendor,
            //                 "product_code" => $pidv,
            //                 "web_price" => $web_price,
            //                 "item_cost" => $factoryprice,
            //                 "created_by" => $this->session->userdata('id'),
            //                 "created_dt" => date("Y-m-d H:i:s")
            //             );
            //             $this->db->insert('cberp_product_ai', $data_additional);

            //             $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
            //             $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
            //         }
            //     }
            // }
            // if ($w_type) {
            //     foreach ($w_type as $key => $value) {
            //         if ($w_type[$key] && numberClean($w_stock[$key]) > 0.00 && $w_type[$key] != $warehouse) {

            //             $data['product_name'] = $product_name;
            //             $data['warehouse'] = $w_type[$key];
            //             $data['qty'] = numberClean($w_stock[$key]);
            //             $data['alert'] = numberClean($w_alert[$key]);
            //             $data['merge'] = 2;
            //             $data['sub'] = $pid;
            //             $data['vb'] = $w_type[$key];
            //             $this->db->insert('cberp_products', $data);
            //             $pidv = $this->db->insert_id();

            //             $data_additional = array(
            //                 "arabic_name" => $arabic_name,
            //                 "manufacturer_id" => $manufacturer_id,
            //                 "made_in" => $made_in,
            //                 "min_price" => $min_price,
            //                 "manufacturer_partno" => $manufacturer_partno,
            //                 "unit_weight" => $unit_weight,
            //                 "kg_quantity" => $kg_quantity,
            //                 "prefered_vendor" => $prefered_vendor,
            //                 "product_code" => $pidv,
            //                 "web_price" => $web_price,
            //                 "item_cost" => $factoryprice,
            //                 "created_by" => $this->session->userdata('id'),
            //                 "created_dt" => date("Y-m-d H:i:s")
                            
            //             );
            //             $this->db->insert('cberp_product_ai', $data_additional);
                        
            //             $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
            //             $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
            //         }
            //     }
            // }
            //return $pid;
            
            $this->custom->save_fields_data($pid, 4);
            // $this->db->trans_complete();
            
        }
    }
    //erp2024 new function 04-06-2024
    public function warehose_stock_insert($data){
        $this->db->insert('cberp_product_to_store', $data);
    }
    public function warehose_stock_update($data,$product_code,$store_id){        
        $this->db->where('product_code', $product_code);
        $this->db->where('store_id', $store_id);
        $this->db->update('cberp_product_to_store', $data);
    }
    public function warehose_stock_check($product_code, $store_id)
    {
        $this->db->select('*');
        $this->db->from('cberp_product_to_store');
        $this->db->where('product_code', $product_code);
        $this->db->where('store_id', $store_id);
        $query = $this->db->get();
        return $query->row_array();

    }
    public function locationwiseproducts($product_code) {
        $this->db->select('cberp_product_to_store.*, cberp_store.store_name as title,  cberp_product_description.product_name, cberp_product_description.product_code');
        $this->db->from('cberp_product_to_store');
        $this->db->join('cberp_store', 'cberp_store.store_id = cberp_product_to_store.store_id');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_product_to_store.product_code');
        $this->db->where('cberp_product_to_store.product_code', $product_code);
        $query = $this->db->get();
        return $query->result_array();
    }

    //erp2024 new function ends

    // public function edit($product_qty)
    public function edit($pid,  $warehouse, $product_name, $product_code, $product_price, $factoryprice,$web_price, $taxrate, $disrate, $product_qty, $product_qty_alert,  $product_desc, $image, $unit, $barcode, $code_type, $vari = null, $serial = null, $wdate = null,$status,$barcode2,$code_type2)
    {

        $this->db->select('qty');
        $this->db->from('cberp_products');
        $this->db->where('pid', $pid);
        $query = $this->db->get();
        $r_n = $query->row_array();
        $ware_valid = $this->valid_warehouse($warehouse);
        $this->db->trans_start();
        // if($code_type=='EAN13')
        // {
        //     $barcode = (strlen($barcode) != 12) ? str_pad($barcode, 12, '0', STR_PAD_LEFT) : $barcode;
        // }
        // else if($code_type=='UPCA'){
        //     $barcode = (strlen($barcode) != 11) ? str_pad($barcode, 11, '0', STR_PAD_LEFT) : $barcode;
        // }
        // else{}

        // if($code_type2=='EAN13')
        // {
        //     $barcode2 = (strlen($barcode2) != 12) ? str_pad($barcode2, 12, '0', STR_PAD_LEFT) : $barcode2;
        // }
        // else if($code_type2=='UPCA'){
        //     $barcode2 = (strlen($barcode2) != 11) ? str_pad($barcode2, 11, '0', STR_PAD_LEFT) : $barcode2;
        // }
        // else{}
        
        if ($this->aauth->get_user()->loc) {
            if ($ware_valid['loc'] == $this->aauth->get_user()->loc OR $ware_valid['loc'] == '0' OR $warehouse == 0) {
                $data = array(
                    // 'pcat' => $catid,
                    'warehouse' => $warehouse,
                    'product_name' => $product_name,
                    'product_code' => $product_code,
                    'product_price' => $product_price,
                    'product_cost' => $factoryprice,
                    'taxrate' => $taxrate,
                    'disrate' => $disrate,
                    'qty' => $product_qty,
                    'product_des' => $product_desc,
                    'alert' => $product_qty_alert,
                    'unit' => $unit,
                    'image' => $image,
                    'barcode' => $barcode,
                    'code_type' => $code_type,
                    'barcode2' => $barcode2,
                    'code_type2' => $code_type2,
                    // 'sub_id' => $sub_cat,
                    // 'b_id' => $b_id,
                    'status' => $status
                );



				$datetime1 = new DateTime(date('Y-m-d'));

				$datetime2 = new DateTime($wdate);

				$difference = $datetime1->diff($datetime2);
				if ($difference->d > 0) {
					$data['expiry'] = $wdate;
				}

               
                $this->db->set($data);
                $this->db->where('pid', $pid);

                if ($this->db->update('cberp_products')) {


                    if ($r_n['qty'] != $product_qty) {
                        $m_product_qty = $product_qty - $r_n['qty'];
                        $this->movers(1, $pid, $m_product_qty, 0, 'Stock Changes');
                    }
                    $this->aauth->applog("[Update Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                    echo json_encode(array(
                        'status' => 'Success', 
                        'message' => $this->lang->line('UPDATED') . 
                            " <a href='" . base_url('products/edit?id='.$pid) . "' class='btn btn-secondary btn-sm' title='Edit'><span class='fa fa-edit' aria-hidden='true'></span></a>".
                            " <a href='" . base_url('products') . "' class='btn btn-secondary btn-sm' title='Product List'><span class='fa fa-list-alt' aria-hidden='true'></span></a>"
                    ));
                    
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        $this->lang->line('ERROR')));
                }
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
        } else {
            $data = array(
                // 'pcat' => $catid,
                'warehouse' => $warehouse,
                'product_name' => $product_name,
                'product_code' => $product_code,
                'product_price' => $product_price,
                'product_cost' => $factoryprice,
                'taxrate' => $taxrate,
                'disrate' => $disrate,
                'qty' => $product_qty,
                'product_des' => $product_desc,
                'alert' => $product_qty_alert,
                'unit' => $unit,
                'image' => $image,
                'barcode' => $barcode,
                'code_type' => $code_type,
                'barcode2' => $barcode2,
                'code_type2' => $code_type2,
                // 'sub_id' => $sub_cat,
                // 'b_id' => $b_id
                'status' => $status
            );

			$datetime1 = new DateTime(date('Y-m-d'));

			$datetime2 = new DateTime($wdate);

			$difference = $datetime1->diff($datetime2);
			if ($difference->d > 0) {
				$data['expiry'] = $wdate;
			}

			$this->db->set($data);
            $this->db->where('pid', $pid);
            if ($this->db->update('cberp_products')) {
                if ($r_n['qty'] != $product_qty) {
                    $m_product_qty = $product_qty - $r_n['qty'];
                    $this->movers(1, $pid, $m_product_qty, 0, 'Stock Changes');
                }
                $this->aauth->applog("[Update Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                echo json_encode(array(
                    'status' => 'Success', 
                    'message' => $this->lang->line('UPDATED') . 
                        " <a href='" . base_url('products/edit?id='.$pid) . "' class='btn btn-secondary btn-sm' title='Edit'><span class='fa fa-edit' aria-hidden='true'></span></a>".
                        " <a href='" . base_url('products') . "' class='btn btn-secondary btn-sm' title='Product List'><span class='fa fa-list-alt' aria-hidden='true'></span></a>"
                ));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
        }

        if (isset($serial['old'])) {
            $this->db->delete('cberp_product_serials', array('product_code' => $pid,'status'=>0));
            $serial_group = array();
            foreach ($serial['old'] as $key => $value) {
                if($value) $serial_group[] = array('product_code' => $pid, 'serial' => $value);
            }
            $this->db->insert_batch('cberp_product_serials', $serial_group);
        }
                if (isset($serial['new'])) {
            $serial_group = array();
            foreach ($serial['new'] as $key => $value) {
                 if($value)  $serial_group[] = array('product_code' => $pid, 'serial' => $value,'status'=>0);
            }

            $this->db->insert_batch('cberp_product_serials', $serial_group);
        }
        $this->custom->edit_save_fields_data($pid, 4);


        $v_type = @$vari['v_type'];
        $v_stock = @$vari['v_stock'];
        $v_alert = @$vari['v_alert'];
        $w_type = @$vari['w_type'];
        $w_stock = @$vari['w_stock'];
        $w_alert = @$vari['w_alert'];

        if (isset($v_type)) {
            foreach ($v_type as $key => $value) {
                if ($v_type[$key] && numberClean($v_stock[$key]) > 0.00) {
                    $this->db->select('u.id,u.name,u2.name AS variation');
                    $this->db->join('cberp_units u2', 'u.rid = u2.id', 'left');
                    $this->db->where('u.id', $v_type[$key]);
                    $query = $this->db->get('cberp_units u');
                    $r_n = $query->row_array();
                    $data['product_name'] = $product_name . '-' . $r_n['variation'] . '-' . $r_n['name'];
                    $data['qty'] = numberClean($v_stock[$key]);
                    $data['alert'] = numberClean($v_alert[$key]);
                    $data['merge'] = 1;
                    $data['sub'] = $pid;
                    $data['vb'] = $v_type[$key];
                    $this->db->insert('cberp_products', $data);
                    $pidv = $this->db->insert_id();
                    $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
                    $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                }
            }
        }
        if (isset($w_type)) {
            foreach ($w_type as $key => $value) {
                if ($w_type[$key] && numberClean($w_stock[$key]) > 0.00 && $w_type[$key] != $warehouse) {
                    $data['product_name'] = $product_name;
                    $data['warehouse'] = $w_type[$key];
                    $data['qty'] = numberClean($w_stock[$key]);
                    $data['alert'] = numberClean($w_alert[$key]);
                    $data['merge'] = 2;
                    $data['sub'] = $pid;
                    $data['vb'] = $w_type[$key];
                    $this->db->insert('cberp_products', $data);
                    $pidv = $this->db->insert_id();
                    $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
                    $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                }
            }
        }
        $this->db->trans_complete();

    }

    public function prd_stats()
    {

        $whr = '';
        // if ($this->aauth->get_user()->loc) {
        //     $whr = ' LEFT JOIN  cberp_store on cberp_store.id = cberp_products.warehouse WHERE cberp_store.loc=' . $this->aauth->get_user()->loc;
        //     if (BDATA) $whr = ' LEFT JOIN  cberp_store on cberp_store.id = cberp_products.warehouse WHERE cberp_store.loc=0 OR cberp_store.loc=' . $this->aauth->get_user()->loc;
        // } elseif (!BDATA) {
        //     $whr = ' LEFT JOIN  cberp_store on cberp_store.id = cberp_products.warehouse WHERE cberp_store.loc=0';
        // }
        $query = $this->db->query("SELECT
        COUNT(IF( cberp_products.onhand_quantity > 0, cberp_products.onhand_quantity, NULL)) AS instock,
        COUNT(IF( cberp_products.onhand_quantity <= 0, cberp_products.onhand_quantity, NULL)) AS outofstock,
        COUNT(cberp_products.onhand_quantity) AS total
        FROM cberp_products $whr");
        echo json_encode($query->result_array());
    }

    public function products_list($id, $term = '')
    {
        $this->db->select('cberp_products.*');
        $this->db->from('cberp_products');
        $this->db->where('cberp_products.warehouse', $id);
        if ($this->aauth->get_user()->loc) {
            $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse');
            $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
        } elseif (!BDATA) {
            $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse');
            $this->db->where('cberp_store.loc', 0);
        }
        if ($term) {
            $this->db->where("cberp_products.product_name LIKE '%$term%'");
            $this->db->or_where("cberp_products.product_code LIKE '$term%'");
        }
        $query = $this->db->get();
        return $query->result_array();

    }

    // erp2024 new function for list products under a selected warehouse 12-06-2024
    public function products_list_by_warehouse($id, $term = '')
    {
        $this->db->select('cberp_products.product_code, cberp_products.product_name, cberp_products.product_code, cberp_products.onhand_quantity,cberp_products.unit, cberp_product_to_store.stock_qty');
        $this->db->from('cberp_product_to_store');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_product_to_store.product_code');
        $this->db->where('cberp_product_to_store.store_id', $id);
        if ($term) {
            $this->db->where("cberp_products.product_name LIKE '%$term%'");
            $this->db->or_where("cberp_products.product_code LIKE '$term%'");
        }
        $query = $this->db->get();
        return $query->result_array();

    }
    // erp2024 new function for list products under a selected warehouse 12-06-2024

    public function units()
    {
        $this->db->select('*');
        $this->db->from('cberp_units');
        $this->db->where('type', 0);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function minprice_list()
    {
        $this->db->select('price_perc');
        $this->db->from('cberp_product_min_price');
        $query = $this->db->get();
        return $query->row_array();

    }
    // erp2024 new function 10-06-2024
    public function priceperc_list()
    {
        $this->db->select('*');
        $this->db->from('cberp_product_min_price');
        $query = $this->db->get();
        return $query->row_array();

    }
    // erp2024 new function 10-06-2024 ends
    public function serials($pid)
    {
        $this->db->select('*');
        $this->db->from('cberp_product_serials');
        $this->db->where('product_code', $pid);

        $query = $this->db->get();
        return $query->result_array();


    }
    // erp2024 new function 05-06-2024
    public function productwise_warehouse_list($pid){
        $this->db->select('*');
        $this->db->from('cberp_product_to_store');
        $this->db->where('product_code', $pid);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }

    public function stock_transfer($from_warehouse, $products_l, $to_warehouse, $qty)
    {

        // echo "<pre>";
        // print_r($to_warehouse);
        // print_r($qty);
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
            $this->db->where('product_code', $row);
            $query = $this->db->get();
            $stock_result = $query->row_array();
            // Get the stock quantity from the query result
            $stock_qty = $stock_result['stock_qty'];
            // If the transfer quantity exceeds the stock quantity, return an error and stop execution
            // if ($transferqty > $stock_qty) {
            //     $flag = 2;
            //     echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR-RECORD')));
            //     exit;  
            // }

            $i++;  // Increment the index for the next iteration
        }
        $stockid = [];
        if($flag==1){
            foreach ($products_l as $row1) {
                $transferqty = 0;
                if (array_key_exists($j, $qtyArray)) {
                    $transferqty = $qtyArray[$j];  
                } 
                $this->db->select('stock_qty');
                $this->db->from('cberp_product_to_store');
                $this->db->where('product_code', $row1);
                $query = $this->db->get();
                $stock_result = $query->row_array();
                $stock_qty = $stock_result['stock_qty'];
                
                //
                //created_by created_dt updated_by updated_dt
                $data = [
                        "product_code" => $row1,
                        "store_id" => $to_warehouse,
                ];
                //erp2024 check transfer warehoues
                $this->db->select('id,stock_qty,intransit_qty');
                $this->db->from('cberp_product_to_store');
                $this->db->where('product_code', $row1);
                $this->db->where('store_id', $to_warehouse);
                $checkquery = $this->db->get();
                $check_result = $checkquery->row_array();
                $chekedID = (!empty($check_result))?$check_result['id']:"0";
                // if($chekedID>0){
                //     $existingQty = $check_result['stock_qty'];
                //     $current_stock = ($existingQty>0)? $existingQty+$transferqty :$transferqty;
                //     $data['stock_qty'] = $current_stock;
                //     $data['updated_by'] = $this->session->userdata('id');
                //     $data['updated_dt'] = date('Y-m-d H:i:s');
                //     $this->db->where('id', $chekedID);
                //     $this->db->update('cberp_product_to_store', $data);
                // }
                // else{
                //     $data['stock_qty'] = $transferqty;
                //     $data['created_by'] = $this->session->userdata('id');
                //     $data['created_dt'] = date('Y-m-d H:i:s');
                //     $this->db->insert("cberp_product_to_store", $data);
                // }

                //erp2024 newly added for intransit 19-06-2024
                //erp2024 newly stock moved to intransist 19-06-2024
                if($chekedID>0){
                    $existingQty = $check_result['intransit_qty'];
                    $current_stock = ($existingQty>0)? $existingQty+$transferqty :$transferqty;
                    $data['intransit_qty'] = $current_stock;
                    $data['updated_by'] = $this->session->userdata('id');
                    $data['updated_dt'] = date('Y-m-d H:i:s');
                    $this->db->where('id', $chekedID);
                    $this->db->update('cberp_product_to_store', $data);
                }
                else{
                    $data['intransit_qty'] = $transferqty;
                    $data['created_by'] = $this->session->userdata('id');
                    $data['created_dt'] = date('Y-m-d H:i:s');
                    $this->db->insert("cberp_product_to_store", $data);
                }
                //erp2024 newly stock moved to intransist 19-06-2024 ends

                //erp2024 check transfer warehoues
                $this->db->select('id,stock_qty');
                $this->db->from('cberp_product_to_store');
                $this->db->where('product_code', $row1);
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
                    "product_code"     => $row1,
                    "transfer_qty"   => $transferqty,
                    "warehouse_from" => $from_warehouse,
                    "warehouse_to"   => $to_warehouse,
                    "transfered_by"  => $this->session->userdata('id'),
                    "transfered_dt"  => date('Y-m-d H:i:s'),                    
                    'intransit_qty'  => $transferqty,
                    'requested_qty'  => $requested_qty,
                    'status'         => "Intransit",
                ];
                $this->db->insert("stock_transfer_wh_to_wh", $stock_tranfer);
                $stockeid[] = $this->db->insert_id();
                //erp2024 operation in transfer from warehouse

                $j++;
                
            }
            $this->session->set_userdata('directtransferids', $stockeid);
            $target = base_url() . "stocktransfer/";
            $printurl = base_url() . "stocktransfer/print_trasfernote_direct";
            echo json_encode(array(
                'status' => 'Success',
                'message' => $this->lang->line('ADDED') . " <a href='$target' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span></a> &nbsp;<a href='$printurl' class='btn btn-secondary btn-sm' target='_blank'><span class='fa fa-print' aria-hidden='true'></span></a>"
            ));
        }
        else{
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR-RECORD')));
        }
        


    }
    // erp2024 new function 05-06-2024 ends

    // erp2024 removed function transfer
    public function transfer($from_warehouse, $products_l, $to_warehouse, $qty)
    {


      
        $updateArray = array();
        $move = false;
        $qtyArray = explode(',', $qty);
        $this->db->select('title');
        $this->db->from('cberp_store');
        $this->db->where('id', $to_warehouse);
        $query = $this->db->get();
        $to_warehouse_name = $query->row_array()['title'];

        $i = 0;
        foreach ($products_l as $row) {
            $qty = 0;
            if (array_key_exists($i, $qtyArray)) $qty = $qtyArray[$i];

            $this->db->select('*');
            $this->db->from('cberp_products');
            $this->db->where('pid', $row);
            $query = $this->db->get();
            $pr = $query->row_array();
            $pr2 = $pr;
            $c_qty = $pr['qty'];
            if ($c_qty - $qty < 0) {

            } elseif ($c_qty - $qty == 0) {


                if ($pr['merge'] == 2) {

                    $this->db->select('pid,product_name');
                    $this->db->from('cberp_products');
                    $this->db->where('pid', $pr['sub']);
                    $this->db->where('warehouse', $to_warehouse);
                    $query = $this->db->get();
                    $pr = $query->row_array();

                } else {
                    $this->db->select('pid,product_name');
                    $this->db->from('cberp_products');
                    $this->db->where('merge', 2);
                    $this->db->where('sub', $row);
                    $this->db->where('warehouse', $to_warehouse);
                    $query = $this->db->get();
                    $pr = $query->row_array();
                }


                $c_pid = $pr['pid'];
                $product_name = $pr['product_name'];

                if ($c_pid) {

                    $this->db->set('qty', "qty+$qty", FALSE);
                    $this->db->where('pid', $c_pid);
                    $this->db->update('cberp_products');
                    $this->aauth->applog("[Product Transfer] -$product_name  -Qty-$qty ID " . $c_pid, $this->aauth->get_user()->username);
                    $this->db->delete('cberp_products', array('pid' => $row));
                    $this->db->delete('cberp_movers', array('d_type' => 1, 'rid1' => $row));

                } else {
                    $updateArray[] = array(
                        'pid' => $row,
                        'warehouse' => $to_warehouse
                    );
                    $move = true;
                    $product_name = $pr2['product_name'];
                    $this->db->delete('cberp_movers', array('d_type' => 1, 'rid1' => $row));

                    $this->movers(1, $row, $qty, 0, 'Stock Transferred & Initialized W- ' . $to_warehouse_name);
                    $this->aauth->applog("[Product Transfer] -$product_name  -Qty-$qty W- $to_warehouse_name PID " . $pr2['pid'], $this->aauth->get_user()->username);
                }


            } else {
                $data['product_name'] = $pr['product_name'];
                $data['pcat'] = $pr['pcat'];
                $data['warehouse'] = $to_warehouse;
                $data['product_name'] = $pr['product_name'];
                $data['product_code'] = $pr['product_code'];
                $data['product_price'] = $pr['product_price'];
                $data['product_cost'] = $pr['product_cost'];
                $data['taxrate'] = $pr['taxrate'];
                $data['disrate'] = $pr['disrate'];
                $data['qty'] = $qty;
                $data['product_des'] = $pr['product_des'];
                $data['alert'] = $pr['alert'];
                $data['	unit'] = $pr['unit'];
                $data['image'] = $pr['image'];
                $data['barcode'] = $pr['barcode'];
                $data['merge'] = 2;
                $data['sub'] = $row;
                $data['vb'] = $to_warehouse;
                if ($pr['merge'] == 2) {
                    $this->db->select('pid,product_name');
                    $this->db->from('cberp_products');
                    $this->db->where('pid', $pr['sub']);
                    $this->db->where('warehouse', $to_warehouse);
                    $query = $this->db->get();
                    $pr = $query->row_array();
                } else {
                    $this->db->select('pid,product_name');
                    $this->db->from('cberp_products');
                    $this->db->where('merge', 2);
                    $this->db->where('sub', $row);
                    $this->db->where('warehouse', $to_warehouse);
                    $query = $this->db->get();
                    $pr = $query->row_array();
                }


                $c_pid = $pr['pid'];
                $product_name = $pr2['product_name'];

                if ($c_pid) {

                    $this->db->set('qty', "qty+$qty", FALSE);
                    $this->db->where('pid', $c_pid);
                    $this->db->update('cberp_products');

                    $this->movers(1, $c_pid, $qty, 0, 'Stock Transferred W ' . $to_warehouse_name);
                    $this->aauth->applog("[Product Transfer] -$product_name  -Qty-$qty W $to_warehouse_name  ID " . $c_pid, $this->aauth->get_user()->username);


                } else {
                    $this->db->insert('cberp_products', $data);
                    $pid = $this->db->insert_id();
                    $this->movers(1, $pid, $qty, 0, 'Stock Transferred & Initialized W ' . $to_warehouse_name);
                    $this->aauth->applog("[Product Transfer] -$product_name  -Qty-$qty  W $to_warehouse_name ID " . $pr2['pid'], $this->aauth->get_user()->username);

                }

                $this->db->set('qty', "qty-$qty", FALSE);
                $this->db->where('pid', $row);
                $this->db->update('cberp_products');
                $this->movers(1, $row, -$qty, 0, 'Stock Transferred WID ' . $to_warehouse_name);
            }


            $i++;
        }

        if ($move) {
            $this->db->update_batch('cberp_products', $updateArray, 'pid');
        }

        echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('UPDATED')));


    }
    // erp2024 removed function transfer ends


    public function meta_delete($name)
    {
        if (@unlink(FCPATH . 'userfiles/product/' . $name)) {
            return true;
        }
    }

    public function valid_warehouse($warehouse)
    {
        $this->db->select('id,loc');
        $this->db->from('cberp_store');
        $this->db->where('id', $warehouse);
        $query = $this->db->get();
        $row = $query->row_array();
        return $row;
    }


    public function movers($type = 0, $rid1 = 0, $rid2 = 0, $rid3 = 0, $note = '')
    {
        $data = array(
            'd_type' => $type,
            'rid1' => $rid1,
            'rid2' => $rid2,
            'rid3' => $rid3,
            'note' => $note
        );
        $this->db->insert('cberp_movers', $data);
    }
    //erp2024 new functions 06-06-2024 starts 
    public function get_total_purchse_quantity($product_code) {
        $this->db->select('SUM(cberp_purchase_order_items.quantity) as total_qty');
        $this->db->from('cberp_purchase_orders');
        $this->db->join('cberp_purchase_order_items', 'cberp_purchase_order_items.purchase_number = cberp_purchase_orders.purchase_number');
        $this->db->where('cberp_purchase_order_items.product_code', $product_code);
        $this->db->where('cberp_purchase_orders.order_status !=', 'Dummy');
        $this->db->where_in('cberp_purchase_orders.payment_status', array('paid', 'due', 'canceled', 'partial'));
        $query = $this->db->get();
        $result = $query->row();
        return $result ? $result->total_qty : 0;
    }
    
    public function get_total_sales_quantity($product_code) {
        $this->db->select('SUM(cberp_sales_orders_items.qty) as total_qty');
        $this->db->from('cberp_sales_orders');
        $this->db->join('cberp_sales_orders_items', 'cberp_sales_orders.id = cberp_sales_orders_items.tid');
        $this->db->where('cberp_sales_orders_items.pid', $product_code);
        $this->db->where_in('cberp_sales_orders.status', array('pending', 'accepted', 'rejected', 'customer_approved'));
        $query = $this->db->get();
        // Uncomment the line below to debug the query
        // echo $this->db->last_query();
        $result = $query->row();
        return $result ? $result->total_qty : 0;
    }
    
    //erp2024 new functions 06-06-2024 ends
    //erp2024 new functions 07-06-2024 starts
    public function products_list_by_id($id)
    {
        $this->db->select('cberp_products.product_name,cberp_products.product_code,cberp_products.product_code,cberp_products.onhand_quantity');
        $this->db->from('cberp_products');
        // $this->db->where('cberp_products.product_code', $id);
        $this->db->where_in('cberp_products.product_code', $id);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function warehouse_by_productid($id, $towarehouse_ids)
    {
        $this->db->select('cberp_store.id, cberp_store.title, cberp_product_to_store.stock_qty');
        $this->db->from('cberp_product_to_store');
        $this->db->join('cberp_store', 'cberp_store.id = cberp_product_to_store.store_id');
        $this->db->where('cberp_product_to_store.product_code', $id);
        if (!empty($towarehouse_ids)) {
            $this->db->where_not_in('cberp_product_to_store.store_id', $towarehouse_ids); // Exclude specified warehouses
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function warehouse_list()
    {
        $this->db->select('cberp_store.store_id as id,cberp_store.store_name AS title');
        $this->db->from('cberp_store');
        $query = $this->db->get();
        return $query->result_array();

    }
    //erp2024 new functions 07-06-2024 ends

    public function get_product_details($product_code)
    {
        $this->db->select('cberp_products.*, cberp_product_category.title as category, 
                        cberp_manufacturer_ai.manufacturer_name, cberp_suppliers.name AS supplier, 
                        cberp_country.name AS madein');
        $this->db->from('cberp_products');
        $this->db->join('cberp_product_category', 'cberp_product_category.id = cberp_products.pcat', 'inner');
        $this->db->join('cberp_manufacturer_ai', 'cberp_manufacturer_ai.manufacturer_id = cberp_products.manufacturer_id', 'left');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_products.prefered_vendor', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_products.made_in', 'left');
        $this->db->where('cberp_products.product_code', $product_code);

        $query = $this->db->get();
        return $query->row_array();
    }
    //erp2024 new functions 20-08-2024 ends

    public function product_warehousewise_stock($product_code)
    {
        $this->db->select('cberp_product_to_store.stock_qty, cberp_store.title');
        $this->db->from('cberp_product_to_store');
        $this->db->join('cberp_store', 'cberp_store.id = cberp_product_to_store.store_id');
        $this->db->where('cberp_product_to_store.product_code', $product_code);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_product_cost($product_code)
    {
        $this->db->select('cberp_products.product_cost');
        $this->db->from('cberp_products');
        // $this->db->where('cberp_products.product_code', $product_code);
        $this->db->where('cberp_products.product_code', $product_code);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['product_cost']);
        } else {
            return 0;
        }

    }

    //erp2024 08-10-2024
    public function category_bysubcatid($id)
    {
        $this->db->select('rel_id');
        $this->db->from('cberp_product_category');
        $this->db->where('cberp_product_category.id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['rel_id']);
        } else {
            return 0;
        }

    }
    public function load_brands()
    {
        $this->db->select('id,brand_name');
        $this->db->from('cberp_brands');
        $this->db->where('cberp_brands.status', 'Enable');
        $query = $this->db->get();
        return $query->result_array();

    }
    public function get_linked_categories($product_code)
    {
        $this->db->select('category_id');
        $this->db->from('cberp_product_to_category');
        $this->db->where('product_code', $product_code);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function get_linked_subcategories($id)
    {
        $this->db->select('subcategory_id');
        $this->db->from('cberp_product_to_subcategory');
        $this->db->where('product_code', $id);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function get_linked_brands($id)
    {
        $this->db->select('brand_id');
        $this->db->from('cberp_product_to_brands');
        $this->db->where('product_code', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function brand_list()
    {
        $this->db->select('id,brand_name as title');
        $this->db->from('cberp_brands');
        $this->db->where('status', 'Enable');
        $this->db->order_by('brand_name','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function manufacturer_list()
    {
        $this->db->select('manufacturer_id as id, manufacturer_name as title');
        $this->db->from('cberp_manufacturer_ai');
        // $this->db->where('status', 'Enable');
        $this->db->order_by('manufacturer_name','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function min_max_product_price()
    {
        $this->db->select('MIN(product_price) AS minimum, MAX(product_price) AS maximum');
        $query = $this->db->get('cberp_products');
        return $query->row_array();
    }


    public function default_supplier()
    {
        $this->db->select('supplier_id,name');
        $this->db->from('cberp_suppliers');
        $this->db->order_by('supplier_id','ASC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function lastsrvNumber()
    {
        $this->db->select('MAX(srv) + 1 as next_id');
        $this->db->from('cberp_purchase_receipts');
        $query = $this->db->get();
        $next_id = $query->row()->next_id;
        if ($query->num_rows() > 0) {
            return $next_id+1;
        } else {
            return 1001;
        }
        
    }

    public function last_reciept_number()
    {
        $prefixlist = get_prefix();
        $prefix = $prefixlist['receipt_prefix'];
        $this->db->select('purchase_reciept_number, purchase_number');
        $this->db->from("cberp_purchase_receipts");
        $this->db->order_by('id', 'DESC');
        $this->db->group_start();
        $this->db->where('purchase_reciept_number !=', NULL);
        $this->db->or_where('purchase_reciept_number !=', '');
        $this->db->group_end();
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $latest_srv = $query->row()->purchase_reciept_number;
            if ($query->row()->purchase_reciept_number) {
                $numeric_part = intval(substr($latest_srv, strlen($prefix)));   
                if($numeric_part==0)
                {
                    $numeric_part = $numeric_part+1000;
                }
                // Increment the number
                $new_srv = $prefix . ($numeric_part + 1);
                return $new_srv;
            } 
            else {              
              
                // Extract the numeric part from the latest srv  
                $numeric_part = intval(substr($latest_srv, strlen($prefix)));
                return $numeric_part;
                if($numeric_part==0)
                {
                    $numeric_part = 1000;
                }
                // Increment the number
                $new_srv = $prefix . ($numeric_part + 1);
                return $new_srv;
            }
        } 
        else {
            
            return $prefix . "1001";
        }
    }


    public function gethistory($pid)
    {
        $this->db->select('cberp_products_log.*,cberp_employees.name');
        $this->db->from('cberp_products_log');  
        $this->db->join('cberp_employees','cberp_products_log.performed_by=cberp_employees.id');
        $this->db->where('cberp_products_log.product_code',$pid);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function history($pid){
        
        $this->db->select('cberp_products.*,cberp_employees.name,cberp_product_inventory_log.*');
        $this->db->from('cberp_product_inventory_log');
        $this->db->join('cberp_products','cberp_product_inventory_log.product_code=cberp_products.product_code');
        $this->db->join('cberp_employees','cberp_product_inventory_log.performed_by=cberp_employees.id');
        $this->db->where('cberp_product_inventory_log.product_code',$pid);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    //erp2024 06-01-2025 detailed history log starts
    public function get_detailed_log($id,$page)
    {
        $this->db->select('cberp_master_log.*,cberp_employees.name,cberp_employees.picture');
        $this->db->from('cberp_master_log');  
        $this->db->join('cberp_employees','cberp_master_log.changed_by=cberp_employees.id');
        $this->db->where('cberp_master_log.item_no',$id);
        $this->db->where('cberp_master_log.log_from',$page);
        $this->db->order_by('cberp_master_log.seqence_number', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    //erp2024 06-01-2025 detailed history log ends
    public function products_list_by_warehouse_for_label_print($store_id, $term)
    {
        if ($term) {
            $this->db->select('cberp_products.product_code, cberp_products.product_name, cberp_products.product_code, cberp_products.onhand_quantity, cberp_products.unit, cberp_product_to_store.stock_qty');
            $this->db->from('cberp_product_to_store');
            $this->db->join('cberp_products', 'cberp_products.product_code = cberp_product_to_store.product_code');
            $this->db->where('cberp_product_to_store.store_id', $store_id);            
            
                $this->db->group_start(); // Start grouping the 'OR' condition
                $this->db->like("cberp_products.product_name", $term);
                $this->db->or_like("cberp_products.product_code", $term);
                $this->db->group_end(); // End grouping the 'OR' condition
            
            $this->db->limit(10);
            
            $query = $this->db->get();
            // echo $this->db->last_query();
            return $query->result_array();
        
        }
    }

    public function products_list_by_warehouse_for_label_print_with_barcodetype($store_id, $term, $code_type)
    {
        if ($term) {
            $this->db->select('cberp_products.product_code, cberp_products.product_name, cberp_products.product_code, cberp_products.onhand_quantity, cberp_products.unit, cberp_product_to_store.stock_qty');
            $this->db->from('cberp_product_to_store');
            $this->db->join('cberp_products', 'cberp_products.product_code = cberp_product_to_store.product_code');
            $this->db->where('cberp_product_to_store.store_id', $store_id);

            // Grouping condition for search term
            $this->db->group_start(); 
            $this->db->like("cberp_products.product_name", $term);
            // $this->db->or_like("cberp_products.product_code", $term);
            $this->db->group_end();

            // Check both code_type and code_type2
            $this->db->group_start();
            $this->db->where('cberp_products.code_type', $code_type);
            $this->db->or_where('cberp_products.code_type2', $code_type);
            $this->db->group_end(); 

            $this->db->limit(10);

            $query = $this->db->get();
            return $query->result_array();
        }
    }


    public function default_income_expense_account()
    {
        $this->db->select('product_income,product_expense');
        $this->db->from('cberp_default_double_entry_accounts');  
        $query = $this->db->get();
        return $query->row_array();
    }
    public function last_cost_by_product_code($product_code)
    {
        $this->db->select('product_average_cost');
        $this->db->from('cberp_average_cost');
        $this->db->where('product_code', $product_code);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        // die( $this->db->last_query());
        if ($query->num_rows() > 0) {
            $product_average_cost = $query->row()->product_average_cost;
        }
        else{
            $product_average_cost = 0;
        }
        return $product_average_cost;
    }

    public function get_all_product(){
        $this->db->select('pid,product_code,product_name,product_price,product_cost,qty,alert,cberp_product_category.title');
        $this->db->from('cberp_products');
        $this->db->join('cberp_product_to_category','cberp_product_to_category.product_code=cberp_products.product_code');
        $this->db->join('cberp_product_category','cberp_product_category.id=cberp_product_to_category.category_id');

        $this->db->where('cberp_products.status','Enable');
        $this->db->order_by('cberp_products.product_code','desc');
        $this->db->group_by('cberp_products.product_code');

        $result = $this->db->get();
        // echo $this->db->last_query();die();
        $show = $result->result_array();
        return $show;
    }
    public function get_low_qty_product(){
            $this->db->select('pid, product_code, product_name, product_price, product_cost, qty, alert, cberp_product_category.title');
            $this->db->from('cberp_products');
            $this->db->join('cberp_product_to_category', 'cberp_product_to_category.product_code = cberp_products.product_code');
            $this->db->join('cberp_product_category', 'cberp_product_category.id = cberp_product_to_category.category_id');
        
            $this->db->where('cberp_products.status', 'Enable');
            $this->db->where('qty <= alert'); 
        
            $this->db->group_by('cberp_products.product_code');
            $this->db->order_by('cberp_products.product_code', 'desc');
        
            $result = $this->db->get();
            // echo $this->db->last_query();die();
            return $result->result_array();
    }

    //22-05-2025
    public function product_details_by_code($product_code)
    {
        $this->db->select('cberp_products.*,cberp_products.product_code AS productcode, cberp_product_pricing.*, cberp_product_locations.*, cberp_product_description.*, cberp_product_barcode.*');
        $this->db->from('cberp_products');
        $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code');
        $this->db->join('cberp_product_locations', 'cberp_product_locations.product_code = cberp_products.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->join('cberp_product_barcode', 'cberp_product_barcode.product_code = cberp_products.product_code','left');
        $this->db->where('cberp_products.product_code', $product_code);
        $query = $this->db->get();
        return $query->row_array(); 

    }
    
}
