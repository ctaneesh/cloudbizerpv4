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

class Products extends CI_Controller
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
        $this->load->model('categories_model');
        $this->load->model('manufacturers_model');
        $this->load->model('supplier_model');
        $this->load->model('country_model');
        $this->load->model('purchase_model', 'purchase');
        $this->load->library("Custom");
        $this->li_a = 'stock';
       

    }

    public function index()
    {
        $data['permissions'] = load_permissions('Stock','Products','Manage Products','List');
        $head['title'] = "Products";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/products',$data);
        $this->load->view('fixed/footer');
    }

    public function cat()
    {
        $head['title'] = "Product Categories";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/cat_productlist');
        $this->load->view('fixed/footer');
    }


    public function add()
    {
        $data = [];
        $data['productcode'] = '';
        $product_code = $this->input->get('code');      
        if($product_code)  
        {
            $linked_ids = $this->products->get_linked_categories($product_code);  
            $data['category_linked'] = array_column($linked_ids, 'category_id'); 
            // $brand_linked = $this->products->get_linked_brands($product_code);
            // $data['brand_linked'] = array_column($brand_linked, 'brand_id'); 
            $data['productcode'] = $product_code;
            $data['incomeaccounts']  = coa_account_list_by_header('Income');
            $data['expenseaccounts'] = coa_account_list_by_header('Expenses');            
            $data['productwise_warehouse'] = $this->products->productwise_warehouse_list($product_code);
            $page = "product";
            $data['detailed_log']= $this->products->get_detailed_log($product_code,$page);
            $products = $data['detailed_log'];
            $groupedBySequence = []; 
            foreach ($products as $product) {
                $sequence = $product['seqence_number'];
                $groupedBySequence[$sequence][] = $product; 
            }
            $data['groupedDatas'] = $groupedBySequence;        
            $data['images']= get_product_files($product_code);
            $data['product'] =  $this->products->product_details_by_code($product_code);
            //  echo "<pre>"; print_r($data['product']); die();
        }

        
        $data['cat'] = $this->categories_model->get_category_tree();
        // $data['cat'] = $this->categories_model->category_list();
        $data['manufacturers'] = $this->manufacturers_model->manufacturer_lists();
        $data['suppliers'] = $this->supplier_model->supplier_lists();
        $data['madein'] = $this->country_model->country_list();
        $minprice = $this->products->priceperc_list();
        $data['min_price_prec'] = $minprice['price_perc'];
        $data['selling_price_perc'] = $minprice['selling_price_perc'];
        $data['whole_price_perc'] = $minprice['whole_price_perc'];
        $data['web_price_perc'] = $minprice['web_price_perc'];
        $data['units'] = $this->products->units();
        $data['warehouse'] = $this->categories_model->warehouse_list_type();
        // echo "<pre>"; print_r($data['suppliers']); die();
        $data['custom_fields'] = $this->custom->add_fields(4);
        $this->load->model('units_model', 'units');
        $data['variables'] = $this->units->variables_list();
        //erp2024 08-10-2024 load brands        
        $data['brands'] = $this->products->load_brands();
        $data['incomeaccounts']  = coa_account_list_by_header('Income');
        $data['expenseaccounts'] = coa_account_list_by_header('Expenses');
        $data['defaultaccounts'] = $this->products->default_income_expense_account();
        // print_r($data['defaultaccounts']); die();

        $head['title'] = "Add Product";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/product-add', $data);
        $this->load->view('fixed/footer');
    }

    public function edit()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['permissions'] = load_permissions('Stock','Products','Manage Products','Edit Page');
        // if (!$this->aauth->premission(14)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $pid = $this->input->get('id');
        $this->db->select('*');
        $this->db->from('cberp_products');
        $this->db->where('pid', $pid);
        $query = $this->db->get();
        $data['product'] = $query->row_array();
        $this->db->select('*');
        $this->db->from('cberp_product_ai');
        $this->db->where('product_id', $pid);
        $query1 = $this->db->get();
        $data['productai'] = $productai_data =  $query1->row_array();
        
        if(!empty($productai_data['updated_by'])){
            $this->db->select('email,username');
            $this->db->from('cberp_users');
            $this->db->where('id', $productai_data['updated_by']);
            $aiquery =  $this->db->get();
            $data['userdetails'] = $aiquery->row_array();
            $data['userdetails']['updated_dt'] = $productai_data['updated_dt'];

        }
        $data['manufacturers'] = $this->manufacturers_model->manufacturer_lists();
        $data['suppliers'] = $this->supplier_model->supplier_lists();
        $data['units'] = $this->products->units();
        $data['madein'] = $this->country_model->country_list();
        if ($data['product']['merge'] > 0) {
            $this->db->select('*');
            $this->db->from('cberp_products');
            $this->db->where('merge', 1);
            $this->db->where('sub', $pid);
            $query = $this->db->get();
            $data['product_var'] = $query->result_array();
            $this->db->select('*');
            $this->db->from('cberp_products');
            $this->db->where('merge', 2);
            $this->db->where('sub', $pid);
            $query = $this->db->get();
            $data['product_ware'] = $query->result_array();
        }

        $data['purchase_qty'] = $this->products->get_total_purchse_quantity($data['product']['product_code']);
        $data['sales_qty'] = $this->products->get_total_sales_quantity($pid);
        $data['units'] = $this->products->units();
        $data['serial_list'] = $this->products->serials($data['product']['pid']);
        $data['cat_ware'] = $this->categories_model->cat_ware($pid);
        $data['cat_sub'] = $this->categories_model->sub_cat_curr($data['product']['sub_id']);
       
        $data['warehouse'] = $this->categories_model->warehouse_list_type();
        $data['productwise_warehouse'] = $this->products->productwise_warehouse_list($pid);
        $data['cat'] = $this->categories_model->category_list();
        $data['custom_fields'] = $this->custom->view_edit_fields($pid, 4);
        $head['title'] = "Edit Product";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('units_model', 'units');
        $data['variables'] = $this->units->variables_list();
        //erp2024 newly added 05-06-2024        
        $minprice = $this->products->priceperc_list();
        $data['min_price_prec'] = $minprice['price_perc'];
        $data['selling_price_perc'] = $minprice['selling_price_perc'];
        $data['whole_price_perc'] = $minprice['whole_price_perc'];
        $data['web_price_perc'] = $minprice['web_price_perc'];
        //erp2024 newly added 05-06-2024
        //erp2024 newly added 08-10-2024 starts 
        $data['brands'] = $this->products->load_brands();
        $linked_ids = $this->products->get_linked_categories($pid);  
        $data['category_linked'] = array_column($linked_ids, 'category_id');        
        $data['cat_sub_list'] = $this->categories_model->sub_cat_list_in($data['category_linked']);
        // $data['cat_sub_list'] = $this->categories_model->sub_cat_list($data['product']['pcat']);
        $subcategory_linked = $this->products->get_linked_subcategories($pid);
        $data['subcategory_linked'] = array_column($subcategory_linked, 'subcategory_id'); 
        $brand_linked = $this->products->get_linked_brands($pid);
        $data['brand_linked'] = array_column($brand_linked, 'brand_id'); 

        $data['incomeaccounts']  = coa_account_list_by_header('Income');
        $data['expenseaccounts'] = coa_account_list_by_header('Expenses');
        // echo "<pre>"; print_r($data['subcategory_linked']); print_r($data['brand_linked']); die();
        //erp2024 newly added 08-10-2024 ends
        $data['log'] = $this->products->gethistory($pid);
        $data['inv_log'] = $this->products->history($pid);
        //erp2024 06-01-2025 detailed history log starts
        $page = "product";
        $data['detailed_log']= $this->products->get_detailed_log($pid,$page);
        $data['images']= get_uploaded_images('Products',$pid);
        $products = $data['detailed_log'];
        $groupedBySequence = []; 
        foreach ($products as $product) {
            $sequence = $product['seqence_number'];
            $groupedBySequence[$sequence][] = $product; 
        }
        $data['groupedDatas'] = $groupedBySequence;
        //erp2024 06-01-2025 detailed history log ends
        $this->load->view('fixed/header', $head);
        $this->load->view('products/product-edit', $data);
        $this->load->view('fixed/footer');

    }


    public function product_list()
    {
        $catid = $this->input->get('id');
        $sub = $this->input->get('sub');
      
        if ($catid > 0) {
            $list = $this->products->get_datatables($catid, '', $sub);
        } else {
            $list = $this->products->get_datatables();
        }
        $data = array();
        $no = $this->input->post('start');
        $permissions = load_permissions('Stock','Products','Manage Products','List');
        
        $functions = array_column($permissions, 'function');
        $reportcls = !in_array('Reports', $functions) ? 'd-none' : '';
        $deletecls = !in_array('Delete', $functions) ? 'd-none' : '';
        $editcls = !in_array('Edit', $functions) ? '' : 'd-none';
        $printcls = !in_array('Print', $functions) ? '' : 'd-none';
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $row[] = $no;
            $pid = $prd->productcode;
            $image_url = base_url('userfiles/product/thumbnail/' . $prd->image);
            $image_path = FCPATH . 'userfiles/product/thumbnail/' . $prd->image;
            if (!file_exists($image_path) || empty($prd->image)) {
                $image_url = base_url('userfiles/product/thumbnail/default.png');
            }
            $row[] = '<a href="' . base_url() . 'products/add?code=' . $pid . '"  ><div class="text-center"><img style="max-width: 50px" src="' . $image_url . '" ></div></a>';
            // $row[] = '<a href="#" data-object-id="' . $pid . '" class="view-object">' . $prd->product_name . '</a>';
            $row[] = $prd->productcode; 
            $row[] = '<a href="' . base_url() . 'products/add?code=' . $pid . '"  >' . $prd->product_name . '</a>';
                     
            $row[] = $prd->onhand_quantity;
            // $row[] = $prd->c_title;
            $row[] = +$prd->onhand_quantity; 
            $row[] = $prd->alert_quantity; 
            // $row[] = $prd->title;
            $row[] = $prd->product_price;
            $row[] = (!empty($prd->date_avaialble)) ? date('d-m-Y',strtotime($prd->date_avaialble)):"";
            $row[] = $prd->status;
            $row[] = '<div class="btn-group"> 
                    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm '.$printcls.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i> </button>                                 
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="' . base_url() . 'products/barcode?id=' . $pid . '" target="_blank" > ' . $this->lang->line('BarCode') . '</a><div class="dropdown-divider"></div> <a class="dropdown-item" href="' . base_url() . 'products/posbarcode?id=' . $pid . '" target="_blank"> ' . $this->lang->line('BarCode') . ' - Compact</a> <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="' . base_url() . 'products/label?id=' . $pid . '" target="_blank"> ' . $this->lang->line('Product') . ' Label</a><div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="' . base_url() . 'products/poslabel?id=' . $pid . '" target="_blank"> Label - Compact</a></div></div> 
                            <a class="btn btn-secondary  btn-sm '.$reportcls.'" href="' . base_url() . 'products/report_product?id=' . $pid . '" target="_blank" title="Reports"> <span class="fa fa-pie-chart"></span> </a> 
                            <a href="' . base_url() . 'products/add?code=' . $pid . '"  class="btn btn-secondary btn-sm '.$editcls.'" title="Edit"><span class="fa fa-edit"></span></a>
                            <a href="#" data-object-id="' . $pid . '" class="btn btn-secondary btn-sm  delete-object '.$deletecls.'" title="Delete"><span class="fa fa-trash"></span></a>
                    </div>
                </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->products->count_all($catid, '', $sub),
            "recordsFiltered" => $this->products->count_filtered($catid, '', $sub),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function product_listcat()
    {
        $catid = $this->input->get('id');
        $sub = $this->input->get('sub');

        if ($catid > 0) {
            $list = $this->products->get_datatables($catid, '', $sub);
        } else {
            $list = $this->products->get_datatables();
        }
        $data = array();
        $no = $this->input->post('start');
       
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $row[] = $no;
            $pid = $prd->pid;           
            $row[] = $prd->product_code;
            $row[] = '<a href="' . base_url() . 'products/add?code=' . $pid . '"  target="_blank">' . $prd->product_name . '</a>';
            $row[] = +$prd->qty;
            // $row[] = $prd->product_code;
            $row[] = $prd->c_title;
            // $row[] = $prd->title;
            // $row[] = amountExchange($prd->product_price, 0,$this->aauth->get_user()->loc);
            $row[] = '<div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i> </button>                                    
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="' . base_url() . 'products/barcode?id=' . $pid . '" target="_blank"> ' . $this->lang->line('BarCode') . '</a><div class="dropdown-divider"></div> <a class="dropdown-item" href="' . base_url() . 'products/posbarcode?id=' . $pid . '" target="_blank"> ' . $this->lang->line('BarCode') . ' - Compact</a> <div class="dropdown-divider">
                    </div>
                    <a class="dropdown-item" href="' . base_url() . 'products/label?id=' . $pid . '" target="_blank"> ' . $this->lang->line('Product') . ' Label</a><div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="' . base_url() . 'products/poslabel?id=' . $pid . '" target="_blank"> Label - Compact</a></div></div> 
                    <a class="btn btn-secondary  btn-sm" href="' . base_url() . 'products/report_product?id=' . $pid . '" target="_blank" title="Reports"> <span class="fa fa-pie-chart"></span> </a> 
                    <a href="' . base_url() . 'products/add?code=' . $pid . '"  class="btn btn-secondary btn-sm"><span class="fa fa-edit"></span></a>
                    <a href="#" data-object-id="' . $pid . '" class="btn btn-secondary btn-sm  delete-object"><span class="fa fa-trash"></span></a>
                    </div>
                </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->products->count_all($catid, '', $sub),
            "recordsFiltered" => $this->products->count_filtered($catid, '', $sub),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    public function addproduct()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        // if($_FILES['upfile'])
        // {            
        //     $product_code = $this->input->post('product_code');
        //     product_files($_FILES['upfile'], $product_code);
        // }
        // echo "<pre>"; print_r($_FILES['upfile']); die(); 
        //if productcode is not empty the the action is update
        $productcode = $this->input->post('productcode', true);
        $product_code = $this->input->post('product_code', true);
       
        $model = $this->input->post('model', true);
        $product_name = $this->input->post('product_name', true);
        $arabic_name = $this->input->post('arabic_name');
        $product_desc = $this->input->post('product_desc', true);
        $selected_categories = $this->input->post('product_cat');
        $made_in = $this->input->post('made_in');
        $manufacturer_id = $this->input->post('manufacturer_id');
        $manufacturer_partno = $this->input->post('manufacturer_partno');
        $brands = $this->input->post('brand_id'); 
        $prefered_vendor = $this->input->post('prefered_vendor');
        $taxrate = numberClean($this->input->post('product_tax', true));

        //Product Pricing section
        $price_unit = $this->input->post('price_unit');
        $unit = $price_unit;         
        // erp2024 newly added section 10-06-2024
        if($this->input->post('kgQuantityCheck', true)==1){
            $kg_quantity = $this->input->post('pieces_per_kg');
            $kgQuantityCheck = 1;
        }
        else{
            $kg_quantity = "0";
            $kgQuantityCheck = "0";
        }   
        $unit_weight = $this->input->post('unit_weight');
        $web_price = $this->input->post('web_price'); 
        $min_price =  $this->input->post('minimum_price');  
        $max_disrate =  $this->input->post('maximum_discount_rate'); 
        $standard_pack = $this->input->post('standard_pack');         
        $wholesale_price = $this->input->post('wholesale_price');
        $factoryprice = numberClean($this->input->post('product_cost'));
        $product_price = numberClean($this->input->post('product_price'));
        $disrate = numberClean($this->input->post('discount_rate', true));


        //inventory section        
        $product_qty = numberClean($this->input->post('onhand_quantity', true));
        $product_qty_alert = numberClean($this->input->post('alert_quantity'));

        //accounting section        
        $income_account_number =  $this->input->post('income_account_number');  
        $expense_account_number =  $this->input->post('expense_account_number'); 
        
        // Location erp2024 newly added section 08-10-2024
        $status  =  $this->input->post('status');  
        $aisel   =  $this->input->post('aisel');  
        $rack_no =  $this->input->post('rack_no');  
        $shelf   =  $this->input->post('shelf');  
        $bin     =  $this->input->post('bin'); 

        //Additional Information section   prd_length      
        $wdate = datefordatabase($this->input->post('wdate'));
        $date_avaialble =  $this->input->post('date_avaialble');  
        $prd_length     =  $this->input->post('prd_length');  
        $prd_width      =  $this->input->post('prd_width');  
        $prd_height     =  $this->input->post('prd_height');  
        $length_class   =  $this->input->post('length_class'); 
        $sku   =  $this->input->post('sku'); 
        $upc   =  $this->input->post('upc'); 
        $ean   =  $this->input->post('ean'); 
        $jan   =  $this->input->post('isbn'); 
        $isbn   =  $this->input->post('isbn');
        

        $basedata = [
            // Basic Info product_desc aisel
            'product_code' => $product_code,
            'model' => $this->input->post('model', true),
            'product_price' => numberClean($this->input->post('product_price')),
            'product_cost' => numberClean($this->input->post('product_cost')),
            'weighted_average_cost' => numberClean($this->input->post('weighted_average_cost')),
            'tax_rate' => numberClean($this->input->post('tax_rate', true)),
            'discount_rate' => numberClean($this->input->post('discount_rate', true)),
            'onhand_quantity' => numberClean($this->input->post('onhand_quantity', true)),
            'alert_quantity' => numberClean($this->input->post('alert_quantity')),
            'unit' => $this->input->post('price_unit'),    
            'sku' => $this->input->post('sku'),
            'upc' => $this->input->post('upc'),
            'ean' => $this->input->post('ean'),
            'jan' => $this->input->post('jan'), 
            'isbn' => $this->input->post('isbn'),
            'manufacturer_id' => $this->input->post('manufacturer_id'),
            'manufacturer_part_number' => $this->input->post('manufacturer_part_number'),
            'made_in' => $this->input->post('made_in'),
            'prefered_vendor' => $this->input->post('prefered_vendor'),
            'expiry_date' => datefordatabase($this->input->post('expiry_date')),    
            'date_avaialble' =>  datefordatabase($this->input->post('date_avaialble')),        
            'unit_weight' => $this->input->post('unit_weight'),
            'kg_quantity' => $this->input->post('kgQuantityCheck', true) == 1 ? $this->input->post('pieces_per_kg') : "0",
            'pieces_per_kg' => $this->input->post('pieces_per_kg'),
            'maximum_discount_rate' => $this->input->post('maximum_discount_rate'),
            'standard_pack' => $this->input->post('standard_pack'),    
            'kgQuantityCheck' => $this->input->post('kgQuantityCheck', true) == 1 ? "1" : "0",            
            'income_account_number' => $this->input->post('income_account_number'),
            'expense_account_number' => $this->input->post('expense_account_number'),
            'created_by' => $this->session->userdata('id'),
            'created_date' => date('Y-m-d H:i:s')
        ];
        
        $barcode_data = [
            'product_code' => $product_code,
            'barcode' => $this->input->post('barcode'),
            'barcode2' => $this->input->post('barcode2'),
            'code_type' => $this->input->post('code_type'),
            'code_type2' => $this->input->post('code_type2')        
        ];
        $location_data = [
            'product_code' => $product_code,
            'aisel' => $this->input->post('aisel'),
            'rack_number' => $this->input->post('rack_number'),
            'shelf_number' => $this->input->post('shelf_number'),
            'bin_number' => $this->input->post('bin_number'),
            'product_length' => $this->input->post('product_length'),
            'product_width' => $this->input->post('product_width'),
            'product_height' => $this->input->post('product_height'),
            'length_class' => $this->input->post('length_class'),
        ];
         


        $pricing_data = [
            'product_code' => $product_code,
            'minimum_price' => $this->input->post('minimum_price'),
            'web_price' => $this->input->post('web_price'),
            'wholesale_price' => $this->input->post('wholesale_price'),
        ];
              

        $prduct_description = [
            'product_code' => $product_code,
            'product_name' => $this->input->post('product_name'),
            'product_description' => $this->input->post('product_description'),
            'arabic_name' => $this->input->post('arabic_name')
        ];
        
        
        if($_FILES['upfile'])
        {
            product_files($_FILES['upfile'], $product_code);
        }
        
        if($productcode)
        {
            $this->db->update('cberp_products', $basedata,['product_code'=>$productcode]);  
            $this->db->update('cberp_product_barcode', $barcode_data,['product_code'=>$productcode]);
            $this->db->update('cberp_product_locations', $location_data,['product_code'=>$productcode]); 
            $this->db->update('cberp_product_pricing', $pricing_data,['product_code'=>$productcode]); 
            $this->db->update('cberp_product_description', $prduct_description,['product_code'=>$productcode]); 
            $this->db->delete('cberp_product_to_category', ['product_code'=>$productcode]); 
            detailed_log_history('product',$productcode,'Updated', $_POST['changedFields']);
        }
        else{
            $this->db->insert('cberp_products', $basedata);  
            $this->db->insert('cberp_product_barcode', $barcode_data);
            $this->db->insert('cberp_product_locations', $location_data); 
            $this->db->insert('cberp_product_pricing', $pricing_data); 
            $this->db->insert('cberp_product_description', $prduct_description); 

            // erp2024 create dummy purchase order master starts
            $latest_id = $this->purchase->lastpurchase();
            $this->load->library("Common");
            $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
            $this->load->model('plugins_model', 'plugins');
            $data['exchange'] = $this->plugins->universal_api(5);
            $data['currencies'] = $this->purchase->currencies();
            $configurations = $this->session->userdata('configurations');
            $currency = get_currency_by_id($configurations['config_currency']);
          
            if($max_disrate>0)
            {
                $pricediscount = $product_price - (($product_price * $max_disrate) / 100);
                $pricediscount = round($pricediscount, 2);
            }
            else{
                $pricediscount = round($max_disrate, 2);
               
            }
            $subtotal = (($product_qty*$product_price)-$pricediscount);
            $subtotal1 = (($product_qty*$factoryprice));
            $defaultsupplier = $this->products->default_supplier();
            $prefix = get_prefix(); 
            $purchase_masater = [
                'purchase_number' => $prefix['po_prefix'].($latest_id+1000),
                'purchase_order_date' => date('Y-m-d H:i:s'),
                'duedate' => date('Y-m-d'),
                'order_total' => $subtotal1,
                'shipping_charge' => 0.00,
                'shipping_tax_type' => 'off',
                'discount' => $pricediscount,
                'shipping_tax' => 0.00,
                'notes' => 'Dummy Purchase Order',
                'payment_status' => 'paid',
                'customer_id' => $defaultsupplier['id'],
                'paid_amount' => 0.00,
                'tax_status' => 'no',
                'discstatus' => 1,
                'payment_terms' => 1,
                'loc' => 1,
                'currency_id' => $currency['id'],
                'purchase_type' => 'Local Cash Purchase',
                'approval_flag' => 1,
                'customer_reference' => null,
                'customer_contact_person' => null,
                'customer_contact_number' => null,
                'customer_contact_email' => null,
                'assigned_to' => $this->session->userdata('id'),
                'created_by' => $this->session->userdata('id'),
                'created_date' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('id'),
                'updated_date' => date('Y-m-d H:i:s'),
                'prepared_by' => $this->session->userdata('id'),
                'prepared_date' => date('Y-m-d H:i:s'),
                'prepared_flag' => '1',
                'approved_by' => $this->session->userdata('id'),
                'approved_date' => date('Y-m-d H:i:s'),
                'sent_by' => $this->session->userdata('id'),
                'sent_date' => date('Y-m-d H:i:s'),
                'order_status' => 'Dummy',
                'receipt_status' => '1',
            ];
           
            $this->db->insert('cberp_purchase_orders',$purchase_masater);
            $purchaseorder_id = $this->db->insert_id();
             
            $purchase_item = [
                'purchase_number' => $prefix['po_prefix'].($latest_id+1000),
                'product_code' => $product_code,
                'quantity' => $product_qty,
                'price' => $factoryprice,
                'tax' => 0.00,
                'discount' => $pricediscount,
                'subtotal' => $subtotal1,
                'totaltax' => 0.00,
                'product_status'=>'1',
                'totaldiscount' => $pricediscount,
                'unit' => $price_unit
            ];
            $this->db->insert('cberp_purchase_order_items',$purchase_item);

  
            // erp2024 create dummy purchase order master  ends
            // erp2024 create dummy purchase reciept master  starts
            $defaultwarhouse = default_warehouse();
            $srvData = $this->products->last_reciept_number();
            $reciept_master = [ 
                'purchase_number' => $prefix['po_prefix'].($latest_id+1000),
                'salepoint_name' => $defaultwarhouse['store_id'],
                'salepoint_name' => $defaultwarhouse['store_name'],
                'purchase_reciept_number' => "Dummy-".$srvData,
                'purchase_receipt_date' => date('Y-m-d'),
                'supplier_id' => $defaultsupplier['id'],
                'bill_number' => 'Dummy'.$srvData,
                'bill_date' => date('Y-m-d'),
                'currency_id' => $currency['id'],
                'currency_rate' => $currency['rate'],
                'purchase_type' => 'Local Cash Purchase',
                'purchase_amount' => $subtotal1,
                'bill_amount' => $subtotal1,
                'cost_factor' => null,
                'payment_date' => date('Y-m-d'),
                'payment_date' => date('Y-m-d'),
                'created_date' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('id'),
                'updated_by' => $this->session->userdata('id'),
                'updated_date' => date('Y-m-d H:i:s'),
                'status' => '1',
                'reciept_status' => 'Received',
                'assigned_to' => $this->session->userdata('id'),
                'prepared_by' => $this->session->userdata('id'),
                'prepared_date' => date('Y-m-d H:i:s'),
                'prepared_flag' => '1',
                'approved_by' => null,
                'approved_date' => date('Y-m-d H:i:s'),
                'approval_flag' => '1',
                'received_by' => $this->session->userdata('id'),
                'received_date' => date('Y-m-d H:i:s'),
                'note' => 'Dummy Purchase Reciept',
            ];
            $this->db->insert('cberp_purchase_receipts',$reciept_master);

            // die($this->db->last_query());
            $reciept_id = $this->db->insert_id();
            $reciept_item = [
                'purchase_reciept_number' => $srvData,
                'product_code' => $product_code,
                'product_quantity_recieved' => $product_qty,
                'ordered_quantity' => $product_qty,
                'product_foc' => null,
                'damaged_quantity' => null,
                'price' => $product_price,
                'saleprice' => $product_price,
                'amount' => $subtotal1,
                'discountperc' => $max_disrate,
                'discountamount' => $pricediscount,
                'netamount' => $subtotal1,
                'qaramount' => $subtotal1,
                'description' => null,
                'status' => '1',  // Default value as per the schema
                'created_date' => date('Y-m-d'),
            ];
            $this->db->insert('cberp_purchase_receipt_items',$reciept_item);
            // erp2024 create dummy purchase reciept master  ends


            $total_price = $factoryprice * $product_qty;
            $average_cost = $total_price/$product_qty;
            $average_cost = round($average_cost, 2);
            $inventory_value = $average_cost * $product_qty;          

            $average_cost_data = array(
                'product_code'             => $product_code,
                'product_cost'           => $factoryprice,
                'transaction_date_time'  => date("Y-m-d H:i:s"),
                'transaction_quantity'   => $product_qty,
                'product_average_cost'   => $average_cost,
                'product_inventory_value'=> $inventory_value,
                'onhand_quantity'        => $product_qty,
                'transaction_type'       => get_costing_transation_type("Purchase"),
                'added_by'               => $this->session->userdata('id')
            );

            $this->db->insert('cberp_average_cost',$average_cost_data);
            $this->db->update('cberp_products',['average_price_table_entry'=>'Yes','product_cost'=>$average_cost],['product_code'=>$product_code]);
            $warehouselist = $this->categories_model->warehouse_list();
            $totalwarehouse = count($warehouselist);
            foreach ($warehouselist as $waredata) {
                $store_id = intval($waredata['store_id']);
                $warehouse_name = trim($waredata['store_name']);
                $stock_quantity = intval($this->input->post('stock_qty_' . $store_id));
                $alert_quantity = intval($this->input->post('alert_qty_' . $store_id));        
                if (!empty($stock_quantity) && $stock_quantity > 0) {
                    $data1 = array(
                        'store_id' => $store_id,
                        'stock_quantity' => $stock_quantity,
                        'alert_quantity' => $alert_quantity,
                        'product_code' => $product_code,
                        'created_by' => $this->session->userdata('id'),
                        'created_date' => date('Y-m-d H:i:s'),
                    );
            
                    //erp2024 newly added function 04-06-2024
                    $this->products->warehose_stock_insert($data1);
                } 
                else {
                    continue;
                }
            }

        }

        if($selected_categories)
        {
            foreach($selected_categories as $category)
            {
                $categorydata['category_id'] = $category;
                $categorydata['product_code']= $product_code;
                $this->db->insert('cberp_product_to_category',$categorydata);
            }
            
        }



           
 
        //  die($this->db->last_query());       
        //  $image = $this->input->post('image');
       
    
            // if($brands)
            // {
            //     $branddata = [];
            //     foreach($brands as $brand1)
            //     {
            //         $branddata['brand_id']=$brand1;
            //         $branddata['product_id']=$product_id;
            //         $this->db->insert('cberp_product_to_brands',$branddata);
            //     }
                
            // }


             echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED')));
         
    }

    //erp2024 old working
   
    public function delete_i()
    {
      //  if ($this->aauth->premission(11)) {
            $id = $this->input->post('deleteid');
            if ($id) {
                // $this->db->delete('cberp_products', array('pid' => $id));
                // $this->db->delete('cberp_products', array('sub' => $id, 'merge' => 1));
                // $this->db->delete('cberp_movers', array('d_type' => 1, 'rid1' => $id));
                // $this->db->set('merge', 0);
                // $this->db->where('sub', $id);
                // $this->db->update('cberp_products');
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        // } else {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         $this->lang->line('ERROR')));
        // }
    }


    
    public function editproduct()
    {
        
        // if (!$this->aauth->premission(14)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }changedFields
        $pid = $this->input->post('pid');
        $product_name = $this->input->post('product_name', true);
        $catid = $this->input->post('product_cat');
        $warehouse = $this->input->post('product_warehouse');
        $product_code = $this->input->post('product_code');
        $made_in = $this->input->post('made_in');
        $product_price = numberClean($this->input->post('product_price'));
        $factoryprice = numberClean($this->input->post('product_cost'));
        $webprice = numberClean($this->input->post('web_price'));
        $taxrate = numberClean($this->input->post('product_tax'));
        $disrate = numberClean($this->input->post('product_disc'));
        $product_qty = numberClean($this->input->post('product_qty'));
        $product_qty_alert = numberClean($this->input->post('product_qty_alert'));
        $product_desc = $this->input->post('product_desc', true);
        $image = $this->input->post('image');
        $unit = $this->input->post('price_unit');
        // $unit = $this->input->post('unit');
        $barcode = $this->input->post('barcode');
        $code_type = $this->input->post('code_type');
        $sub_cat = $this->input->post('sub_cat');
        // if (!$sub_cat) $sub_cat = 0;
        // $brand = $this->input->post('brand');
        $vari = array();
        $vari['v_type'] = $this->input->post('v_type');
        $vari['v_stock'] = $this->input->post('v_stock');
        $vari['v_alert'] = $this->input->post('v_alert');
        $vari['w_type'] = $this->input->post('w_type');
        $vari['w_stock'] = $this->input->post('w_stock');
        $vari['w_alert'] = $this->input->post('w_alert');
        $serial = array();
        $serial['new'] = $this->input->post('product_serial');
        $serial['old'] = $this->input->post('product_serial_e');
		$wdate = datefordatabase($this->input->post('wdate'));
		$wdate =substr($wdate,0,10);
       
        //erp2024 newly added 05-06-2024       
        $min_price = $this->input->post('min_price');    
        
        
          // erp2024 newly added section 08-10-2024 _ai
          $brands = $this->input->post('brand_id'); 
          $status  =  $this->input->post('status');  
          $aisel   =  $this->input->post('aisel');  
          $rack_no =  $this->input->post('rack_no');  
          $shelf   =  $this->input->post('shelf');  
          $bin     =  $this->input->post('bin');    
          $date_avaialble =  $this->input->post('date_avaialble');  
          $prd_length     =  $this->input->post('prd_length');  
          $prd_width      =  $this->input->post('prd_width');  
          $prd_height     =  $this->input->post('prd_height');  
          $length_class   =  $this->input->post('length_class'); 

           
          $income_account_number =  $this->input->post('income_account_number');  
          $expense_account_number =  $this->input->post('expense_account_number'); 
          $barcode2 = $this->input->post('barcode2');
          $code_type2 = $this->input->post('code_type2');
      
          // erp2024 newly added section 08-10-2024

        // $min_price = 0;
        // if(!empty($minprice)){  
        //     $min_price = $minprice;
        // }
        // else if(!empty($factoryprice)){                  
        //     $minprice = $this->products->priceperc_list();
        //     $minprice_perc = $minprice['price_perc'];    
        //     $min_price = (($factoryprice*$minprice_perc)/100)+$factoryprice;
        // }
        // else{
        //     $min_price = 0; 
        // }

        $warehouselist = $this->categories_model->warehouse_list();
        $totalwarehouse = count($warehouselist);
        $this->db->delete('cberp_product_to_store',['product_id'=>intval($pid)]);
        foreach ($warehouselist as $waredata) {
            $store_id = intval($waredata['id']);
            $warehouse_name = trim($waredata['title']);
            $stockunit = intval($this->input->post('stock_qty_' . $store_id));
            $alertunit = intval($this->input->post('alert_qty_' . $store_id));
            if (!empty($stockunit) && $stockunit > 0) {
                
               
                $results = $this->products->warehose_stock_check($pid, $store_id);
                // if(!empty($results)){
                //     $data1 = array(
                //         'store_id' => $store_id,
                //         'stock_qty' => $stockunit,
                //         'alert_qty' => $alertunit,
                //         'product_id' => intval($pid),
                //         'updated_by' => $this->session->userdata('id'),
                //         'updated_dt' => date('Y-m-d H:i:s'),
                //     );
                //     $this->products->warehose_stock_update($data1, $pid, $store_id);
                // }
                // else{
                    $data1 = array(
                        'store_id' => $store_id,
                        'stock_qty' => $stockunit,
                        'alert_qty' => $alertunit,
                        'product_id' => intval($pid),
                        'created_by' => $this->session->userdata('id'),
                        'created_dt' => date('Y-m-d H:i:s'),
                    );
                    $this->products->warehose_stock_insert($data1);
                // }
                //erp2024 newly added function 04-06-2024
            } else {
                continue;
            }

        }
        //erp2024 newly added 05-06-2024  ends  web_price
        $dataai['arabic_name'] = $this->input->post('arabic_name', true);
        $dataai['manufacturer_id'] = $this->input->post('manufacturer_id', true);
        $dataai['made_in'] = $this->input->post('made_in', true);
        $dataai['manufacturer_partno'] = $this->input->post('manufacturer_partno', true);
        $dataai['prefered_vendor'] = $this->input->post('prefered_vendor', true);
        $dataai['unit_weight'] = $this->input->post('unit_weight', true);
        $dataai['wholesale_price'] = $this->input->post('wholesale_price', true);
        $dataai['web_price'] = numberClean($this->input->post('web_price'));
        $dataai['item_cost'] = numberClean($this->input->post('product_cost'));
        $dataai['min_price'] = $min_price;
        $dataai['updated_by'] = $this->session->userdata('id');
        $dataai['updated_dt'] = date("Y-m-d H:i:s");
        $dataai['aisel'] =   $aisel;              
        $dataai['rack_no'] = $rack_no;                 
        $dataai['shelf'] = $shelf;              
        $dataai['bin'] =  $bin;              
        $dataai['date_avaialble'] =  date('Y-m-d',strtotime($date_avaialble));     
        $dataai['prd_length'] = $prd_length;       
        $dataai['prd_width'] = $prd_width;           
        $dataai['prd_height'] = $prd_height;          
        $dataai['length_class'] =  $length_class; 
        $dataai['expense_account_number'] =  $expense_account_number; 
        $dataai['income_account_number'] =  $income_account_number; 
         // erp2024 newly added section 11-06-2024 price_unit
         if($this->input->post('kgQuantityCheck', true)==1){
            $dataai['kgQuantityCheck']=1;
            $dataai['pieces_per_kg']= $this->input->post('pieces_per_kg');
        }
        else{
            $dataai['pieces_per_kg']="0";
            $dataai['kgQuantityCheck']="0";
        }        
        $dataai['standard_pack'] = $this->input->post('standard_pack');         
        $dataai['wholesale_price'] = $this->input->post('wholesale_price');
        $dataai['price_unit'] = $this->input->post('price_unit');
        $dataai['max_disrate'] = numberClean($this->input->post('max_disrate'));

        
         // erp2024 newly added section 10-06-2024
        
        if ($pid) {

            $this->db->where('product_id', $pid);
            $this->db->update('cberp_product_ai',$dataai);
            $this->db->delete('cberp_product_to_category',['product_id'=>$pid]);
            $this->db->delete('cberp_product_to_brands',['product_id'=>$pid]);
            $this->db->delete('cberp_product_to_subcategory',['product_id'=>$pid]);
            //erp2024 08-10-2024
            if($catid)
            {
                $categorydata = [];
                foreach($catid as $category)
                {
                    $categorydata['category_id']=$category;
                    $categorydata['product_id']=intval($pid);
                    $this->db->insert('cberp_product_to_category',$categorydata);
                }
                
            }
            if($brands)
            {
                $branddata = [];
                foreach($brands as $brand1)
                {
                    $branddata['brand_id']=$brand1;
                    $branddata['product_id']=intval($pid);
                    $this->db->insert('cberp_product_to_brands',$branddata);
                }
                
            }

            if($sub_cat)
            {
                $subcatdata = [];
                foreach($sub_cat as $sub)
                {
                    $subcatdata['subcategory_id']=$sub;
                    $subcatdata['product_id']=intval($pid);
                    $subcatdata['category_id']=$this->products->category_bysubcatid($sub);
                    $this->db->insert('cberp_product_to_subcategory',$subcatdata);
                }
                
            }
            // file upload section starts 25-01-2025
            if($_FILES['upfile'])
            {
                upload_files($_FILES['upfile'], 'Products',$pid);
            }
            // file upload section ends 25-01-2025

            history_table_log('cberp_products_log','product_id',$pid,'Update');
             //erp2024 07-01-2025 detailed history log starts
             detailed_log_history('product',$pid,'Updated', $_POST['changedFields']);
              //erp2024 07-01-2025 detailed history log ends 
             //********* */
            $this->products->edit($pid, $warehouse, $product_name, $product_code, $product_price, $factoryprice,$web_price, $taxrate, $disrate, $product_qty, $product_qty_alert,  $product_desc, $image, $unit, $barcode, $code_type,  $vari, $serial,$wdate,$status,$barcode2,$code_type2); 
        }
    }

    //erp2024 new function for stock remove from a shop or warehouse
    public function delstockfromwarehouse(){
        $product_code = $this->input->post('product_code', true);
        $store_id = $this->input->post('warehouseID', true);
        $stock_quantity = $this->input->post('stock_qty', true);
        $alert_qty = $this->input->post('alert_qty', true);
        $product_qty = $this->input->post('product_qty', true);
        $product_qty_alert = $this->input->post('product_qty_alert', true);
        $data1 = array(
            'stock_quantity' => 0,
            'alert_quantity' => 0,
            'updated_by' => $this->session->userdata('id'),
            'updated_date' => date('Y-m-d H:i:s'),
        );
        $this->products->warehose_stock_update($data1, $product_code, $store_id);
        // $maindata = array(
        //     'qty' => $product_qty,
        //     'alert' => $product_qty_alert
        // );
        // $this->db->where('pid', $productID);
        // $this->db->update('cberp_products',$maindata);
        echo json_encode(array('status' => 'Success'));
    }
    //erp2024 new function for stock remove from a shop or warehouse 05-06-2024
    public function warehouseproduct_list()
    {
        $catid = $this->input->get('id');
        $list = $this->products->get_datatables($catid, true);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $row[] = $no;
            $pid = $prd->product_code;
            $row[] = $prd->product_name;
            $row[] = $prd->product_code;
            $row[] = $prd->c_title;
            $row[] = +$prd->qty;
            $row[] = amountExchange($prd->product_price, 0, $this->aauth->get_user()->loc);
            $row[] = '<a href="' . base_url() . 'products/add?code=' . $pid . '" class="btn btn-secondary btn-sm" title="Edit" target="_blank"><span class="fa fa-pencil"></span></a> <a href="#" data-object-id="' . $pid . '" class="btn btn-secondary btn-sm  delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->products->count_all($catid, true),
            "recordsFiltered" => $this->products->count_filtered($catid, true),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function prd_stats()
    {
        $this->products->prd_stats();
    }

    public function stock_transfer_products()
    {
        $wid = $this->input->post('wid');
        $customer = $this->input->post('product');
        $terms = @$customer['term'];
        // erp2024 removed function 12-06-2024
        // $result = $this->products->products_list($wid, $terms);
        // erp2024 removed function 12-06-2024 ends
        // erp2024 new function 12-06-2024 
        $result = $this->products->products_list_by_warehouse($wid, $terms);
        echo json_encode($result);
    }


    public function sub_cat()
    {
        $wid = $this->input->get('id');
        $string = $this->input->post('product');


        if(isset($string['term'])) $this->db->like('title', $string['term']);
        $this->db->from('cberp_product_category');
        $wid_array = explode(',', $wid);
        $this->db->where_in('rel_id', $wid_array);
        $this->db->where('c_type', 1);
        $this->db->order_by('title','ASC');
        $query = $this->db->get();
        $result = $query->result_array();


        echo json_encode($result);
    }

    public function stock_transfer()
    {
        $data['permissions'] = load_permissions('Stock','Stock Transfer','New Stock Transfer');
        if ($this->input->post()) {
            $products_l = $this->input->post('products_id');
            // $products_l = $this->input->post('products_l');
            $from_warehouse = $this->input->post('from_warehouse');
            $to_warehouse = $this->input->post('to_warehouse');
            $qty = $this->input->post('products_qty');
            //erp2024 12-06-2024 new function
            // $this->products->transfer($from_warehouse, $products_l, $to_warehouse, $qty);
            // erp2024 newly added function for transfer
            $this->products->stock_transfer($from_warehouse, $products_l, $to_warehouse, $qty);
        } else {
            $data['cat'] = $this->categories_model->category_list();
            $data['warehouse'] = $this->categories_model->warehouse_list();
            $head['title'] = "Stock Transfer";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/stock_transfer', $data);
            $this->load->view('fixed/footer');
        }
    }


    public function file_handling()
    {
        if ($this->input->get('op')) {
            $name = $this->input->get('name');
            if ($this->products->meta_delete($name)) {
                echo json_encode(array('status' => 'Success'));
            }
        } else {
            $id = $this->input->get('id');
            $this->load->library("Uploadhandler_generic", array(
                'accept_file_types' => '/\.(gif|jpe?g|png)$/i',
				'upload_dir' => FCPATH . 'userfiles/product/',
				'upload_url' => base_url() . 'userfile/product/',
				'max_width'=>400,
				'max_height'=>400
            ));
        }
    }

    public function barcode()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $pid = 2;
        // 277712075583
        // 8448142619591
        // $pid = $this->input->get('id');
        if ($pid) {
            $this->db->select('product_name,barcode,code_type');
            $this->db->from('cberp_products');
            //  $this->db->where('warehouse', $warehouse);
            $this->db->where('pid', $pid);
            $query = $this->db->get();
            $resultz = $query->row_array();
            $data['name'] = $resultz['product_name'];
            $data['code'] = $resultz['barcode'];
            $data['ctype'] = $resultz['code_type'];
            $html = $this->load->view('barcode/view', $data, true);
            ini_set('memory_limit', '64M');

            //PDF Rendering
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($data['name'] . '_barcode.pdf', 'I');
        }
    }

    public function posbarcode()
    {
        $pid = $this->input->get('id');
        if ($pid) {
            $this->db->select('product_name,barcode,code_type');
            $this->db->from('cberp_products');
            //  $this->db->where('warehouse', $warehouse);
            $this->db->where('pid', $pid);
            $query = $this->db->get();
            $resultz = $query->row_array();
            $data['name'] = $resultz['product_name'];
            $data['code'] = $resultz['barcode'];
            $data['ctype'] = $resultz['code_type'];
            $html = $this->load->view('barcode/posbarcode', $data, true);
            ini_set('memory_limit', '64M');

            //PDF Rendering
            $this->load->library('pdf');
            $pdf = $this->pdf->load_thermal();
            $pdf->WriteHTML($html);
            $pdf->Output($data['name'] . '_barcode.pdf', 'I');

        }
    }

    // public function view_over()
    // {
    //     $pid = $this->input->post('id');
    //     $this->db->select('cberp_products.*,cberp_store.title');
    //     $this->db->from('cberp_products');
    //     $this->db->where('cberp_products.pid', $pid);
    //     $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse');
    //     if ($this->aauth->get_user()->loc) {
    //         $this->db->group_start();
    //         $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
    //         if (BDATA) $this->db->or_where('cberp_store.loc', 0);
    //         $this->db->group_end();
    //     } elseif (!BDATA) {
    //         $this->db->where('cberp_store.loc', 0);
    //     }

    //     $query = $this->db->get();
    //     $data['product'] = $query->row_array();

    //     $this->db->select('cberp_products.*,cberp_store.title');
    //     $this->db->from('cberp_products');
    //     $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse');
    //     if ($this->aauth->get_user()->loc) {
    //         $this->db->group_start();
    //         $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
    //         if (BDATA) $this->db->or_where('cberp_store.loc', 0);
    //         $this->db->group_end();
    //     } elseif (!BDATA) {
    //         $this->db->where('cberp_store.loc', 0);
    //     }
    //     $this->db->where('cberp_products.merge', 1);
    //     $this->db->where('cberp_products.sub', $pid);
    //     $query = $this->db->get();
    //     $data['product_variations'] = $query->result_array();

    //     $this->db->select('cberp_products.*,cberp_store.title');
    //     $this->db->from('cberp_products');
    //     $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse');
    //     if ($this->aauth->get_user()->loc) {
    //         $this->db->group_start();
    //         $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
    //         if (BDATA) $this->db->or_where('cberp_store.loc', 0);
    //         $this->db->group_end();
    //     } elseif (!BDATA) {
    //         $this->db->where('cberp_store.loc', 0);
    //     }
    //     $this->db->where('cberp_products.sub', $pid);
    //     $this->db->where('cberp_products.merge', 2);
    //     $query = $this->db->get();
    //     $data['product_warehouse'] = $query->result_array();


    //     $this->load->view('products/view-over', $data);


    // }

    //erp2024 new modification 05-06-2024
    public function view_over()
    {
        $pid = $this->input->post('id');
        $this->db->select('cberp_products.*');
        $this->db->from('cberp_products');
        $this->db->where('cberp_products.pid', $pid);
        // $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('cberp_store.loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_store.loc', 0);
        // }

        $query = $this->db->get();
        $data['product'] = $query->row_array();

        // $this->db->select('cberp_products.*');
        // $this->db->from('cberp_products');
        // $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('cberp_store.loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_store.loc', 0);
        // }
        // $this->db->where('cberp_products.merge', 1);
        // $this->db->where('cberp_products.sub', $pid);
        // $query = $this->db->get();
        // $data['product_variations'] = $query->result_array();

        // $this->db->select('cberp_products.*');
        // $this->db->from('cberp_products');
        // $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('cberp_store.loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_store.loc', 0);
        // }
        // $this->db->where('cberp_products.sub', $pid);
        // $this->db->where('cberp_products.merge', 2);
        // $query = $this->db->get();
        // $data['product_warehouse'] = $query->result_array();


        $this->load->view('products/view-over', $data);


    }
    //erp2024 new modification 05-06-2024 ends

    public function label()
    {
        $pid = $this->input->get('id');
        if ($pid) {
            $this->db->select('product_name,product_price,product_code,barcode,expiry,code_type');
            $this->db->from('cberp_products');
            //  $this->db->where('warehouse', $warehouse);
            $this->db->where('pid', $pid);
            $query = $this->db->get();
            $resultz = $query->row_array();

            $html = $this->load->view('barcode/label', array('lab' => $resultz), true);
            ini_set('memory_limit', '64M');

            //PDF Rendering
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($resultz['product_name'] . '_label.pdf', 'I');

        }
    }


    public function poslabel()
    {
        $pid = $this->input->get('id');
        if ($pid) {
            $this->db->select('product_name,product_price,product_code,barcode,expiry,code_type');
            $this->db->from('cberp_products');
            //  $this->db->where('warehouse', $warehouse);
            $this->db->where('pid', $pid);
            $query = $this->db->get();
            $resultz = $query->row_array();
            $html = $this->load->view('barcode/poslabel', array('lab' => $resultz), true);
            ini_set('memory_limit', '64M');
            //PDF Rendering
            $this->load->library('pdf');
            $pdf = $this->pdf->load_thermal();
            $pdf->WriteHTML($html);
            $pdf->Output($resultz['product_name'] . '_label.pdf', 'I');
        }
    }

    public function report_product()
    {
        $pid = intval($this->input->post('id'));

        $r_type = intval($this->input->post('r_type'));
        $s_date = datefordatabase($this->input->post('s_date'));
        $e_date = datefordatabase($this->input->post('e_date'));

        if ($pid && $r_type) {


            switch ($r_type) {
                case 1 :
                    $query = $this->db->query("SELECT cberp_invoices.tid,cberp_invoice_items.qty,cberp_invoice_items.price,cberp_invoices.invoicedate FROM cberp_invoice_items LEFT JOIN cberp_invoices ON cberp_invoices.id=cberp_invoice_items.tid WHERE cberp_invoice_items.pid='$pid' AND cberp_invoices.status!='canceled' AND (DATE(cberp_invoices.invoicedate) BETWEEN DATE('$s_date') AND DATE('$e_date'))");
                    $result = $query->result_array();
                    break;

                case 2 :
                    $query = $this->db->query("SELECT cberp_purchase_orders.tid,cberp_purchase_order_items.qty,cberp_purchase_order_items.price,cberp_purchase_orders.invoicedate FROM cberp_purchase_order_items LEFT JOIN cberp_purchase_orders ON cberp_purchase_orders.id=cberp_purchase_order_items.tid WHERE cberp_purchase_order_items.pid='$pid' AND cberp_purchase_orders.status!='canceled' AND (DATE(cberp_purchase_orders.invoicedate) BETWEEN DATE('$s_date') AND DATE('$e_date'))");
                    $result = $query->result_array();
                    break;

                case 3 :
                    $query = $this->db->query("SELECT rid2 AS qty, DATE(d_time) AS  invoicedate,note FROM cberp_movers  WHERE cberp_movers.d_type='1' AND rid1='$pid'  AND (DATE(d_time) BETWEEN DATE('$s_date') AND DATE('$e_date'))");
                    $result = $query->result_array();
                    break;
            }

            $this->db->select('*');
            $this->db->from('cberp_products');
            $this->db->where('pid', $pid);
            $query = $this->db->get();
            $product = $query->row_array();

            $cat_ware = $this->categories_model->cat_ware($pid, $this->aauth->get_user()->loc);

            //if(!$cat_ware) exit();
            $html = $this->load->view('products/statementpdf-ltr', array('report' => $result, 'product' => $product, 'cat_ware' => $cat_ware, 'r_type' => $r_type), true);
            ini_set('memory_limit', '64M');

            //PDF Rendering
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($pid . 'report.pdf', 'I');
        } else {
            $pid = intval($this->input->get('id'));
            $this->db->select('*');
            $this->db->from('cberp_products');
            $this->db->where('pid', $pid);
            $query = $this->db->get();
            $product = $query->row_array();
            $head['title'] = "Product Sales";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/statement', array('id' => $pid, 'product' => $product));
            $this->load->view('fixed/footer');
        }
    }

    public function custom_label()
    {
        $data['permissions'] = load_permissions('Stock','Products Label','Custom Label');
        if ($this->input->post()) { 
            require APPPATH . 'third_party/barcode/autoload.php';
            $width = $this->input->post('width');
            $height = $this->input->post('height');
            $padding = $this->input->post('padding');
            $store_name = $this->input->post('store_name');
            $warehouse_name = $this->input->post('warehouse_name');
            $product_price = $this->input->post('product_price');
            $product_code = $this->input->post('product_code');
            $bar_height = $this->input->post('bar_height');
            $bar_width = $this->input->post('bar_width');
            $label_width = $this->input->post('label_width');
            $label_height = $this->input->post('label_height');
            $product_name = $this->input->post('product_name');
            $font_size = $this->input->post('font_size');
            $max_char = $this->input->post('max_char');
            $b_type = $this->input->post('b_type');
            $total_rows = $this->input->post('total_rows');
            $items_per_rows = $this->input->post('items_per_row');
            $product_id = $this->input->post('product_id');
            $product_id_array = explode(',', $product_id);
            $from_warehouse = $this->input->post('from_warehouse');
            $code_type = $this->input->post('code_type');
            $products = array();
            // if(!$this->input->post('products_l')) exit('No Product Selected!');
            // foreach ($this->input->post('products_l') as $row) {
                // $this->db->select('cberp_products.product_name,cberp_products.product_price,cberp_products.product_code,cberp_products.barcode,cberp_products.expiry,cberp_products.code_type,cberp_store.title,cberp_store.loc');
                // $this->db->from('cberp_products');
                // $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse', 'left');

                //  $this->db->where('warehouse', $warehouse);
                // $this->db->where_in('cberp_products.pid', $product_id);
                // $query = $this->db->get();
                // $products[] = $query->result_array();

                //old section
                // $resultz = $query->row_array();
                // $products[] = $resultz;

            // }
            $this->db->select('
                cberp_products.product_name, 
                cberp_products.product_price, 
                cberp_products.product_code, 
                CASE
                    WHEN cberp_products.code_type = ' . $this->db->escape($code_type) . ' THEN cberp_products.barcode
                    WHEN cberp_products.code_type2 = ' . $this->db->escape($code_type) . ' THEN cberp_products.barcode2
                    ELSE NULL
                END AS selected_barcode, 
                cberp_products.expiry, 
                cberp_products.code_type, 
                cberp_store.title, 
                cberp_store.loc
            ');
            $this->db->from('cberp_products');
            $this->db->join('cberp_product_to_store', 'cberp_product_to_store.product_id = cberp_products.pid', 'left');
            $this->db->join('cberp_store', 'cberp_store.id = cberp_product_to_store.store_id', 'left');

            // Filter by warehouse ID
            $this->db->where('cberp_product_to_store.store_id', $from_warehouse);

            // Filter by product IDs (array input)
            $this->db->where_in('cberp_products.pid', $product_id_array);

            // Filter by code type
            $this->db->group_start(); // Start grouping OR conditions
            $this->db->where('cberp_products.code_type', $code_type);
            $this->db->or_where('cberp_products.code_type2', $code_type);
            $this->db->group_end(); // End grouping OR conditions
            $query = $this->db->get();


            $products = $query->result_array(); 
            // echo "<pre>"; echo $this->db->last_query(); print_r($products);
            // ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);

            $loc = location($resultz['loc']);


            $design = array('store' => $loc['cname'], 'warehouse' => $resultz['title'], 'width' => $width, 'height' => $height, 'padding' => $padding, 'store_name' => $store_name, 'warehouse_name' => $warehouse_name, 'product_price' => $product_price, 'product_code' => $product_code, 'bar_height' => $bar_height, 'total_rows' => $total_rows, 'items_per_row' => $items_per_rows, 'bar_width' => $bar_width, 'label_width' => $label_width, 'label_height' => $label_height, 'product_name' => $product_name, 'font_size' => $font_size, 'max_char' => $max_char, 'b_type' => $b_type);


            $this->load->view('barcode/custom_label', array('products' => $products, 'style' => $design));

            
            // $html = $this->load->view('barcode/custom_label', array('products' => $products, 'style' => $design), true);
            ini_set('memory_limit', '64M');

                        //PDF Rendering
                        // $this->load->library('pdf');
                        // $pdf = $this->pdf->load_en();
                        // $pdf->WriteHTML($html);
                        // $pdf->Output($resultz['product_name'] . '_label.pdf', 'I');
            

        } else {
            $data['cat'] = $this->categories_model->category_list();
            $data['warehouse'] = $this->categories_model->warehouse_list();
            $head['title'] = "Custom Label";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/custom_label', $data);
            $this->load->view('fixed/footer');
        }
    }

    public function custom_label_old()
    {
        if ($this->input->post()) {
            $width = $this->input->post('width');
            $height = $this->input->post('height');
            $padding = $this->input->post('padding');
            $store_name = $this->input->post('store_name');
            $warehouse_name = $this->input->post('warehouse_name');
            $product_price = $this->input->post('product_price');
            $product_code = $this->input->post('product_code');
            $bar_height = $this->input->post('bar_height');
            $total_rows = $this->input->post('total_rows');
            $items_per_rows = $this->input->post('items_per_row');
            $products = array();


            foreach ($this->input->post('products_l') as $row) {
                $this->db->select('cberp_products.product_name,cberp_products.product_price,cberp_products.product_code,cberp_products.barcode,cberp_products.expiry,cberp_products.code_type,cberp_store.title,cberp_store.loc');
                $this->db->from('cberp_products');
                $this->db->join('cberp_store', 'cberp_store.id = cberp_products.warehouse', 'left');

                // if ($this->aauth->get_user()->loc) {
                //     $this->db->group_start();
                //     $this->db->where('cberp_store.loc', $this->aauth->get_user()->loc);

                //     if (BDATA) $this->db->or_where('cberp_store.loc', 0);
                //     $this->db->group_end();
                // } elseif (!BDATA) {
                //     $this->db->where('cberp_store.loc', 0);
                // }

                //  $this->db->where('warehouse', $warehouse);
                $this->db->where('cberp_products.pid', $row);
                $query = $this->db->get();
                $resultz = $query->row_array();

                $products[] = $resultz;

            }


            $loc = location($resultz['loc']);

            $design = array('store' => $loc['cname'], 'warehouse' => $resultz['title'], 'width' => $width, 'height' => $height, 'padding' => $padding, 'store_name' => $store_name, 'warehouse_name' => $warehouse_name, 'product_price' => $product_price, 'product_code' => $product_code, 'bar_height' => $bar_height, 'total_rows' => $total_rows, 'items_per_row' => $items_per_rows);


            $html = $this->load->view('barcode/custom_label', array('products' => $products, 'style' => $design), true);
            ini_set('memory_limit', '64M');

            //PDF Rendering
            $this->load->library('pdf');
            $pdf = $this->pdf->load_en();
            $pdf->WriteHTML($html);
            $pdf->Output($resultz['product_name'] . '_label.pdf', 'I');


        } else {
            $data['cat'] = $this->categories_model->category_list();
            $data['warehouse'] = $this->categories_model->warehouse_list();
            $head['title'] = "Custom Label";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/custom_label', $data);
            $this->load->view('fixed/footer');
        }
    }

    public function standard_label()
    {   
       
        $data['permissions'] = load_permissions('Stock','Products Label','Standard Label');
        $data['cat'] = $this->categories_model->category_list();
        $data['warehouse'] = $this->categories_model->warehouse_list();
        if ($this->input->post()) {
            $width = $this->input->post('width');
            $height = $this->input->post('height');
            $padding = $this->input->post('padding');
            $store_name = $this->input->post('store_name');
            $warehouse_name = $this->input->post('warehouse_name');
            $product_price = $this->input->post('product_price');
            $product_code = $this->input->post('product_code');
            $bar_height = $this->input->post('bar_height');
            $total_rows = $this->input->post('total_rows');
            $items_per_rows = $this->input->post('items_per_row');
            $standard_label = $this->input->post('standard_label');
            $product_id = $this->input->post('product_id');
            $product_id_array = explode(',', $product_id);
            $from_warehouse = $this->input->post('from_warehouse');
            $code_type = $this->input->post('code_type');
            $products = array();
            
            // $this->db->select('
            //     cberp_products.product_name, 
            //     cberp_products.product_price, 
            //     cberp_products.product_code, 
            //     cberp_products.barcode, 
            //     cberp_products.expiry, 
            //     cberp_products.code_type, 
            //     cberp_store.title, 
            //     cberp_store.loc
            // ');
            // $this->db->from('cberp_products');
            // $this->db->join('cberp_product_to_store', 'cberp_product_to_store.product_id = cberp_products.pid', 'left');
            // $this->db->join('cberp_store', 'cberp_store.id = cberp_product_to_store.store_id', 'left');
            // $this->db->where('cberp_product_to_store.store_id', $from_warehouse);
            // $this->db->where_in('cberp_products.pid', $product_id_array);

            // $query = $this->db->get();

            $this->db->select('
                cberp_products.product_name, 
                cberp_products.product_price, 
                cberp_products.product_code, 
                CASE
                    WHEN cberp_products.code_type = ' . $this->db->escape($code_type) . ' THEN cberp_products.barcode
                    WHEN cberp_products.code_type2 = ' . $this->db->escape($code_type) . ' THEN cberp_products.barcode2
                    ELSE NULL
                END AS selected_barcode, 
                cberp_products.expiry, 
                cberp_products.code_type, 
                cberp_store.title, 
                cberp_store.loc
            ');
            $this->db->from('cberp_products');
            $this->db->join('cberp_product_to_store', 'cberp_product_to_store.product_id = cberp_products.pid', 'left');
            $this->db->join('cberp_store', 'cberp_store.id = cberp_product_to_store.store_id', 'left');

            // Filter by warehouse ID
            $this->db->where('cberp_product_to_store.store_id', $from_warehouse);

            // Filter by product IDs (array input)
            $this->db->where_in('cberp_products.pid', $product_id_array);

            // Filter by code type
            $this->db->group_start(); // Start grouping OR conditions
            $this->db->where('cberp_products.code_type', $code_type);
            $this->db->or_where('cberp_products.code_type2', $code_type);
            $this->db->group_end(); // End grouping OR conditions
            $query = $this->db->get();


            $products = $query->result_array();

            $loc = location($resultz['loc']);

            $design = array('store' => $loc['cname'], 'warehouse' => $resultz['title'], 'width' => $width, 'height' => $height, 'padding' => $padding, 'store_name' => $store_name, 'warehouse_name' => $warehouse_name, 'product_price' => $product_price, 'product_code' => $product_code, 'bar_height' => $bar_height, 'total_rows' => $total_rows, 'items_per_row' => $items_per_rows);

            // echo "<pre>"; print_r($products); print_r($design);
            // ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);

            switch ($standard_label) {
                case 'eu30019' :
                    $html = $this->load->view('standard_label/eu30019', array('products' => $products, 'style' => $design), true);
                    break;
            }
            
            ini_set('memory_limit', '64M');
            
            $this->load->library('pdf');
            $pdf = $this->pdf->load_en();
            $pdf->WriteHTML($html);
            $pdf->Output($resultz['product_name'] . '_label.pdf', 'D');
            
            header("Location: " . $_SERVER['REQUEST_URI']);
            $this->load->view('fixed/header', $head);
            $this->load->view('products/standard_label', $data);
            $this->load->view('fixed/footer');
     
            // echo "<script>window.location.href = window.location.href;</script>";
           
        } else {
  
            $head['title'] = "Standard Label";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('products/standard_label', $data);
            $this->load->view('fixed/footer');
        }
    }

    //erp2024 products by location
    public function productsbylocation()
    {
        $head['title'] = "Products by location";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/products-by-location');
        $this->load->view('fixed/footer');
    }
    public function warehouse_by_productid()
    {
        $results = $this->products->warehouse_by_productid($this->input->post('prdid'), $this->input->post('mainwarehouse'));
        $options = [];
        if (!empty($results)) {
            foreach ($results as $result) {
                $options[] = [
                    'id' => $result['id'],
                    'title' => $result['title'],
                    'stock_qty' => $result['stock_qty'],
                ];
            }
        }
        echo json_encode($options);
    }
    public function products_by_id()
    {
        $salesPro=$this->input->post('selectedProducts');
        $productIds = explode(",",$salesPro);
        $results = $this->products->products_list_by_id($productIds);
        $options = [];
        if (!empty($results)) {
            foreach ($results as $result) {
                $options[] = [
                    'id' => $result['pid'],
                    'title' => $result['product_name'],
                    'code' => $result['product_code'],
                ];
            }
        }
        echo json_encode($options);
       
    }

    public function locationwiseproducts() {
        $product_code = $this->input->post('product_code');
        $results = $this->products->locationwiseproducts($product_code);
        $response = [];
        $result = [];

        $total_purchse_quantity = $this->products->get_total_purchse_quantity($product_code);
        $total_sales_quantity = $this->products->get_total_sales_quantity($product_code);
        $onhand = 0;
        if (!empty($results)) {
            foreach ($results as $row) {
                $title = $row['title'];
                $stock_qty = $row['stock_quantity'];
                $alert_qty = $row['alert_quantity'];
                if(!empty($stock_qty)){
                    $onhand = $onhand+$stock_qty;
                }
                // $unit = $row['unit'];
                $productname = $row['product_name'];
                $productcode = $row['product_code'];

                $response[] = [
                    'title' => $title,
                    'stock_qty' => $stock_qty,
                    'alert_qty' => $alert_qty,
                    // 'unit' => $unit,
                ];
            }
        }
        $result['stocks'] = $response;
        $result['total_purchse_quantity'] = (!empty($total_purchse_quantity))?$total_purchse_quantity:'0';
        $result['total_sales_quantity'] = (!empty($total_sales_quantity))?$total_sales_quantity:'0';
        $result['onhand'] = $onhand;
        $result['baseunit'] = $unit;
        $result['productname'] = $productname;
        $result['productcode'] = $productcode;
        echo json_encode($result);
    }

    public function locationwiseproductsbyid() {
        $prdid = $this->input->get('id');
        $results = $this->products->locationwiseproducts($prdid);
        $response = [];
        $result = [];
        $total_purchse_quantity = $this->products->get_total_purchse_quantity($prdid);
        $total_sales_quantity = $this->products->get_total_sales_quantity($prdid);
        $onhand = 0;
        if (!empty($results)) {
            foreach ($results as $row) {
                $title = $row['title'];
                $stock_qty = $row['stock_qty'];
                $alert_qty = $row['alert_qty'];
                if(!empty($stock_qty)){
                    $onhand = $onhand+$stock_qty;
                }
                $unit = $row['unit'];
                $productname = $row['product_name'];
                $productcode = $row['product_code'];

                $response[] = [
                    'title' => $title,
                    'stock_qty' => $stock_qty,
                    'alert_qty' => $alert_qty,
                    'unit' => $unit,
                ];
            }
        }
        $result['stocks'] = $response;
        $result['total_purchse_quantity'] = (!empty($total_purchse_quantity))?$total_purchse_quantity:'0';
        $result['total_sales_quantity'] = (!empty($total_sales_quantity))?$total_sales_quantity:'0';
        $result['onhand'] = $onhand;
        $result['baseunit'] = $unit;
        $result['productname'] = $productname;
        $result['productcode'] = $productcode;
        echo json_encode($result);
    }
    
    public function priceperclist(){
        $minprice = $this->products->priceperc_list();
        $data = [];
        $data['min_price_prec'] = (!empty($minprice['price_perc']))?$minprice['price_perc']:1;
        $data['selling_price_perc'] = (!empty($minprice['selling_price_perc']))?$minprice['selling_price_perc']:1;
        $data['whole_price_perc'] = (!empty($minprice['whole_price_perc']))?$minprice['whole_price_perc']:1;
        $data['web_price_perc'] = (!empty($minprice['web_price_perc']))?$minprice['web_price_perc']:1;
        echo json_encode($data);
    }
    //erp2024 new functions ends

    //erp2024 function for bulk products upload 03-07-2024 starts
    public function product_imports()
    {
        
        $head['title'] = "Product bulk upload";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/bulkuploads');
        $this->load->view('fixed/footer');
    }
    public function import() {
        $file_path = $_FILES['csv_file']['tmp_name'];    

        if (($handle = fopen($file_path, "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ","); // Get the first row, which contains the column headers
            $data = [];
            
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row_data = array();
                foreach ($header as $key => $heading) {
                    $row_data[$heading] = $row[$key];
                }
                $data[] = $row_data;
            }
            fclose($handle);
            echo "<pre>"; print_r($data); die();

            
            if(!empty($data)) {
                $this->Product_model->insert_products($data);
                $this->session->set_flashdata('message', 'CSV Data Imported Successfully');
            } else {
                $this->session->set_flashdata('message', 'No data to import');
            }
        } else {
            $this->session->set_flashdata('message', 'Unable to open the file');
        }
        redirect(base_url('csv_import'));
    }
    //erp2024 function for bulk products upload 03-07-2024 ends

    public function product_details()
    {
        $pid = $this->input->post('product_id');
        $data = $this->products->get_product_details($pid);
        echo json_encode(array('status' => 'Success', 'data'=>$data));

    }


    public function product_warehousewise_stock()
    {
        $pid = $this->input->post('product_id');
        $result = $this->products->product_warehousewise_stock($pid);

        $tableContent = "";
        if (!empty($result)) {
            foreach ($result as $data) {
                $tableContent .= '<tr><td>' . $data['title'] . '</td><td>' . $data['stock_qty'] . '</td></tr>';
            }
        }

        echo json_encode(array('status' => 'Success', 'data' => $tableContent));
    }

    public function product_cost()
    {
        $pid = $this->input->post('product_id');
        $data = $this->products->get_product_cost($pid);
        echo json_encode(array('status' => 'Success', 'data'=>$data));

    }

    //erp2024 07-10-2024
    public function product_cost_update()
    {
        $pid = $this->input->post('pid');
        $cost = $this->input->post('cost');
        $pricepercentages  = $this->products->priceperc_list();
        $min_price_prec = $pricepercentages['price_perc'];
        $selling_price_perc = $pricepercentages['selling_price_perc'];
        $whole_price_perc = $pricepercentages['whole_price_perc'];
        $web_price_perc = $pricepercentages['web_price_perc'];
        $minprice = (($cost*$min_price_prec)/100) + $cost;
        $selling_price = (($cost*$selling_price_perc)/100) + $cost;
        $web_price = (($cost*$web_price_perc)/100) + $cost;
        $whole_price = (($cost*$whole_price_perc)/100) + $cost;
        
        $data = [
            'min_price'=>$minprice,
            'web_price'=>$web_price,
            'wholesale_price'=>$whole_price,
            'item_cost'=>$cost
        ];
        $this->db->where('product_id', $pid);
        $this->db->update('cberp_product_ai',$data,);
        $mainproduct = [
            'product_price'=>$selling_price,
            'product_cost'=>$cost
        ];
        $this->db->where('pid', $pid);
        $this->db->update('cberp_products',$mainproduct);

        $changedFields = json_encode([
            [
                'fieldlabel' => $this->input->post('productname')."(".$this->input->post('product_code').")",
                'field_name' => "Cost Update", 
                'oldValue' => $this->input->post('old_cost'),
                'newValue' => $cost
            ]
        ]);
        
        detailed_log_history('Purchasereceipt',$this->input->post('receipt_id'),'Cost Updated', $changedFields);
        echo json_encode(array('status' => 'Success'));

    }

    public function category_list()
    {
        $category_list = $this->categories_model->category_list();
        $catoption = "";
        if(!empty($category_list))
        {
            foreach($category_list as $row){
                $catoption .= "<option value='".$row['id']."'>".$row['title']."</option>";
            }
        }
        echo json_encode(array('data' => $catoption));
    }
    public function brand_list()
    {
        $category_list = $this->products->brand_list();
        $catoption = "";
        if(!empty($category_list))
        {
            foreach($category_list as $row){
                $catoption .= "<option value='".$row['id']."'>".$row['title']."</option>";
            }
        }
        echo json_encode(array('data' => $catoption));
    }
    public function manufacturer_list()
    {
        $category_list = $this->products->manufacturer_list();
        $catoption = "";
        if(!empty($category_list))
        {
            foreach($category_list as $row){
                $catoption .= "<option value='".$row['id']."'>".$row['title']."</option>";
            }
        }
        echo json_encode(array('data' => $catoption));
    }
    public function warehouse_list()
    {
        $category_list = $this->products->warehouse_list();
        $catoption = "";
        if(!empty($category_list))
        {
            foreach($category_list as $row){
                $catoption .= "<option value='".$row['id']."'>".$row['title']."</option>";
            }
        }
        echo json_encode(array('data' => $catoption));
    }
    public function min_max_product_price(){
        $details = $this->products->min_max_product_price();
        echo json_encode(array('status' => 'Success', 'data' => $details));

    }
    public function update_inventory_log(){
        
        $data = [
            'product_id' => $this->input->post('product_id'),
            'note'       => $this->input->post('note'),
            'new_qty'    => $this->input->post('new_qty'),
            'old_qty'    => $this->input->post('old_qty'),
            'performed_dt' => date('Y-m-d H:i:s'),
            'performed_by' => $this->session->userdata('id')
        ];
        $changedFields = $this->input->post('changedFields', true);    
        $this->db->insert('cberp_product_inventory_log', $data);
        history_table_log('cberp_products_log','product_id',$this->input->post('product_id'),'Update');
        // //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('product',$this->input->post('product_id'),'Quantity Updated', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        echo json_encode(array('status' => 'Success', 'data' => $details));

    }

    public function barcodeinvoke() {

            $head['title'] = "Barcode";
            $this->load->view('fixed/header', $head);
            $this->load->view('products/barcodeinvoke',$data);
            $this->load->view('fixed/footer');

    }

    public function barcodeprint() {
        
            $id = $this->input->post($this->security->xss_clean('code'));
            $qty = $this->input->post($this->security->xss_clean('qty'));
            $data = array();
            $data['items']['id'] = $id;
            $data['items']['qty'] = $qty;
            $head['title'] = "Barcode";
         
            $this->load->view('fixed/header', $head);
            $this->load->view('products/barcod',$data);
            $this->load->view('fixed/footer');
    }

    public function warehousewise_products()
    {
        $store_id = $this->input->post('store_id');
        $terms = $this->input->post('searchTerm');
        $barcode_type = $this->input->post('barcode_type');
        if($barcode_type)
        {
            $products = $this->products->products_list_by_warehouse_for_label_print_with_barcodetype($store_id, $terms, $barcode_type);
        }
        else{
            $products = $this->products->products_list_by_warehouse_for_label_print($store_id, $terms);
        }
            
        echo json_encode($products);
    }

    public function delete_product_image(){
        $image_id = $this->input->post('image_id');
        $name = $this->input->post('image');
        $this->db->where('product_image_id', $image_id);
        $this->db->delete('cberp_product_images');
        unlink(FCPATH . 'userfiles/product/extraimages/' . $name);
        echo json_encode(array('status' => '1', 'message' =>"Success"));            
    }

    public function weighted_costing_update()
    {
        $product_code = $this->input->post('product_code');
        $weighted_average_cost = $this->input->post('weighted_average_cost');
        $cost  = $this->products->last_cost_by_product_id($product_code);
        // if($weighted_average_cost!=$average_cost)
        // {
        //     echo json_encode(array('status' => 'Success','data'=>'Mismatch'));
        // }
        

        $pricepercentages  = $this->products->priceperc_list();
        $min_price_prec = $pricepercentages['price_perc'];
        $selling_price_perc = $pricepercentages['selling_price_perc'];
        $whole_price_perc = $pricepercentages['whole_price_perc'];
        $web_price_perc = $pricepercentages['web_price_perc'];
        $minprice = (($cost*$min_price_prec)/100) + $cost;
        $selling_price = (($cost*$selling_price_perc)/100) + $cost;
        $web_price = (($cost*$web_price_perc)/100) + $cost;
        $whole_price = (($cost*$whole_price_perc)/100) + $cost;
        
        $data = [
            'minimum_price'=>$minprice,
            'web_price'=>$web_price,
            'wholesale_price'=>$whole_price,
            // 'weighted_average_cost'=>$cost
        ];
        $this->db->where('product_code', $product_code);
        $this->db->update('cberp_product_pricing',$data);
   
        $mainproduct = [
            'product_price'  => $selling_price,
            'product_cost' => $cost
        ];
        $this->db->where('product_code', $product_code);
        $this->db->update('cberp_products',$mainproduct);
        // die($this->db->last_query());
        $changedFields = json_encode([
            [
                'fieldlabel' => $this->input->post('productname')."(".$this->input->post('product_code').")",
                'field_name' => "Cost Update", 
                'oldValue' => $weighted_average_cost,
                'newValue' => $cost
            ]
        ]);
        
        detailed_log_history('Purchasereceipt',$this->input->post('receipt_id'),'Cost Updated', $changedFields);
        echo json_encode(array('status' => 'Success'));

    }

    // Product Reports 12-04-2025
    public function product_reports()
	{

        $head['title'] = "Product Stock Report";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('products/product_stock_report');
        $this->load->view('fixed/footer');
	}

    public function getallproduct() {
        $result = $this->products->get_all_product();
        echo json_encode(['data' => $result]); 
    }
    public function getLowQtyproduct(){
        $result = $this->products->get_low_qty_product();
        echo json_encode(['data' => $result]); 
    }

    public function product_suggested_price()
    {
        // Get data from the POST request (you can pass these values from the form or URL)
        $actual_price = $this->input->post('product_price');
        $product_id = $this->input->post('product_id');
        
        // Prepare the URL with GET parameters
        $url = "https://pricing-model-g.onrender.com/Pricing_Model?ProductID=$product_id&ActualPrice=$actual_price";

        // Set your Basic Auth username and password
        $username = 'Admin';  // Replace with your actual username
        $password = 'AIAdmin123';  // Replace with your actual password

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url); // Set the URL with GET parameters
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string instead of outputting it
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set timeout (in seconds)

        // Set Basic Authentication header
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password"); // Basic auth credentials

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Check if there was an error with the cURL request
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        }

        // Close the cURL session
        curl_close($ch);
        // Decode the JSON response into a PHP array
        $data = json_decode($response, true); // 'true' converts the JSON into an associative array

        // Check if the 'Prediction' key exists in the response
        if (isset($data['Prediction'])) {
            $prediction = $data['Prediction'][0]; // Get the first value from the Prediction array
            $result = "Predicted Price: " . $prediction;  // Output the predicted price
        } else {  
            $result = 'Prediction data for this product is not available.<br> <a href="' . base_url() . 'products/add?code=DOL647866">Click on DOL647866 to view the predicted price</a>';
        }
        
        echo json_encode(array('status' => 'Success','data'=>$result));
        // Output the response (You can process it further depending on the API response)
        // echo $response;
        die();
    }

    
   
}