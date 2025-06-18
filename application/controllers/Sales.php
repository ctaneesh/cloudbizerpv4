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
 *  * Tree Code Hub IT (P) Ltd
 * ***********************************************************************
 */

defined('BASEPATH') or exit('No direct script access allowed');


class Sales extends CI_Controller
{
    private $configurations;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sales_model', 'sales');
        // $this->load->model('products_model', 'products');
        // $this->load->model('quote_model', 'quote');
        $this->load->library("Aauth");
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->li_a = 'sales';
        $this->session->unset_userdata('orderid');
        $this->configurations = $this->session->userdata('configurations');
    }

    
    public function index()
    {
        
        $head['title'] = "Sales";
        $data['eid'] = intval($this->input->get('eid'));
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model', 'invocies');
        $condition = "WHERE cberp_sales_orders.salesorder_number IS NOT NULL";
        $data['counts'] = $this->invocies->get_dynamic_count('cberp_sales_orders','invoicedate','total',$condition);
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/saleslist', $data);
        $this->load->view('fixed/footer');
    }

  

    public function saleviewstatement()
    {
        $this->load->model('Products_model', 'product');     
        $data['permissions'] = load_permissions('Sales','Reports','Sales - Purchase');  
        $pid = $this->input->post('pid'); 
        $sdate = datefordatabase($this->input->post('sdate'));
        $edate = datefordatabase($this->input->post('edate'));
        $product = $this->product->get_product_details($pid);

        $data['filter'] = array($pid, $sdate, $edate,  $product['product_name'], $product['product_code'], $product['product_des'], $product['qty']);

        $head['title'] = "Product Sales Report - ".$data['filter'][3];
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/salestatement_list', $data);
        $this->load->view('fixed/footer');


    }

   
    
    public function ajax_list()
    {     
       
        $eid = 0;
        $eid = $this->input->post('eid');
        $product_code = $this->input->post('product_code');

       
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $list = $this->sales->get_datatables($eid,'cberp_delivery_note_items');
        $list_count = $this->sales->count_all($eid,'cberp_delivery_note_items');
        $list_fil = $this->sales->count_filtered($eid,'cberp_delivery_note_items');

        $del_return_list = $this->sales->get_datatables($eid,'cberp_delivery_return_items');
        $del_return_count = $this->sales->count_all($eid,'cberp_delivery_return_items');
        $del_return_fil = $this->sales->count_filtered($eid,'cberp_delivery_return_items');

        $purchase_list = $this->sales->get_datatables($product_code,'purchase_items');
        $purchase_count = $this->sales->count_all($eid,'purchase_items');
        $purchase_fil = $this->sales->count_filtered($eid,'purchase_items');


       // $results = array( $list, $del_return_list,$purchase_list);
        // $results = [
        //     array($list),
        //     array($del_return_list),
        //     array($purchase_list)        
        // ];
        $manualValues = ["Sale", "Delivery Return", "Purchase"];
        // foreach ($results as $index => $array) {
        //     foreach ($array as $item) {
        //         $item->tran_type = isset($values[$index]) ? $values[$index] : "default"; 
        //     }
        // }
        function addManualField(&$array, $value) {
            foreach ($array as $item) {
                $item->tran_type = $value;
            }
        }
        addManualField($list, $manualValues[0]);
        addManualField($del_return_list, $manualValues[1]);
        addManualField($purchase_list, $manualValues[2]);

        $mergedArray = array_merge($list, $del_return_list, $purchase_list);
        usort($mergedArray, function($a, $b) {
            return strcmp($a->created_date, $b->created_date);
        });

        $total_count = $list_count + $del_return_count + $purchase_count;
        $total_fil = $list_fil + $del_return_fil + $purchase_fil;

        // var_dump($mergedArray);
        // $mergedArray = array_merge($array1, $array2, $array3);

        $data = array();
        $no = $this->input->post('start');
        // foreach ($list as $invoices) {

        foreach ($mergedArray as $invoices) {
            // print_r($invoices);
            // print_r('<br>');   

            // $invoices->tran_type = "Sale";
            $qty = number_format($invoices->product_qty);
            $totalvalue = number_format($invoices->product_qty * $invoices->product_price, 2);
            // $before_qty = $invoices->remaining_qty + $invoices->qty;
            // $before_qty = $invoices->salesorder_product_qty - $invoices->delivery_returned_qty;
           $before_qty = '';
            // $after_qty = $invoices->salesorder_product_qty - $invoices->delivery_returned_qty;
            $no++;
            $row = array();
            $row[] = $no;
            
            $row[] = dateformat($invoices->created_date);
            // $row[] = 'Sale';
            $row[] = $invoices->tran_type;
            $row[] = $qty;
            $row[] = $invoices->product_price;

            // $row[] = $main_url;
            $row[] = $before_qty;
            // $row[] = $invoices->items;
            $row[] = $invoices->product_qty;
            // $row[] = $invoices->price;
            $row[] = $totalvalue;
          
            $data[] = $row;
        }
        // print_r($data); die();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_count,
            "recordsFiltered" => $total_fil,
            // "recordsTotal" => $this->sales->count_all($eid),
            // "recordsFiltered" => $this->sales->count_filtered($eid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }
   
   
    // public function customers_list()
    public function CustomerSalesReport()
    {     
        
        $customer = $this->input->post('customer');        
        $daterange = $this->input->post('daterange');
        $rmethod = $this->input->post('report_method');
        $start_date = $this->input->post('filter_expiry_date_from');
        $end_date = $this->input->post('filter_expiry_date_to');  

        $_SESSION['SaleReportData'] = [
            'customer' => $customer,
            'daterange' => $daterange,
            'rmethod' => $rmethod,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
       
        $cust_list = $this->sales->get_customers($start_date,$end_date,$customer);
        $cust_list1 = $this->sales->get_customers1($start_date,$end_date,$customer);

        // Merge the arrays
        $merged_array = array_merge($cust_list, $cust_list1);        
        $unique_customers = [];
        $customer_ids = [];

        foreach ($merged_array as $item) {
            if (!in_array($item->customer_id, $customer_ids)) {
                $customer_ids[] = $item->customer_id; // Keep track of seen IDs
                $unique_customers[] = $item; // Add unique item to result
            }
        }
        // Convert stdClass array to associative array
        $assocArray = array_map(function($item) {
            return (array)$item; // Cast each stdClass object to an associative array
        }, $unique_customers);

       
        $data = array();
        $resarray = [];
     
        if(!empty($assocArray))
        { 
            $data['customer_list'] = $assocArray;
            foreach ($assocArray as $cust) {
                $customerinfo = $this->sales->customer_by_customer_id($cust['customer_id']);
                $saleresults = $this->sales->customer_sale_by_customer_id($cust['customer_id'],$start_date,$end_date);
                $sale_return_results = $this->sales->customer_sales_return_by_customer_id($cust['customer_id'],$start_date,$end_date);
                foreach ($saleresults as &$sales) {
                    $sales['sale_type'] = 'Delivery'; // Replace 'some_value' with your desired value
                }
                foreach ($sale_return_results as &$sales_r) {
                    $sales_r['sale_type'] = 'Delivery Return'; // Replace 'some_value' with your desired value
                }
                $merged_sale_array = array_merge($saleresults, $sale_return_results);  

                if(!empty($merged_sale_array))
                {
                    foreach($merged_sale_array as $row){
                        if($row['sale_type'] == 'Delivery Return'){
                            $product_qty = -1 * $row['product_qty'];   
                                                     
                        }else{
                            $product_qty = $row['product_qty'];   
                        }
                        $total_poduct_price =  $product_qty * $row['price'];
                        $total_cost =  $product_qty * $row['cost'];

                        $resarray[][$cust['customer_id']] = array(
                            'customer' => $customerinfo['name'],
                            'created_date' => $row['created_date'],
                            'product_code' => $row['product_code'],
                            'product_des' => $row['product_des'],

                            'product_qty' => $product_qty,
                            'product_price' => $row['price'],
                            'cost' => $row['cost'],
                            'total_price' => $total_poduct_price,
                            'total_cost' => $total_cost,
                            'profit' => ($total_poduct_price) - ($total_cost),
                            
                        );
                    }
                }

            }

        }
        $data['permissions'] = load_permissions('Sales','Reports','Customer Sales');
        $data['custlist'] = $resarray; 
        $data['rmethod'] = $rmethod;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;  
        $data['daterange'] = $daterange;  
        if($customer!=''){
            $data['custdata'] = $this->sales->cust_details($customer);
        }

        $head['title'] = "Customer Sales Report";
        $this->load->view('fixed/header', $head);
        $this->load->view('sales/customersales_report', $data);
        $this->load->view('fixed/footer');


    }
    public function customer_sales_pdf()
    {                    
        // if (!$this->aauth->premission(10)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        
        $head['title'] = "Customer Sales Report";
        $head['usernm'] = $this->aauth->get_user()->username;
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;


        $customer = $_SESSION['SaleReportData']['customer'];        
        $daterange = $_SESSION['SaleReportData']['daterange'];
        $rmethod = $_SESSION['SaleReportData']['rmethod'];
        $start_date = $_SESSION['SaleReportData']['start_date'];
        $end_date = $_SESSION['SaleReportData']['end_date'];       
       
        $cust_list = $this->sales->get_customers($start_date,$end_date,$customer);
        $cust_list1 = $this->sales->get_customers1($start_date,$end_date,$customer);

        // Merge the arrays
        $merged_array = array_merge($cust_list, $cust_list1);        
        $unique_customers = [];
        $customer_ids = [];

        foreach ($merged_array as $item) {
            if (!in_array($item->customer_id, $customer_ids)) {
                $customer_ids[] = $item->customer_id; // Keep track of seen IDs
                $unique_customers[] = $item; // Add unique item to result
            }
        }
        // Convert stdClass array to associative array
        $assocArray = array_map(function($item) {
            return (array)$item; // Cast each stdClass object to an associative array
        }, $unique_customers);


        $resarray = [];
        if(!empty($assocArray))
        { 
            $data['customer_list'] = $assocArray;
            foreach ($assocArray as $cust) {
                $customerinfo = $this->sales->customer_by_customer_id($cust['customer_id']);
                $saleresults = $this->sales->customer_sale_by_customer_id($cust['customer_id'],$start_date,$end_date);
                $sale_return_results = $this->sales->customer_sales_return_by_customer_id($cust['customer_id'],$start_date,$end_date);
                foreach ($saleresults as &$sales) {
                    $sales['sale_type'] = 'Delivery'; // Replace 'some_value' with your desired value
                }
                foreach ($sale_return_results as &$sales_r) {
                    $sales_r['sale_type'] = 'Delivery Return'; // Replace 'some_value' with your desired value
                }
                $merged_sale_array = array_merge($saleresults, $sale_return_results);  

                if(!empty($merged_sale_array))
                {
                    foreach($merged_sale_array as $row){
                        if($row['sale_type'] == 'Delivery Return'){
                            $product_qty = -1 * $row['product_qty'];   
                                                     
                        }else{
                            $product_qty = $row['product_qty'];   
                        }
                        $total_poduct_price =  $product_qty * $row['price'];
                        $total_cost =  $product_qty * $row['cost'];

                        $resarray[][$cust['customer_id']] = array(
                            'customer' => $customerinfo['name'],
                            'created_date' => $row['created_date'],
                            'product_code' => $row['product_code'],
                            'product_des' => $row['product_des'],
                            'product_qty' => $product_qty,
                            'product_price' => $row['price'],
                            'cost' => $row['cost'],
                            'total_price' => $total_poduct_price,
                            'total_cost' => $total_cost,
                            'profit' => ($total_poduct_price) - ($total_cost),
                            
                        );
                    }
                }

            }

        }
      
        $data['custlist'] = $resarray;
        $data['rmethod'] = $rmethod;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;  
        $data['daterange'] = $daterange; 

        $html = $this->load->view('sales/customersalereportprintpdf-' . LTR, $data, true);
            
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        // $pdf = $this->pdf->load('utf-8', 'A4-L');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('customer-sales-report.pdf', 'I');  
    }
    public function export_to_excel()
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        $filename = 'customer_sales_report_' . date('Y-m-d') . '.csv';

        // Set the headers to force download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
         // Open output stream
        $output = fopen('php://output', 'w');
        
        $configurations = $this->session->userdata('configurations');
        $config_currency = $configurations['config_currency'];
        // Write the CSV header
        fputcsv($output, ['Customer', 'Sale Date','Item ID', 'Item Description', 'Quantity Sold', 'Price',  'Cost', 'Total Price', 'Total Cost', 'Profit']);        
        
        $customer = $_SESSION['SaleReportData']['customer'];        
        $daterange = $_SESSION['SaleReportData']['daterange'];
        $rmethod = $_SESSION['SaleReportData']['rmethod'];
        $start_date = $_SESSION['SaleReportData']['start_date'];
        $end_date = $_SESSION['SaleReportData']['end_date'];       
       
        $cust_list = $this->sales->get_customers($start_date,$end_date,$customer);
        $cust_list1 = $this->sales->get_customers1($start_date,$end_date,$customer);

        // Merge the arrays
        $merged_array = array_merge($cust_list, $cust_list1);        
        $unique_customers = [];
        $customer_ids = [];

        foreach ($merged_array as $item) {
            if (!in_array($item->customer_id, $customer_ids)) {
                $customer_ids[] = $item->customer_id; // Keep track of seen IDs
                $unique_customers[] = $item; // Add unique item to result
            }
        }
        // Convert stdClass array to associative array
        $assocArray = array_map(function($item) {
            return (array)$item; // Cast each stdClass object to an associative array
        }, $unique_customers);

        $report_total = 0;
        $report_item_total = 0;
        $report_sub_total = 0;
        $report_cost_total = 0;

        foreach ($assocArray as $cust) {
            $customerId = $cust['customer_id'];
            fputcsv($output, [$cust['name'], '', '', '', '', '', '', '', '', '', '']);  

            $customerinfo = $this->sales->customer_by_customer_id($cust['customer_id']);
            $saleresults = $this->sales->customer_sale_by_customer_id($cust['customer_id'],$start_date,$end_date);
            $sale_return_results = $this->sales->customer_sales_return_by_customer_id($cust['customer_id'],$start_date,$end_date);
            foreach ($saleresults as &$sales) {
                $sales['sale_type'] = 'Delivery'; // Replace 'some_value' with your desired value
            }
            foreach ($sale_return_results as &$sales_r) {
                $sales_r['sale_type'] = 'Delivery Return'; // Replace 'some_value' with your desired value
            }
            $merged_sale_array = array_merge($saleresults, $sale_return_results); 
            
            if(!empty($merged_sale_array))
            {
                $item_total = 0;
                $sub_total_price = 0;
                $sub_total_cost = 0;

                $profit_total = 0;
                foreach($merged_sale_array as $row){
                    if($row['sale_type'] == 'Delivery Return'){
                        $product_qty = -1 * $row['product_qty'];   
                                                 
                    }else{
                        $product_qty = $row['product_qty'];   
                    }
                    $total_poduct_price =  $product_qty * $row['price'];
                    $total_cost =  $product_qty * $row['cost'];

              
                    $created_date = !empty($row['created_date']) ? date('d-m-Y', strtotime($row['created_date'])) : $row['created_date'];
                                         
                    $product_code = $row['product_code'];
                    $product_des = $row['product_des'];
                    $product_qty = $product_qty;
                    $product_price = $row['price'];
                    $cost = $row['cost'];
                    $total_price = $total_poduct_price;
                    $total_cost = $total_cost;
                    $profit = $total_poduct_price - $total_cost;

                    $profit_total = $profit_total + $profit; 
                    $item_total = $item_total +  $product_qty;
                    $sub_total_price = $sub_total_price +  $product_price;
                    $sub_total_cost = $sub_total_cost + $total_cost;

                    fputcsv($output, ['', $created_date, $product_code, $product_des, $product_qty, $product_price, $cost, $total_price, $total_cost, $profit]);
                }              
                
            }
            $report_total = $report_total + $profit_total;
            $report_item_total = $report_item_total + $item_total;
            $report_sub_total = $report_sub_total + $sub_total_price;
            $report_cost_total = $report_cost_total + $sub_total_cost;

            fputcsv($output, ['','Total', '', '', $item_total, '', '', $sub_total_price, $sub_total_cost, $profit_total]);
        }
       
        fputcsv($output, ['','', '', '', 'Total Items', '', '', 'Total Price', 'Total Cost',  'Grand Total']);
        fputcsv($output, ['','', '', '', $report_item_total, '', '', $report_sub_total, $report_cost_total,  $report_total]);
        
        fclose($output);
        exit;
    }

    function average_calc()
    {
    
        $head['title'] = "Average Price";
        $list = $this->purchase->get_data_from_average_price_table();
        $data['list'] = $list;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/averagecost', $data);
        $this->load->view('fixed/footer');
    }
}

