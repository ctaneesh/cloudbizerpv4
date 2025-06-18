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

class Reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sales_model', 'sales');
        $this->load->model('Tree_model', 'tree');
        $this->load->model('reports_model', 'reports');
        $this->load->model('stockreport_model', 'stockreport');
        $this->load->model('salesorderreport_model', 'salesorderreport');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(10)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $this->li_a = 'data';
    }

    public function index()
    {
    }

    //Statistics

    public function statistics()
    {
        $data['stat'] = $this->reports->statistics();
        $head['title'] = "Statistics";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/stat', $data);
        $this->load->view('fixed/footer');
    }


    //accounts section

    public function accountstatement()

    {
        $data['permissions'] = load_permissions('Accounts','Accounts','Account Statements');
        $this->load->model('transactions_model');
        $data['accounts'] = $this->transactions_model->acc_list();
        $head['title'] = "Account Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/statement', $data);
        $this->load->view('fixed/footer');

    }

    public function customerstatement()

    {
        $this->load->model('transactions_model');
        $data['accounts'] = $this->transactions_model->acc_list();
        $head['title'] = "Account Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/customer_statement', $data);
        $this->load->view('fixed/footer');

    }

    public function supplierstatement()

    {
        $this->load->model('transactions_model');
        $data['accounts'] = $this->transactions_model->acc_list();
        $head['title'] = "Account Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/supplier_statement', $data);
        $this->load->view('fixed/footer');

    }

    public function viewstatement()

    {
        $this->load->model('accounts_model', 'accounts');
        $pay_acc = $this->input->post('pay_acc');
        $trans_type = $this->input->post('trans_type');
        $sdate = datefordatabase($this->input->post('sdate'));
        $edate = datefordatabase($this->input->post('edate'));
        $ttype = $this->input->post('ttype');
        $account = $this->accounts->details($pay_acc);
        $data['filter'] = array($pay_acc, $trans_type, $sdate, $edate, $ttype, $account['holder']);
        $head['title'] = "Account Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/statement_list', $data);
        $this->load->view('fixed/footer');
    }

    public function customerviewstatement()

    {
        $this->load->model('customers_model', 'customer');
        $cid = $this->input->post('customer');
        $trans_type = $this->input->post('trans_type');
        $sdate = datefordatabase($this->input->post('sdate'));
        $edate = datefordatabase($this->input->post('edate'));
        $ttype = $this->input->post('ttype');
        $customer = $this->customer->details($cid);
        $data['filter'] = array($cid, $trans_type, $sdate, $edate, $ttype, $customer['name']);

        //  print_r( $data['statement']);
        $head['title'] = "Customer Account Statement - ".$data['filter'][5];
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/customerstatement_list', $data);
        $this->load->view('fixed/footer');


    }

    public function supplierviewstatement()

    {
        $this->load->model('supplier_model', 'supplier');
        $cid = $this->input->post('supplier');
        $trans_type = $this->input->post('trans_type');
        $sdate = datefordatabase($this->input->post('sdate'));
        $edate = datefordatabase($this->input->post('edate'));
        $ttype = $this->input->post('ttype');
        $customer = $this->supplier->details($cid);
        $data['filter'] = array($cid, $trans_type, $sdate, $edate, $ttype, $customer['name']);
        $head['title'] = "Supplier Account Statement - ".$data['filter'][5];
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/supplierstatement_list', $data);
        $this->load->view('fixed/footer');


    }


    //

    public function statements()
    {
        $pay_acc = $this->input->post('ac');
        $trans_type = $this->input->post('ty');
        $sdate = datefordatabase($this->input->post('sd'));
        $edate = datefordatabase($this->input->post('ed'));
    
        // Fetch data from the reports model
        $result = $this->reports->get_statements($pay_acc, $trans_type, $sdate, $edate);
        $data = [];
        $balance = 0;
    
        // Prepare the data for DataTable
        foreach ($result as $row) {
            $balance += $row['credit'] - $row['debit'];
            $data[] = [
                'date' => dateformat($row['date']),
                'note' => $row['note'],
                'debit' => number_format($row['debit'], 2),
                'credit' => number_format($row['credit'], 2),
                'balance' => number_format($balance, 2)
            ];
        }
    
        // Return the data in DataTables format
        echo json_encode([
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->reports->get_statements_count($pay_acc, $trans_type, $sdate, $edate),
            "recordsFiltered" => $this->reports->get_statements_count($pay_acc, $trans_type, $sdate, $edate),
            "data" => $data
        ]);
    }
    

    public function customerstatements()
    {


        $pay_acc = $this->input->post('ac');
        $trans_type = $this->input->post('ty');
        $sdate = datefordatabase($this->input->post('sd'));
        $edate = datefordatabase($this->input->post('ed'));

        $list = $this->reports->get_customer_statements($pay_acc, $trans_type, $sdate, $edate);
        // print_r($list); die();
        $data = [];
        $balance = 0;
        // foreach ($list as $row) {
        //     $balance += $row['credit'] - $row['debit'];
        //     echo '<tr><td>' . $row['date'] . '</td><td>' . $row['note'] . '</td><td>' . amountExchange($row['debit'], 0, $this->aauth->get_user()->loc) . '</td><td>' . amountExchange($row['credit'], 0, $this->aauth->get_user()->loc) . '</td><td>' . amountExchange($balance, 0, $this->aauth->get_user()->loc) . '</td></tr>';
        // }
        foreach ($list as $row) {
            $balance += $row['credit'] - $row['debit'];
            $data[] = [
                'date' => dateformat($row['date']),
                'note' => $row['note'],
                'debit' => number_format($row['debit'], 2),
                'credit' => number_format($row['credit'], 2),
                'balance' => number_format($balance, 2)
            ];
        }
    
        // Return the data in DataTables format
        echo json_encode([
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->reports->get_customer_statements_count($pay_acc, $trans_type, $sdate, $edate),
            "recordsFiltered" => $this->reports->get_customer_statements_count($pay_acc, $trans_type, $sdate, $edate),
            "data" => $data
        ]);

    }

    public function supplierstatements()
    {


        $pay_acc = $this->input->post('ac');
        $trans_type = $this->input->post('ty');
        $sdate = datefordatabase($this->input->post('sd'));
        $edate = datefordatabase($this->input->post('ed'));


        $list = $this->reports->get_supplier_statements($pay_acc, $trans_type, $sdate, $edate);
        $balance = 0;
        $data = [];
        // foreach ($list as $row) {
        //     $balance += $row['debit'] - $row['credit'];
        //     echo '<tr><td>' . $row['date'] . '</td><td>' . $row['note'] . '</td><td>' . amountExchange($row['debit'], 0, $this->aauth->get_user()->loc) . '</td><td>' . amountExchange($row['credit'], 0, $this->aauth->get_user()->loc) . '</td><td>' . amountExchange($balance, 0, $this->aauth->get_user()->loc) . '</td></tr>';
        // }
        foreach ($list as $row) {
            $balance += $row['credit'] - $row['debit'];
            $data[] = [
                'date' => dateformat($row['date']),
                'note' => $row['note'],
                'debit' => number_format($row['debit'], 2),
                'credit' => number_format($row['credit'], 2),
                'balance' => number_format($balance, 2)
            ];
        }
    
        // Return the data in DataTables format
        echo json_encode([
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->reports->get_supplier_statements_count($pay_acc, $trans_type, $sdate, $edate),
            "recordsFiltered" => $this->reports->get_supplier_statements_count($pay_acc, $trans_type, $sdate, $edate),
            "data" => $data
        ]);

    }


    // income section


    public function incomestatement()

    {
        $head['title'] = "Income Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);

        $this->load->model('transactions_model');
        $data['accounts'] = $this->transactions_model->acc_list();
        $data['income'] = $this->reports->incomestatement();


        $this->load->view('reports/incomestatement', $data);


        $this->load->view('fixed/footer');

    }


    public function customincome()
    {

        if ($this->input->post('check')) {
            $acid = $this->input->post('pay_acc');
            $sdate = datefordatabase($this->input->post('sdate'));
            $edate = datefordatabase($this->input->post('edate'));

            $date1 = new DateTime($sdate);
            $date2 = new DateTime($edate);

            $diff = $date2->diff($date1)->format("%a");
            if ($diff < 365) {
                $income = $this->reports->customincomestatement($acid, $sdate, $edate);

                echo json_encode(array('status' => 'Success', 'message' => 'Calculated', 'param1' => '<hr><b>Income between the dates is ' . amountExchange($income['credit'], 0, $this->aauth->get_user()->loc) . '</b>'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => 'Date range should be within 365 days', 'param1' => ''));
            }

        }
    }

    // expense section


    public function expensestatement()

    {
        $head['title'] = "Expense Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);

        $this->load->model('transactions_model');
        $data['accounts'] = $this->transactions_model->acc_list();
        $data['income'] = $this->reports->expensestatement();


        $this->load->view('reports/expensestatement', $data);


        $this->load->view('fixed/footer');

    }


    public function customexpense()
    {

        if ($this->input->post('check')) {
            $acid = $this->input->post('pay_acc');
            $sdate = datefordatabase($this->input->post('sdate'));
            $edate = datefordatabase($this->input->post('edate'));

            $date1 = new DateTime($sdate);
            $date2 = new DateTime($edate);

            $diff = $date2->diff($date1)->format("%a");
            if ($diff < 365) {
                $income = $this->reports->customexpensestatement($acid, $sdate, $edate);

                echo json_encode(array('status' => 'Success', 'message' => 'Calculated', 'param1' => '<hr><b>Expense between the dates is ' . amountExchange($income['debit'], 0, $this->aauth->get_user()->loc) . '</b>'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => 'Date range should be within 365 days', 'param1' => ''));
            }

        }

    }


    public function refresh_data()

    {


        $head['title'] = "Refreshing Reports";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/refresh_data');
        $this->load->view('fixed/footer');

    }

    public function refresh_process()

    {

        $this->load->model('cronjob_model');
        if ($this->cronjob_model->reports()) {

            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Calculated')));
        }

    }

    public function taxstatement()

    {
        $this->load->model('transactions_model');
        $data['accounts'] = $this->transactions_model->acc_list();
        $head['title'] = "TAX Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('locations_model');
        $data['locations'] = $this->locations_model->locations_list();
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/tax_statement', $data);
        $this->load->view('fixed/footer');

    }

    public function taxviewstatement()

    {


        $trans_type = $this->input->post('ty');
        $sdate = datefordatabase($this->input->post('sdate'));
        $edate = datefordatabase($this->input->post('edate'));
        $lid = $this->input->post('lid');
        $data['filter'] = array($sdate, $edate, $trans_type, $lid);


        $head['title'] = "TAX Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/tax_out', $data);
        $this->load->view('fixed/footer');


    }

    public function taxviewstatements_load()
    {


        $trans_type = $this->input->post('ty');
        $sdate = datefordatabase($this->input->post('sd'));
        $edate = datefordatabase($this->input->post('ed'));
        $lid = $this->input->post('loc');
        $sdate = !empty($sdate) ? date('Y-m-d',strtotime($sdate)) : ""; 
        $edate = !empty($edate) ? date('Y-m-d',strtotime($edate)) : ""; 
        $counts=0;
        if ($trans_type == 'Sales') {
            $result = $this->reports->sales_tax_statement($sdate, $edate, $lid);
            $counts = $this->reports->get_sales_tax_statement_count($sdate, $edate, $lid);
        }
        else{
            $result = $this->reports->purchase_tax_statement($sdate, $edate, $lid);           
            $counts = $this->reports->get_purchses_tax_statement_count($sdate, $edate, $lid);
        }
        // if ($trans_type == 'Sales') {
        //     $where = " WHERE (DATE(cberp_invoices.invoicedate) BETWEEN '$sdate' AND '$edate' )";
        //     if ($lid > 0) $where .= " AND (cberp_invoices.loc=$lid)";
        //     $query = $this->db->query("SELECT cberp_customers.tax_id AS VAT_Number,cberp_invoices.tid AS invoice_number,cberp_invoices.total AS amount,cberp_invoices.tax AS tax,cberp_customers.name AS customer_name,cberp_customers.company AS Company_Name,cberp_invoices.invoicedate AS date FROM cberp_invoices LEFT JOIN cberp_customers ON cberp_invoices.csd=cberp_customers.customer_id" . $where);
        // } else {

        //     $where = " WHERE (DATE(cberp_purchase_orders.invoicedate) BETWEEN '$sdate' AND '$edate') ";
        //     if ($lid > 0) $where .= " AND (cberp_invoices.loc=$lid)";
        //     $query = $this->db->query("SELECT cberp_suppliers.tax_id AS VAT_Number,cberp_purchase_orders.tid AS invoice_number,cberp_purchase_orders.total AS amount,cberp_purchase_orders.tax AS tax,cberp_suppliers.name AS customer_name,cberp_suppliers.company AS Company_Name,cberp_purchase_orders.invoicedate AS date FROM cberp_purchase_orders LEFT JOIN cberp_suppliers ON cberp_purchase_orders.csd=cberp_suppliers.supplier_id" . $where);
        // }


        $balance = 0;
        $data=[];
        foreach ($result as $row) {
            $balance += $row['tax'];
            $data[] = [
                'invoice_number' => $row['invoice_number'],
                'customer_name' => ($row['customer_name']),
                'Company_Name' => $row['Company_Name'],
                'amount' => number_format( $row['amount'], 2),
                'tax' => number_format( $row['tax'], 2),
                'balance' => number_format($balance, 2)
            ];
        }
    
        echo json_encode([
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $counts,
            "recordsFiltered" => $counts,
            "data" => $data
        ]);
        // foreach ($result as $row) {
        //     $balance += $row['tax'];
        //     echo '<tr><td>' . $row['invoice_number'] . '</td><td>' . $row['customer_name'] . '</td><td>' . $row['VAT_Number'] . '</td><td>' . amountExchange($row['amount'], 0, $this->aauth->get_user()->loc) . '</td><td>' . amountExchange($row['tax'], 0, $this->aauth->get_user()->loc) . '</td><td>' . amountExchange($balance, 0, $this->aauth->get_user()->loc) . '</td></tr>';
        // }


    }

    // profit section


    public function profitstatement()

    {
        $head['title'] = "Profit Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);

        $this->load->model('locations_model');
        $data['locations'] = $this->locations_model->locations_list2();
        $data['income'] = $this->reports->profitstatement();


        $this->load->view('reports/profitstatement', $data);


        $this->load->view('fixed/footer');

    }


    public function customprofit()
    {

        if ($this->input->post('check')) {
            $lid = $this->input->post('pay_acc');
            $sdate = datefordatabase($this->input->post('sdate'));
            $edate = datefordatabase($this->input->post('edate'));

            $date1 = new DateTime($sdate);
            $date2 = new DateTime($edate);

            // if ($this->aauth->get_user()->loc) {
            //     $lid = $this->aauth->get_user()->loc;
            // }

            $diff = $date2->diff($date1)->format("%a");
            if ($diff < 365) {
                $income = $this->reports->customprofitstatement($lid, $sdate, $edate);

                echo json_encode(array('status' => 'Success', 'message' => 'Calculated', 'param1' => '<hr> Profit between the dates is ' . amountExchange($income['col1'], 0, $this->aauth->get_user()->loc) . ' '));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => 'Date range should be within 365 days', 'param1' => ''));
            }

        }
    }

    // profit section


    public function sales()

    {
        $head['title'] = "Sales Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);

        $this->load->model('locations_model');
        $data['locations'] = $this->locations_model->locations_list();
        $data['income'] = $this->reports->salesstatement();


        $this->load->view('reports/sales', $data);


        $this->load->view('fixed/footer');

    }


    public function customsales()
    {

        if ($this->input->post('check')) {
            $lid = $this->input->post('pay_acc');
            $sdate = datefordatabase($this->input->post('sdate'));
            $edate = datefordatabase($this->input->post('edate'));

            $date1 = new DateTime($sdate);
            $date2 = new DateTime($edate);

            // if ($this->aauth->get_user()->loc) {
            //     $lid = $this->aauth->get_user()->loc;
            // }

            $diff = $date2->diff($date1)->format("%a");
            if ($diff < 365) {
                $income = $this->reports->customsalesstatement($lid, $sdate, $edate);

                echo json_encode(array('status' => 'Success', 'message' => 'Calculated', 'param1' => '<hr> Sales between the dates is ' . amountExchange($income['total'], 0, $this->aauth->get_user()->loc) . ''));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => 'Date range should be within 365 days', 'param1' => ''));
            }
        }
    }

    // products section
    public function products()

    {
        $head['title'] = "Products Statement";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->model('locations_model');
        $this->load->model('categories_model');
        $data['locations'] = $this->locations_model->locations_list();
        $data['cat'] = $this->categories_model->category_list();
        $data['income'] = $this->reports->productsstatement();
        $this->load->view('reports/products', $data);
        $this->load->view('fixed/footer');
    }


    public function customproducts()
    {
        if ($this->input->post('check')) {
            $lid = $this->input->post('pay_acc');
            $sdate = datefordatabase($this->input->post('sdate'));
            $edate = datefordatabase($this->input->post('edate'));
            $date1 = new DateTime($sdate);
            $date2 = new DateTime($edate);
            $diff = $date2->diff($date1)->format("%a");
            // if ($this->aauth->get_user()->loc) {
            //     $lid = $this->aauth->get_user()->loc;
            // }
            if ($diff < 365) {
                $income = $this->reports->customproductsstatement($lid, $sdate, $edate);
                echo json_encode(array('status' => 'Success', 'message' => 'Calculated', 'param1' => '<hr>Product Sales between the dates is ' . amountExchange($income['subtotal'], 0, $this->aauth->get_user()->loc) . ' <br> Qty between the dates is ' . amountFormat_general($income['qty']) . '.'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => 'Date range should be within 365 days', 'param1' => ''));
            }

        }
    }

    public function customproducts_cat()
    {
        if ($this->input->post('check')) {
            $lid = $this->input->post('pay_acc');
            $sdate = datefordatabase($this->input->post('sdate'));
            $edate = datefordatabase($this->input->post('edate'));
            $date1 = new DateTime($sdate);
            $date2 = new DateTime($edate);
            $diff = $date2->diff($date1)->format("%a");
            // if ($this->aauth->get_user()->loc) {
            //     $lid = $this->aauth->get_user()->loc;
            // }
            if ($diff < 365) {
                $income = $this->reports->customproductsstatement_cat($lid, $sdate, $edate);
                echo json_encode(array('status' => 'Success', 'message' => 'Calculated', 'param1' => '<hr>Product Sales between the dates is ' . amountExchange($income['subtotal'], 0, $this->aauth->get_user()->loc) . ' Qty between the dates is ' . amountFormat_general($income['qty']) . '.'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => 'Date range should be within 365 days', 'param1' => ''));
            }

        }
    }

    public function fetch_data()
    {
        if ($this->input->get('p')) {

            $data = $this->reports->fetchdata($this->input->get('p'));
            echo json_encode(array('status' => 'Success', 'message' => 'Calculated', 'p1' => $data['p1'], 'p2' => $data['p2'], 'p3' => $data['p3'], 'p4' => $data['p4']));
        }
    }
    public function aging_report()
    {       
        
        
        // $data['paymentlist'] = $resarray;
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        
        $head['title'] = "Ar Aging Report";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/aragingreport', $data);
        $this->load->view('fixed/footer');

        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        $resarray = [];
        $uppaid_customers = $this->reports->unpaid_customer_list();
        if(!empty($uppaid_customers))
        {
            foreach ($uppaid_customers as $key => $value) {
                $paymentresults = $this->reports->payment_list_for_compay_by_id($value['id']);
                if(!empty($paymentresults))
                {
                    foreach($paymentresults as $row){
                        $resarray[] = array(
                            'company' => $value['company'],
                            'today_total' => $row['today_total'],
                            '30days' => $row['total_1_30_days'],
                            '60days' => $row['total_31_60_days'],
                            '90days' => $row['total_61_90_days'],
                            '90plus' => $row['total_above_90_days'],
                        );
                    }
                }
                
            }
        }
        $data['paymentlist'] = $resarray;
        $html = $this->load->view('reports/agingreportprintpdf-' . LTR, $data, true);
            
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        // $pdf = $this->pdf->load('utf-8', 'A4-L');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('ar-aging-report' . $pay_acc . '.pdf', 'I');  
    }

    public function ar_aging_report()
    {
        $data['permissions'] = load_permissions('Data and Reports','Accounting','Aged Receivables');
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000');        
        $data = [];
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        $resarray = [];
        
        // $uppaid_customers = $this->reports->unpaid_customer_list();
        $uppaid_customers = $this->reports->customer_list();
      
        if(!empty($uppaid_customers))
        {
            $data['customer_list'] = $uppaid_customers;
            foreach ($uppaid_customers as $key => $value) {
                $paymentresults = $this->reports->invoice_list_for_compay_by_id($value['id']);
                // echo "<pre>"; print_r($paymentresults); die();
                if(!empty($paymentresults))
                {
                    // if($value['id']==1) { continue; }
                    foreach($paymentresults as $row){
                        $resarray[][$value['id']] = array(
                            'company' => (!empty($value['company']))?$value['company']:$value['name'],
                            'invoiceid' => $row['id'],
                            'invoicetid' => $row['tid'],
                            'invoice_date' => $row['invoicedate'],
                            'invoice_due_date' => $row['invoiceduedate'],
                            'subtotal' => $row['total'],
                            'payment_recieved_amount' => $row['payment_recieved_amount'],
                            'invoice_number' => $row['invoice_number'],
                            'status' => $row['status'],
                            'today_total' => $row['today_total'],
                            '30days' => $row['total_1_30_days'],
                            '60days' => $row['total_31_60_days'],
                            '90days' => $row['total_61_90_days'],
                            '90plus' => $row['total_above_90_days'],
                        );
                    }
                    // echo "<pre>"; print_r($resarray); die();
                }
                
            }
        }
        $data['paymentlist'] = $resarray;
        $head['title'] = "Ar Aging Report";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/aragingreport', $data);
        $this->load->view('fixed/footer');
        
    }
    

    public function aging_report_pdf()
    {       
        
        
        // $data['paymentlist'] = $resarray;
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        
        $head['title'] = "Ar Aging Report";
        $head['usernm'] = $this->aauth->get_user()->username;
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        $resarray = [];
        $uppaid_customers = $this->reports->customer_list();
      
        if(!empty($uppaid_customers))
        {
            $data['customer_list'] = $uppaid_customers;
            foreach ($uppaid_customers as $key => $value) {
                $paymentresults = $this->reports->invoice_list_for_compay_by_id($value['id']);
                if(!empty($paymentresults))
                {
                    // if($value['id']==1) { continue; }
                    foreach($paymentresults as $row){
                        $resarray[][$value['id']] = array(
                            'company' => (!empty($value['company']))?$value['company']:$value['name'],
                            'invoiceid' => $row['id'],
                            'invoicetid' => $row['tid'],
                            'invoice_date' => $row['invoicedate'],
                            'invoice_due_date' => $row['invoiceduedate'],
                            'subtotal' => $row['total'],
                            'payment_recieved_amount' => $row['payment_recieved_amount'],
                            'invoice_number' => $row['invoice_number'],
                            'status' => $row['status'],
                            'today_total' => $row['today_total'],
                            '30days' => $row['total_1_30_days'],
                            '60days' => $row['total_31_60_days'],
                            '90days' => $row['total_61_90_days'],
                            '90plus' => $row['total_above_90_days'],
                        );
                    }
                    // echo "<pre>"; print_r($resarray); die();
                }
                
            }
        }
        $data['paymentlist'] = $resarray;
        $html = $this->load->view('reports/aragingreportprintpdf-' . LTR, $data, true);
            
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        // $pdf = $this->pdf->load('utf-8', 'A4-L');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('ar-aging-report' . $pay_acc . '.pdf', 'I');  
    }

    public function pay_to_supplier_report()
    {
        
       
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        $resarray = [];
        $supplierlist_forpay = $this->reports->supplier_list_for_pay();
        
        if(!empty($supplierlist_forpay))
        {
            foreach ($supplierlist_forpay as $key => $value) {
                $paymentresults = $this->reports->payment_list_for_supplier_by_id($value['id']);
                // echo "<pre>"; print_r($paymentresults); die();
                if(!empty($paymentresults))
                {
                    foreach($paymentresults as $row){
                        $resarray[][$value['id']] = array(
                            'company' => $value['company'],
                            'total' => $row['total'],
                            'purchase_number' => $row['purchase_number'],
                            'pamnt' => $row['pamnt'],
                            'status' => $row['status'],
                            'invoice_date' => $row['invoicedate'],
                            'invoice_due_date' => $row['invoiceduedate'],
                            'today_total' => $row['today_total'],
                            '30days' => $row['total_1_30_days'],
                            '60days' => $row['total_31_60_days'],
                            '90days' => $row['total_61_90_days'],
                            '90plus' => $row['total_above_90_days'],
                        );
                    }
                }
            }
        }

        $data['supplier_list'] = $supplierlist_forpay;
        $data['paymentlist'] = $resarray;
    //    echo "<pre>"; print_r($resarray); die();
        $html = $this->load->view('reports/paytosupplierreportprintpdf-' . LTR, $data, true);
         
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        // $pdf = $this->pdf->load('utf-8', 'A4-L');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('pay-to-supplier-report' . $pay_acc . '.pdf', 'I');  
      
        
            
    }

    public function pay_to_supplier_aged_report()
    {
        
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
      //  $data['permissions'] = load_permissions('Data and Reports','Accounting','Aged Payables');
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        $resarray = [];
        $supplierlist_forpay = $this->reports->supplier_lists();       
        // echo "<pre>";print_r($supplierlist_forpay);die(); 
        if(!empty($supplierlist_forpay))
        {
            $data['customer_list'] = $supplierlist_forpay;
            foreach ($supplierlist_forpay as $key => $value) {
                $paymentresults = $this->reports->payment_list_for_suppliers_byid($value['id']);
                // echo "<pre>";print_r($paymentresults);die(); 
                if(!empty($paymentresults))
                {
                    foreach($paymentresults as $row){
                        $resarray[][$value['id']] = array(
                            'company' => !empty($value['company'])?$value['company']:$value['name'],
                            'invoiceid' => $row['id'],
                            // 'invoicetid' => $row['tid'],
                            'invoice_date' => $row['purchase_order_date'],
                            'invoice_due_date' => $row['duedate'],
                            'purchase_number' => $row['purchase_number'],
                            'payment_recieved_amount' => $row['paid_amount'],
                            'subtotal' => $row['order_total'],
                            'status' => $row['payment_status'],
                            '30days' => $row['total_1_30_days'],
                            '60days' => $row['total_31_60_days'],
                            '90days' => $row['total_61_90_days'],
                            '90plus' => $row['total_above_90_days'],
                        );
                    }
                }
                
            }
        }
        $data['paymentlist'] = $resarray;
        $head['title'] = "Pay To Supplier Aging Report";
        $head['usernm'] = $this->aauth->get_user()->username;
        // echo "<pre>"; print_r($paymentresults);  die();
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/supplier_aging_report', $data);
        $this->load->view('fixed/footer');
      
        
            
    }

    public function commission()

    {
        if ($this->input->post('check')) {
            $lid = $this->input->post('pay_acc');
            $sdate = datefordatabase($this->input->post('sdate'));
            $edate = datefordatabase($this->input->post('edate'));

            $date1 = new DateTime($sdate);
            $date2 = new DateTime($edate);

            // if ($this->aauth->get_user()->loc) {
            //     $lid = $this->aauth->get_user()->loc;
            // }

            $diff = $date2->diff($date1)->format("%a");
            if ($diff < 365) {
                $commission = $this->reports->customcommission($lid, $sdate, $edate);

                echo json_encode(array('status' => 'Success', 'message' => 'Calculated', 'param1' => 'Commission between the dates is ' . amountExchange($commission, 0, $this->aauth->get_user()->loc)));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => 'Date range should be within 365 days', 'param1' => ''));
            }

        } else {
            $head['title'] = "Commission";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);

            $this->load->model('employee_model');
            $data['employee'] = $this->employee_model->list_employee();

            $this->load->view('reports/commission', $data);


            $this->load->view('fixed/footer');
        }

    }

    public function export_to_excel()
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        $filename = 'aged_receivable_report_' . date('Y-m-d') . '.csv';

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
        fputcsv($output, ['Company', 'Invoice Date','Due Date', 'Amount', 'Paid Amount', 'Currency',   '1-30 Days', '31-60 Days', '61-90 Days', '90 Days', 'Total']);
        $customer_list = $this->reports->customer_list();
        $net_days30_total =0;
        $net_days60_total =0;
        $net_days90_total =0;
        $net_days90plus_total =0;
        $net_amount=0;
        $net_paid = 0;
        $net_colwise_total = 0;
        foreach ($customer_list as $row) {
            $customerId = $row['id'];
            fputcsv($output, [$row['company'], '', '', '', '', '', '', '', '', '', '']);    
            $days30_total = $days60_total = $days90_total = $days90plus_total = $colwise_total = 0;
            $paymentresults = $this->reports->invoice_list_for_compay_by_id($customerId);
            $grand_total = 0;
            $paid_total = 0;
            if(!empty($paymentresults))
            {
                // echo "<pre>"; print_r($paymentresults); die();
                foreach($paymentresults as $item){

                    $invoicetid = $item['invoice_number'];
                  
                    $invoice_date = !empty($item['invoicedate']) ? date('d-m-Y', strtotime($item['invoicedate'])) : '';
                    $subtotal = $item['total'];
                    $payment_recieved_amount = $item['payment_recieved_amount'];
                    $grand_total += $subtotal;
                    $paid_total += $payment_recieved_amount;
                   
                    $invoice_due_date = !empty($item['invoiceduedate']) ? date('d-m-Y', strtotime($item['invoiceduedate'])) : '';
                    $days30 = $item['total_1_30_days'] > 0 ? $item['total_1_30_days'] : 0.00;
                    $days60 = $item['total_31_60_days'] > 0 ? $item['total_31_60_days'] : 0.00;
                    $days90 = $item['total_61_90_days'] > 0 ? $item['total_61_90_days'] : 0.00;
                    $days90plus = $item['total_above_90_days'] > 0 ? $item['total_above_90_days'] : 0.00;
                
                    $status = $item['status'];
                    if(($status== 'due' || $status==  'partial') && $days30>=1)
                    {
                        $txtcls1 = "text-danger";
                        $days30 =  $days30 * -1;
                        
                    }
                    else{
                        $txtcls1 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days60>=1)
                    {
                        $txtclsdays60 = "text-danger";
                        $days60 =  $days60 * -1;
                    }
                    else{
                        $txtclsdays60 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days90>=1)
                    {
                        $txtclsdays90 = "text-danger";
                        $days90 =  $days90 * -1;
                    }
                    else{
                        $txtclsdays90 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days90plus>=1)
                    {
                        $txtclsdays90plus = "text-danger";
                        $days90plus =  $days90plus * -1;
                    }
                    else{
                        $txtclsdays90plus = "";
                    }
                    $days30_total += $days30;
                    $days60_total += $days60;
                    $days90_total += $days90;
                    $days90plus_total += $days90plus;
                    $grand = $days30 + $days60 + $days90 + $days90plus;
                    $colwise_total += $grand;

                    $net_days30_total += $days30;
                    $net_days60_total += $days60;
                    $net_days90_total += $days90;
                    $net_days90plus_total  += $days90plus;
                    $net_colwise_total += $grand;
                    $net_amount += $subtotal;
                    $net_paid += $payment_recieved_amount;

                    fputcsv($output, [$invoicetid, $invoice_date, $invoice_due_date, $subtotal, $payment_recieved_amount,$config_currency,  $days30, $days60, $days90, $days90plus, $grand]);
                }
               
                
            }
            
            // fputcsv($output, ['Total', '', '', $grand_total, $paid_total, '', $days30_total, $days60_total, $days90_total, $days90plus_total, $colwise_total]);
        }
        fputcsv($output, ['', '', '', '', '', '', '', '', '', '', '']);
        fputcsv($output, ['Net Total', '', '', $net_amount, $net_paid, '', $net_days30_total, $net_days60_total, $net_days90_total, $net_days90plus_total, $net_colwise_total]);
        
        fclose($output);
        exit;
    }


    public function export_to_excel_for_aged_payable()
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        $filename = 'aged_payable_report_' . date('dmYHis') . '.csv';

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
        fputcsv($output, ['Supplier', 'Created Date','Due Date', 'Amount', 'Paid Amount', 'Currency',   '1-30 Days', '31-60 Days', '61-90 Days', '90 Days', 'Total']);
        $customer_list = $this->reports->supplier_lists();
        $net_days30_total =0;
        $net_days60_total =0;
        $net_days90_total =0;
        $net_days90plus_total =0;
        $net_amount=0;
        $net_paid = 0;
        $net_colwise_total = 0;
        foreach ($customer_list as $row) {
            $customerId = $row['id'];
            fputcsv($output, [$row['company'], '', '', '', '', '', '', '', '', '', '']);    
            $days30_total = $days60_total = $days90_total = $days90plus_total = $colwise_total = 0;
            $paymentresults = $this->reports->payment_list_for_suppliers_byid($row['id']);
            $grand_total = 0;
            $paid_total = 0;
            if(!empty($paymentresults))
            {
                // echo "<pre>"; print_r($paymentresults); die();
                foreach($paymentresults as $item){

                    $invoicetid = $item['invoice_number'];
                  
                    $invoice_date = !empty($item['invoicedate']) ? date('d-m-Y', strtotime($item['invoicedate'])) : '';
                    $subtotal = $item['total'];
                    $payment_recieved_amount = $item['pamnt'];
                    $grand_total += $subtotal;
                    $paid_total += $payment_recieved_amount;
                   
                    $invoice_due_date = !empty($item['invoiceduedate']) ? date('d-m-Y', strtotime($item['invoiceduedate'])) : '';
                    $days30 = $item['total_1_30_days'] > 0 ? $item['total_1_30_days'] : 0.00;
                    $days60 = $item['total_31_60_days'] > 0 ? $item['total_31_60_days'] : 0.00;
                    $days90 = $item['total_61_90_days'] > 0 ? $item['total_61_90_days'] : 0.00;
                    $days90plus = $item['total_above_90_days'] > 0 ? $item['total_above_90_days'] : 0.00;
                
                    $status = $item['status'];
                    if(($status== 'due' || $status==  'partial') && $days30>=1)
                    {
                        $txtcls1 = "text-danger";
                        $days30 =  $days30 * -1;
                        
                    }
                    else{
                        $txtcls1 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days60>=1)
                    {
                        $txtclsdays60 = "text-danger";
                        $days60 =  $days60 * -1;
                    }
                    else{
                        $txtclsdays60 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days90>=1)
                    {
                        $txtclsdays90 = "text-danger";
                        $days90 =  $days90 * -1;
                    }
                    else{
                        $txtclsdays90 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days90plus>=1)
                    {
                        $txtclsdays90plus = "text-danger";
                        $days90plus =  $days90plus * -1;
                    }
                    else{
                        $txtclsdays90plus = "";
                    }
                    $days30_total += $days30;
                    $days60_total += $days60;
                    $days90_total += $days90;
                    $days90plus_total += $days90plus;
                    $grand = $days30 + $days60 + $days90 + $days90plus;
                    $colwise_total += $grand;

                    $net_days30_total += $days30;
                    $net_days60_total += $days60;
                    $net_days90_total += $days90;
                    $net_days90plus_total  += $days90plus;
                    $net_colwise_total += $grand;
                    $net_amount += $subtotal;
                    $net_paid += $payment_recieved_amount;

                    fputcsv($output, [$invoicetid, $invoice_date, $invoice_due_date, $subtotal, $payment_recieved_amount,$config_currency,  $days30, $days60, $days90, $days90plus, $grand]);
                }
               
                
            }
            
            // fputcsv($output, ['Total', '', '', $grand_total, $paid_total, '', $days30_total, $days60_total, $days90_total, $days90plus_total, $colwise_total]);
        }
        fputcsv($output, ['', '', '', '', '', '', '', '', '', '', '']);
        fputcsv($output, ['Net Total', '', '', $net_amount, $net_paid, '', $net_days30_total, $net_days60_total, $net_days90_total, $net_days90plus_total, $net_colwise_total]);
        
        fclose($output);
        exit;
    }

    public function export_to_excel1()
    {
        $filename = 'invoice_report_' . date('Y-m-d') . '.csv';

        // Set the headers to force download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
         // Open output stream
        $output = fopen('php://output', 'w');
        $uppaid_customers = $this->reports->customer_list();
        if(!empty($uppaid_customers))
        {
            $data['customer_list'] = $uppaid_customers;
            foreach ($uppaid_customers as $key => $value) {
                $paymentresults = $this->reports->invoice_list_for_compay_by_id($value['id']);
                if(!empty($paymentresults))
                {
               
                    foreach($paymentresults as $row){
                        $resarray[][$value['id']] = array(
                            'company' => (!empty($value['company']))?$value['company']:$value['name'],
                            'invoiceid' => $row['id'],
                            'invoicetid' => $row['tid'],
                            'invoice_date' => $row['invoicedate'],
                            'invoice_due_date' => $row['invoiceduedate'],
                            'subtotal' => $row['subtotal'],
                            'status' => $row['status'],
                            'today_total' => $row['today_total'],
                            '30days' => $row['total_1_30_days'],
                            '60days' => $row['total_31_60_days'],
                            '90days' => $row['total_61_90_days'],
                            '90plus' => $row['total_above_90_days'],
                        );
                    }
                    
                }
                
            }
        }
        $paymentlist = $resarray;
        
        // Write the CSV header
        fputcsv($output, ['Company', 'Invoice Date', 'Amount', 'Currency', 'Account', 'Due Date', '1-30 Days', '31-60 Days', '61-90 Days', '90 Days', 'Total']);
        $customer_list = $this->reports->customer_list();
        foreach ($customer_list as $row) {
            $customerId = $row['id'];
            fputcsv($output, [$row['company'], '', '', '', '', '', '', '', '', '', '']);    
            $days30_total = $days60_total = $days90_total = $days90plus_total = $colwise_total = 0;
            $paymentresults = $this->reports->invoice_list_for_compay_by_id($value['id']);
            if(!empty($paymentresults))
            {
           
                foreach($paymentresults as $item){

                    $invoicetid = $item['invoicetid'];
                    $invoice_date = !empty($item['invoice_date']) ? date('d-m-Y', strtotime($item['invoice_date'])) : '';
                    $subtotal = $item['subtotal'];
                    $invoice_due_date = !empty($item['invoice_due_date']) ? date('d-m-Y', strtotime($item['invoice_due_date'])) : '';
                    $days30 = $item['30days'] > 0 ? $item['30days'] : 0.00;
                    $days60 = $item['60days'] > 0 ? $item['60days'] : 0.00;
                    $days90 = $item['90days'] > 0 ? $item['90days'] : 0.00;
                    $days90plus = $item['90plus'] > 0 ? $item['90plus'] : 0.00;
                
                    $status = $item['status'];
                    if(($status== 'due' || $status==  'partial') && $days30>=1)
                    {
                        $txtcls1 = "text-danger";
                        $days30 =  $days30 * -1;
                        
                    }
                    else{
                        $txtcls1 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days60>=1)
                    {
                        $txtclsdays60 = "text-danger";
                        $days60 =  $days60 * -1;
                    }
                    else{
                        $txtclsdays60 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days90>=1)
                    {
                        $txtclsdays90 = "text-danger";
                        $days90 =  $days90 * -1;
                    }
                    else{
                        $txtclsdays90 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days90plus>=1)
                    {
                        $txtclsdays90plus = "text-danger";
                        $days90plus =  $days90plus * -1;
                    }
                    else{
                        $txtclsdays90plus = "";
                    }
                }
                
            }

            foreach ($paymentlist as $key => $list) {
                if (isset($paymentlist[$key][$customerId])) {
                    $invoicetid = $list[$customerId]['invoicetid'];
                    $invoice_date = !empty($list[$customerId]['invoice_date']) ? date('d-m-Y', strtotime($list[$customerId]['invoice_date'])) : '';
                    $subtotal = $list[$customerId]['subtotal'];
                    $invoice_due_date = !empty($list[$customerId]['invoice_due_date']) ? date('d-m-Y', strtotime($list[$customerId]['invoice_due_date'])) : '';
                    $days30 = $list[$customerId]['30days'] > 0 ? $list[$customerId]['30days'] : 0.00;
                    $days60 = $list[$customerId]['60days'] > 0 ? $list[$customerId]['60days'] : 0.00;
                    $days90 = $list[$customerId]['90days'] > 0 ? $list[$customerId]['90days'] : 0.00;
                    $days90plus = $list[$customerId]['90plus'] > 0 ? $list[$customerId]['90plus'] : 0.00;
    
                    $status = $list[$customerId]['status'];
                    if(($status== 'due' || $status==  'partial') && $days30>=1)
                    {
                        $txtcls1 = "text-danger";
                        $days30 =  $days30 * -1;
                        
                    }
                    else{
                        $txtcls1 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days60>=1)
                    {
                        $txtclsdays60 = "text-danger";
                        $days60 =  $days60 * -1;
                    }
                    else{
                        $txtclsdays60 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days90>=1)
                    {
                        $txtclsdays90 = "text-danger";
                        $days90 =  $days90 * -1;
                    }
                    else{
                        $txtclsdays90 = "";
                    }
                    if(($status== 'due' || $status==  'partial') && $days90plus>=1)
                    {
                        $txtclsdays90plus = "text-danger";
                        $days90plus =  $days90plus * -1;
                    }
                    else{
                        $txtclsdays90plus = "";
                    }
                    // Calculate totals
                    $days30_total += $days30;
                    $days60_total += $days60;
                    $days90_total += $days90;
                    $days90plus_total += $days90plus;
                    $grand = $days30 + $days60 + $days90 + $days90plus;
                    $colwise_total += $grand;
    
                    // Write invoice row
                    fputcsv($output, [$invoicetid, $invoice_date, $subtotal, $config_currency, '', $invoice_due_date, $days30, $days60, $days90, $days90plus, $grand]);
                }
            }
    
            // Write totals for the customer
            fputcsv($output, ['Total', '', '', '', '', '', $days30_total, $days60_total, $days90_total, $days90plus_total, $colwise_total]);
        }

        
        fclose($output);
        exit;
    }


    public function stock_report()
    {
       
        $data = [];
        $data['permissions'] = load_permissions('Stock','Reports','Stock Report');
        $head['title'] = "Stock Report List";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouses'] = $this->stockreport->warehouses();
        $data['categories'] = $this->stockreport->categories();
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/stock_report', $data);
        $this->load->view('fixed/footer');
    }

    public function stock_ajax_list()
    {
        $eid = 0;
        // if ($this->aauth->premission(9)) {
        //     $eid = $this->input->post('eid');
        // }
    
        $list = $this->stockreport->get_datatables($eid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $totalvalue = number_format($invoices->qty * $invoices->product_price, 2);
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url("products/edit?id=$invoices->pid") . '">&nbsp; ' . $invoices->product_code . '</a>';
            $row[] = '<a href="' . base_url("products/edit?id=$invoices->pid") . '">'.$invoices->product_name. '</a>';
            $row[] = $invoices->title;
            $row[] = $invoices->product_cost;
            $row[] = $invoices->product_price;
            $row[] = $invoices->qty;
            $row[] = $totalvalue;
            $row[] = "";
            $row[] = "";
            $productnamewithcode = $invoices->product_name."(".$invoices->product_code.")";
            $row[] = '<span><button onclick="single_product_direct_details(' . $invoices->pid . ')" type="button" class="btn btn-sm btn-secondary" title="Location Stock List">Info <i class="fa fa-info"></i></button> &nbsp;<button onclick="single_product_stock_direct(\'' . $invoices->pid . '\', \'' . addslashes($productnamewithcode) . '\')" type="button" class="btn btn-sm btn-secondary" title="Location Stock List">Location <i class="fa fa-history   "></i></button></span>';
            
            $data[] = $row;
        }
    
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->stockreport->count_all($eid),
            "recordsFiltered" => $this->stockreport->count_filtered($eid),
            "data" => $data,
        );
    
        // Output the JSON response
        echo json_encode($output);
    }
    public function salesorder_ajax_list()
    {
        $eid = 0;
        // if ($this->aauth->premission(9)) {
        //     $eid = $this->input->post('eid');
        // }
    
        $list = $this->salesorderreport->get_datatables($eid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            if(($invoices->converted_status=='0') && ($prdstatus==1)){
                $status = $this->lang->line('Completed');
            }
            else if(($invoices->converted_status=='2') && ($prdstatus==1)){
                $status =  $this->lang->line('Completed');
            }
            else if(($invoices->converted_status=='2') && ($prdstatus!=1)){
                $status = $this->lang->line('Partially Converted');
            }
            else if(($invoices->converted_status=='0') && ($prdstatus!=1)){
                $status = $this->lang->line('Not Converted');
            }
            else if(($invoices->converted_status=='3')){
                $status = $this->lang->line('Assign for Delivery');
            }
            else{
                $status = $this->lang->line('Converted');
            }
            $no++;
            $row = array();
            $row[] = $no;
            
          
            $row[] = date('d-m-Y',strtotime($invoices->invoicedate));
            $row[] = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->id&token=3") . '">' . $invoices->salesorder_number . '</a>';
            $row[] = $invoices->name;
            $row[] = $invoices->product_code;
            $row[] = $invoices->product_name;
            $row[] = "";
            $row[] = $invoices->subtotal;
            $row[] = $status;
            $row[] = $invoices->status;
            // $row[] = ($invoices->status=='Invoiced')?'Invoiced':'Not Invoiced';
            
            $data[] = $row;
        }
    
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->salesorderreport->count_all($eid),
            "recordsFiltered" => $this->salesorderreport->count_filtered($eid),
            "data" => $data,
        );
    
        // Output the JSON response
        echo json_encode($output);
    }

    //----purchase order---------------------
    public function purchaseorder_ajax_list()
    {
        $eid = 0;
        // if ($this->aauth->premission(9)) {
        //     $eid = $this->input->post('eid');
        // }
    
        $list = $this->salesorderreport->get_datatables_purchase($eid);

       //print_r($list);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            
            $no++;
            $row = array();
            $row[] = $no;
            
          
            $row[] = dateformat_time($invoices->sent_date);           
            $row[] = $invoices->product_code;
            $row[] = $invoices->product;
            $row[] = $invoices->qty;            
            $row[] = $invoices->price;
            $row[] = $invoices->suppliername;
            $row[] = $invoices->supplierphone;            
            $row[] = $invoices->supplieraddress;
            $row[] = $invoices->addedname;
            $row[] = $invoices->approvename;            
            $row[] = $invoices->sentbyname;
           
            // $row[] = ($invoices->status=='Invoiced')?'Invoiced':'Not Invoiced';
            
            $data[] = $row;
        }
    
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->salesorderreport->count_all_purchase($eid),
            "recordsFiltered" => $this->salesorderreport->count_filtered_purchase($eid),
            "data" => $data,
        );
    
        // Output the JSON response
        echo json_encode($output);
    }


    //-----------end-------------------------
    

    public function sales_orders_report()
    {
        
        $data = [];
        $data['permissions'] = load_permissions('Sales','Reports','Sales Orders Report');
        $head['title'] = "Sales Orders Report";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouses'] = $this->stockreport->warehouses();
        $data['categories'] = $this->stockreport->categories();
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/sales_orders_report', $data);
        $this->load->view('fixed/footer');
    }

    public function purchase_orders_report()
    {
        
        $data = [];
        $head['title'] = "Purchase Orders Report";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouses'] = $this->stockreport->warehouses();
        $data['categories'] = $this->stockreport->categories();        
        $data['permissions'] = load_permissions('Stock','Reports','Open Purchase Orders');
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/purchase_orders_report', $data);
        $this->load->view('fixed/footer');
    }





    public function stock_ajax_list1() {

        $eid = 0;
        // if ($this->aauth->premission(9)) {
        //     $eid = $this->input->post('eid');
        // }
    
        // Fetch the data from the model
        $list = $this->stockreport->get_datatables($eid);
        
        // Initialize an empty array to store data
        $data = array();
        $no = $this->input->post('start');
        
        foreach ($list as $invoices) {
            $totalvalue = number_format($invoices->qty * $invoices->product_price, 2);
            $no++;
            
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url("products/edit?id=$invoices->pid") . '">&nbsp; ' . $invoices->product_code . '</a>';
            $row[] = '<a href="' . base_url("products/edit?id=$invoices->pid") . '">'.$invoices->product_name. '</a>';
            $row[] = $invoices->product_cost;
            $row[] = $invoices->product_price;
            $row[] = $invoices->qty;
            $row[] = $totalvalue;
            $row[] = "";
            $row[] = "";
            $productnamewithcode = $invoices->product_name . "(" . $invoices->product_code . ")";
            $row[] = '<span><button onclick="single_product_direct_details(' . $invoices->pid . ')" type="button" class="btn btn-sm btn-secondary" title="Location Stock List">Info <i class="fa fa-info"></i></button> &nbsp;<button onclick="single_product_stock_direct(\'' . $invoices->pid . '\', \'' . addslashes($productnamewithcode) . '\')" type="button" class="btn btn-sm btn-secondary" title="Location Stock List">Location <i class="fa fa-history"></i></button></span>';
    
            $data[] = $row;
        }
    
        // Prepare the output with 'data' field
        $output = array(
            "draw" => intval($this->input->post('draw')),  // Ensure 'draw' is an integer
            "recordsTotal" => $this->stockreport->count_all($eid),
            "recordsFiltered" => $this->stockreport->count_filtered($eid),
            "data" => $data  // Ensure that 'data' is always populated with an array
        );
    
        // Output in JSON format
        echo json_encode($output);
    }
    
    public function stock_to_pdf() {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '60000000'); 
        // $category = $this->input->post('category');
        // $warehouse = $this->input->post('warehouse');
        
        $stocklist = $this->stockreport->stock_list_pdf($warehouse="",$category="");
        $data['stocklist'] = $stocklist;
        $html .= '<table>
        <thead>
            <tr>               
                <th style="font-size: 12px;">Product Code</th>
                <th style="font-size: 12px;text-align:center;">Product Name</th>
                <th style="font-size: 12px;text-align:center;">Unit Cost</th>
                <th style="font-size: 12px;text-align:center;">Selling Price</th>
                <th style="font-size: 12px;text-align:center;">On Hand</th>
                <th style="font-size: 12px;text-align:center;"> Total Value</th>
                <th style="font-size: 12px;text-align:center;"> Purchase<br>Orders</th>
                <th style="font-size: 12px;text-align:center;"> Customer<br>Sales Orders</th>
            </tr>
        </thead>
        <tbody>';
        if(!empty($stocklist))
            {
                $i=1;
                foreach($stocklist as $row)
                    {
                        
                        $totalvalue = number_format($row['qty'] * $row['product_cost'], 2);
                        $html .= '<tr>                           
                                <td style="font-size: 11px;border:1px solid #ccc;">'.$row['product_code'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['product_name'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['product_cost'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['product_price'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['qty'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$totalvalue.'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;"></td>            
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;"></td>            
                            </tr>';
                            $i++;
                    }
            }
            $html .= '</tbody>';
            $html .= '</table>';
    
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('stock-report' . $pay_acc . '.pdf', 'I');  
        
    }

    //erp2024 24-12-2024 reports starts
    public function balance_sheet_report()
    {
        
        $data = [];
        $head['title'] = "Balance Sheet";

        $this->load->model('accounts_model', 'accounts');
        
        $result = $this->reports->balance_sheet_report();
        $nestedArray = [];
        $headerSums = [];
        $typeSums = [];
        $accountparent=[];

        // $accounts_data = [];
        // $accountparent=[];
        // foreach($trail_accounts as $row)
        // {
        //     $accounts_data[$row['coa_header_id']][] = $row;
        //     if($row['parent_account_id'])
        //     {                
        //         $parent_account_details = $this->reports->load_parent_by_id($row['parent_account_id']);
        //         $row['parent_account_number'] = $parent_account_details['acn'];
        //         $row['parent_account_name']   = $parent_account_details['holder'];
        //         $accountparent[$row['coa_header_id']][$row['parent_account_id']][] = $row;
        //     }
        // }
       
        // $data['accountparent'] = $accountparent;

        foreach ($result as $row) {
            $headerId = $row['coa_header_id'];
            $typeId = $row['coa_type_id'];
            $amount = abs($row['lastbal']);

            // Ensure the structure exists
            if (!isset($nestedArray[$headerId])) {
                $nestedArray[$headerId] = [];
                $headerSums[$headerId] = 0; // Initialize sum for header
            }
            if (!isset($nestedArray[$headerId][$typeId])) {
                $nestedArray[$headerId][$typeId] = [];
                $typeSums[$typeId] = 0; // Initialize sum for type
            }

            // Add account details
            $nestedArray[$headerId][$typeId][] = [
                'account_header' => $row['coa_header'],
                'account_type' => $row['typename'],
                'code' => $row['acn'],
                'account_name' => $row['holder'],
                'amount' => $amount,
                'parent_account_id' =>$row['parent_account_id'],
            ];


            if($row['parent_account_id'])
            {                
                $parent_account_details = $this->reports->load_parent_by_id($row['parent_account_id']);
                $accountparent[$headerId][$typeId][$row['parent_account_id']][] = 
                [
                    'account_header' => $row['coa_header'],
                    'account_type' => $row['typename'],
                    'code' => $row['acn'],
                    'account_name' => $row['holder'],
                    'amount' => $amount,
                    'parent_account_number' => $parent_account_details['acn'],
                    'parent_account_name' => $parent_account_details['holder'],
                ];
            }
            
            // Accumulate sums
            if($row['typename']=='Contra-Asset')
            {
                $headerSums[$headerId] -= $amount;
            }
            else{
                $headerSums[$headerId] += $amount;
            }
            
            $typeSums[$typeId] += $amount;
        }
        $data['accountparent'] = $accountparent;
        // echo "<pre>"; print_r($accountparent); 
        // echo "<pre>"; print_r($nestedArray); die();
        $data['nestedArray'] = $nestedArray;
        $data['headerSums'] = $headerSums;
        $data['typeSums'] = $typeSums;  
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/balance_sheet_report', $data);
        $this->load->view('fixed/footer');
    }

   public function balance_sheet_to_excel()
   {
       $data = [];
       set_time_limit(0);
       ini_set('memory_limit', '20000M');
       ini_set('max_execution_time', '600000'); 
       $filename = 'balance_sheet_report_' . date('Y-m-d') . '.csv';
 
       // Set the headers to force download
       header('Content-Type: text/csv');
       header('Content-Disposition: attachment; filename="' . $filename . '"');
       header('Pragma: no-cache');
       header('Expires: 0');
        // Open output stream
       $output = fopen('php://output', 'w');
       fputcsv($output, ['Balance Sheet - '.date('d-M-Y')]);
       fputcsv($output, ['']);
       $result = $this->reports->balance_sheet_report();
       $nestedArray = [];
       $headerSums = [];
       $typeSums = [];

       foreach ($result as $row) {
           $headerId = $row['coa_header_id'];
           $typeId = $row['coa_type_id'];
           $amount = $row['lastbal'];

           // Ensure the structure exists
           if (!isset($nestedArray[$headerId])) {
               $nestedArray[$headerId] = [];
               $headerSums[$headerId] = 0; // Initialize sum for header
           }
           if (!isset($nestedArray[$headerId][$typeId])) {
               $nestedArray[$headerId][$typeId] = [];
               $typeSums[$typeId] = 0; // Initialize sum for type
           }

           // Add account details
           $nestedArray[$headerId][$typeId][] = [
               'account_header' => $row['coa_header'],
               'account_type' => $row['typename'],
               'code' => $row['acn'],
               'account_name' => $row['holder'],
               'amount' => $amount,
           ];

           // Accumulate sums
           $headerSums[$headerId] += $amount;
           $typeSums[$typeId] += $amount;
       }

       if(!empty($nestedArray))
       {  
           $assettotal = 0;
           $liabilitytotal =0;
           foreach ($nestedArray as $headerId => $types)
           {
               if($types[array_key_first($types)][0]['account_header']=='Assets')
               {
                   $assettotal += $headerSums[$headerId];
               }
               else if($types[array_key_first($types)][0]['account_header']=='Liabilities'){
                   $liabilitytotal += $headerSums[$headerId];
               }
               fputcsv($output, [$types[array_key_first($types)][0]['account_header'],'','',$headerSums[$headerId]]);

               foreach ($types as $typeId => $accounts)
               {
                
                   fputcsv($output, ['',$accounts[0]['account_type'],'',$typeSums[$typeId]]);
                   foreach ($accounts as $account)
                   {
                       $code = $account['code'];    
                       fputcsv($output, ['','',$account['account_name'],$account['amount']]);   
                   }
                   fputcsv($output, ['','','','']);
               }
           }
           $equitytotal =  $assettotal - $liabilitytotal;
           fputcsv($output, ['Equity','','',$equitytotal]);
       } 
       fclose($output); 
       exit;
   }
 
    public function balance_sheet_to_pdf()
    {
        ini_set('memory_limit', '64M');
        $data = [];
        $head['title'] = "Balance Sheet";
        $nestedArray = [];
        $headerSums = [];
        $typeSums = [];

        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        $result = $this->reports->balance_sheet_report();
        foreach ($result as $row) {
            $headerId = $row['coa_header_id'];
            $typeId = $row['coa_type_id'];
            // $amount = $row['lastbal'];
            $amount = abs($row['lastbal']);
            // Ensure the structure exists
            if (!isset($nestedArray[$headerId])) {
                $nestedArray[$headerId] = [];
                $headerSums[$headerId] = 0; // Initialize sum for header
            }
            if (!isset($nestedArray[$headerId][$typeId])) {
                $nestedArray[$headerId][$typeId] = [];
                $typeSums[$typeId] = 0; // Initialize sum for type
            }

            // Add account details
            $nestedArray[$headerId][$typeId][] = [
                'account_header' => $row['coa_header'],
                'account_type' => $row['typename'],
                'code' => $row['acn'],
                'account_name' => $row['holder'],
                'amount' => $amount,
            ];

            // Accumulate sums
            if($row['typename']=='Contra-Asset')
            {
                $headerSums[$headerId] -= $amount;
            }
            else{
                $headerSums[$headerId] += $amount;
            }
            // Accumulate sums
            // $headerSums[$headerId] += $amount;
            $typeSums[$typeId] += $amount;
        }

        $data['nestedArray'] = $nestedArray;
        $data['headerSums'] = $headerSums;
        $data['typeSums'] = $typeSums;  

        $html = $this->load->view('reports/balancesheetprintpdf-' . LTR, $data, true);
           
        
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('balance-sheet-report' . $pay_acc . '.pdf', 'I');
    }


    public function general_ledger()
    {
        $data = [];
        $head['title'] = "General Ledger";
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        $this->load->model('transactions_model', 'transactions');   
        $coa_accounts = $this->reports->coa_accounts_with_transactions();
        $transactions = [];
        if(!empty($coa_accounts))
        {
            foreach ($coa_accounts as $row) {
                $results = $this->transactions->load_account_transactions_by_code($row['acn']);
                $transactions[$row['acn']] = $results;
            }
        }
        $data['transaction_records'] = $transactions;
        $data['coa_accounts'] = $coa_accounts;

        

        $this->load->view('fixed/header', $head);
        $this->load->view('reports/general_ledger_report', $data);
        $this->load->view('fixed/footer');
    }

    public function general_ledger_to_pdf()
    {
        ini_set('memory_limit', '64M');
        $data = [];
        $head['title'] = "General Ledger";
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;

        $this->load->model('transactions_model', 'transactions');   
        $coa_accounts = $this->reports->coa_accounts_with_transactions();
        $transactions = [];
        if(!empty($coa_accounts))
        {
            foreach ($coa_accounts as $row) {
                $results = $this->transactions->load_account_transactions_by_code($row['acn']);
                $transactions[$row['acn']] = $results;
            }
        }
        $data['transaction_records'] = $transactions;
        $data['coa_accounts'] = $coa_accounts;

        $html = $this->load->view('reports/generalledgerprintpdf-' . LTR, $data, true);   
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('balance-sheet-report' . $pay_acc . '.pdf', 'I');
        // $this->load->view('fixed/header', $head);
        // $this->load->view('reports/balance_sheet_report_pdf', $data);
        // $this->load->view('fixed/footer');
    }

    public function general_ledger_to_csv()
    {
        $this->load->model('transactions_model', 'transactions');

        $coa_accounts = $this->reports->coa_accounts_with_transactions();
        $transactions = [];

        if (!empty($coa_accounts)) {
            foreach ($coa_accounts as $row) {
                $results = $this->transactions->load_account_transactions_by_code($row['acn']);
                $transactions[$row['acn']] = $results;
            }
        }

        // Set the CSV headers
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="general-ledger.csv"');

        $output = fopen('php://output', 'w');

        // Optional: Write a title row
        fputcsv($output, ['General Ledger Report']);

        // Loop through each account and its transactions
        foreach ($coa_accounts as $account) {
            $account_code = $account['acn'];
            $account_name = $account['name'];

            fputcsv($output, []); // Empty row for spacing
            fputcsv($output, ["Account: $account_code - $account_name"]);
            fputcsv($output, ['Date', 'Transaction ID', 'Description', 'Debit', 'Credit', 'Balance']);

            if (!empty($transactions[$account_code])) {
                foreach ($transactions[$account_code] as $txn) {
                    fputcsv($output, [
                        $txn['date'],
                        $txn['transaction_id'],
                        $txn['description'],
                        $txn['debit'],
                        $txn['credit'],
                        $txn['balance']
                    ]);
                }
            } else {
                fputcsv($output, ['No transactions found']);
            }
        }

        fclose($output);
        exit; // Ensure no extra output is sent
    }


    public function profit_and_loss()
    {
        $data = [];
        $head['title'] = "Profit & Loss";
        
        $this->load->model('transactions_model', 'transactions');   
        $data['income']  = $this->reports->coa_accounts_total_transaction_amount('Income',date('Y'));
        $data['expense']  = $this->reports->coa_accounts_total_transaction_amount('Expenses',date('Y'));
       
        // echo "<pre>"; print_r($data['income']); die();

        $this->load->view('fixed/header', $head);
        $this->load->view('reports/profit_and_loss_report', $data);
        $this->load->view('fixed/footer');
    }
    public function profit_and_loss_to_prf()
    {
        ini_set('memory_limit', '64M');
        $data = [];
        $head['title'] = "Profit & Loss Report";
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;
        $head['title'] = "Profit & Loss";
        
     
        $data['income']  = $this->reports->coa_accounts_total_transaction_amount('Income',date('Y'));
        $data['expense']  = $this->reports->coa_accounts_total_transaction_amount('Expenses',date('Y'));

        $html = $this->load->view('reports/profitandlossprintpdf-' . LTR, $data, true);   
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        // $pdf->AddPage('L');
        $pdf->WriteHTML($html);       
        $pdf->Output('balance-sheet-report' . $pay_acc . '.pdf', 'I');
    }

    public function profit_and_loss_to_excel()
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        $filename = 'profit_and_loss_excel_' . date('dmYHis') . '.csv';
  
        // Set the headers to force download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
         // Open output stream
        $output = fopen('php://output', 'w');
        
        // Write the CSV header
        
        fputcsv($output, [
            '',
            'Jan - Mar (' . date('Y') . ')',
            'Apr - Jun (' . date('Y') . ')',
            'Jul - Sep (' . date('Y') . ')',
            'Oct - Dec (' . date('Y') . ')',
            'Total'
        ]);
        
        $income  = $this->reports->coa_accounts_total_transaction_amount('Income',date('Y'));
        $expense  = $this->reports->coa_accounts_total_transaction_amount('Expenses',date('Y'));
        $grand_income_first = 0;
        $grand_income_second = 0;
        $grand_income_third = 0;
        $grand_income_fourth = 0;
        $grand_income_total = 0;
        $grand_expense_first = 0;
        $grand_expense_second = 0;
        $grand_expense_third = 0;
        $grand_expense_fourth = 0;
        $grand_expense_total = 0;    

        $grand_income_first_debit = 0;
        $grand_income_second_debit = 0;
        $grand_income_third_debit = 0;
        $grand_income_fourth_debit = 0;
        $grand_income_total_debit = 0;

        $grand_income_first = 0;
        $grand_income_second = 0;
        $grand_income_third = 0;
        $grand_income_fourth = 0;
        $grand_income_total = 0; 

        $grand_expense_first_credit = 0;
        $grand_expense_second_credit = 0;
        $grand_expense_third_credit = 0;
        $grand_expense_fourth_credit = 0;
        $grand_expense_total_credit = 0;

        $grand_expense_first_debit = 0;
        $grand_expense_second_debit = 0;
        $grand_expense_third_debit = 0;
        $grand_expense_fourth_debit = 0;
        $grand_expense_total_debit = 0;


        if($income)
        {
            
            fputcsv($output, ['Income','','','','','']);  
            fputcsv($output, ['','','','','','']);  
            foreach ($income as $key => $value) {
                $account_id = $value['account_id'];
                $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                $second = ($value['quarter_label']=='Second') ? abs($value['amount']):0.00;
                $third = ($value['quarter_label']=='Third') ? abs($value['amount']):0.00;
                $fourth = ($value['quarter_label']=='Fourth') ? abs($value['amount']):0.00;
                $total = ($first+$second+$third+$fourth);
                $holder = $value['holder'];

                if($value['transtype']=='credit')
                {
                    $grand_expense_first_credit += $first;
                    $grand_expense_second_credit += $second;
                    $grand_expense_third_credit += $third;
                    $grand_expense_fourth_credit += $fourth;
                    $grand_expense_total_credit += $total;
                    $type = 'C';
                }
                else{
                    $grand_expense_first_debit += $first;
                    $grand_expense_second_debit += $second;
                    $grand_expense_third_debit += $third;
                    $grand_expense_fourth_debit += $fourth;
                    $grand_expense_total_debit += $total;
                    $type = 'D';
                }

                fputcsv($output, [$holder,$first,$second,$third,$fourth,$total]);  
               
            }
            $grand_income_first  = abs($grand_income_first_debit   - $grand_income_first_credit);   
            $grand_income_second = abs($grand_income_second_debit  - $grand_income_second_credit);  
            $grand_income_third  = abs($grand_income_third_debit   - $grand_income_third_credit);  
            $grand_income_fourth = abs($grand_income_fourth_debit  - $grand_income_fourth_credit); 
            $grand_income_total  = abs($grand_income_total_debit   - $grand_income_total_credit);
            fputcsv($output, ['Total',$grand_income_first,$grand_income_second,$grand_income_third,$grand_income_fourth,$grand_income_total]);  
        }
        fputcsv($output, ['','','','','','']);  
  
        if($expense)
        {
            foreach ($expense as $key => $value) {
               $account_id = $value['account_id'];
               $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
               $second = ($value['quarter_label']=='Second') ? abs($value['amount']):0.00;
               $third = ($value['quarter_label']=='Third') ? abs($value['amount']):0.00;
               $fourth = ($value['quarter_label']=='Fourth') ? abs($value['amount']):0.00;
               $total = ($first+$second+$third+$fourth);

               if($value['transtype']=='credit')
               {
                   $grand_expense_first_credit += $first;
                   $grand_expense_second_credit += $second;
                   $grand_expense_third_credit += $third;
                   $grand_expense_fourth_credit += $fourth;
                   $grand_expense_total_credit += $total;
               }
               else{
                   $grand_expense_first_debit += $first;
                   $grand_expense_second_debit += $second;
                   $grand_expense_third_debit += $third;
                   $grand_expense_fourth_debit += $fourth;
                   $grand_expense_total_debit += $total;
               }
               fputcsv($output, [$holder,$first,$second,$third,$fourth,$total]);  
            }
            
            $grand_expense_first  = abs($grand_expense_first_debit   - $grand_expense_first_credit);   
            $grand_expense_second = abs($grand_expense_second_debit  - $grand_expense_second_credit);  
            $grand_expense_third  = abs($grand_expense_third_debit   - $grand_expense_third_credit);  
            $grand_expense_fourth = abs($grand_expense_fourth_debit  - $grand_expense_fourth_credit); 
            $grand_expense_total  = abs($grand_expense_total_debit   - $grand_expense_total_credit);
            fputcsv($output, ['Total',$grand_expense_first,$grand_expense_second,$grand_expense_third,$grand_expense_fourth,$grand_expense_total]);  
        }

        fputcsv($output, ['','','','','','']); 
        fputcsv($output, ['Net Profit',($grand_income_first-$grand_expense_first),($grand_income_second-$grand_expense_second),($grand_income_third-$grand_expense_third),($grand_income_fourth-$grand_expense_fourth),($grand_income_total-$grand_expense_total)]);  
        fclose($output);
        exit;
    }

    public function trial_balance()
    {
        $data = [];
        $head['title'] = "Trial Balance";
        
        $this->load->model('transactions_model', 'transactions');   
        $data['trail_account_headers'] = $this->reports->trail_balance_account_headers();
        $trail_accounts = $this->reports->trail_balance_details();
        $accounts_data = [];
        $accountparent=[];
        foreach($trail_accounts as $row)
        {
            $accounts_data[$row['coa_header_id']][] = $row;
            if($row['parent_account_id'])
            {                
                $parent_account_details = $this->reports->load_parent_by_id($row['parent_account_id']);
                $row['parent_account_number'] = $parent_account_details['acn'];
                $row['parent_account_name']   = $parent_account_details['holder'];
                $accountparent[$row['coa_header_id']][$row['parent_account_id']][] = $row;
            }
        }
       
        $data['accountparent'] = $accountparent;

        $data['trail_account_details'] = $accounts_data;
        // echo "<pre>"; print_r($data['trail_account_details']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/trial_balance', $data);
        $this->load->view('fixed/footer');
    }

    // public function trial_balance_old()
    // {
    //     $data = [];
    //     $head['title'] = "Trial Balance";
        
    //     $this->load->model('transactions_model', 'transactions');   
    //     $data['trail_account_headers'] = $this->reports->trail_balance_account_headers();
    //     $trail_accounts = $this->reports->trail_balance_details();
    //     $accounts_data = [];
    //     foreach($trail_accounts as $row)
    //     {
    //         $accounts_data[$row['coa_header_id']][] = $row;
    //     }
    //     $data['trail_account_details'] = $accounts_data;
    //     $this->load->view('fixed/header', $head);
    //     $this->load->view('reports/trial_balance', $data);
    //     $this->load->view('fixed/footer');
    // }
    public function trial_balance_to_pdf()
    {
        
        $data = [];
        $head['title'] = "Trial Balance";
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;
        $data['trail_account_headers'] = $this->reports->trail_balance_account_headers();
        $trail_accounts = $this->reports->trail_balance_details();
        $accounts_data = [];
        foreach($trail_accounts as $row)
        {
            $accounts_data[$row['coa_header_id']][] = $row;
        }
        $data['trail_account_details'] = $accounts_data;
        $html =$this->load->view('reports/trailbalanceprintpdf-' . LTR, $data, true);   
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('trail_balance-report' . $pay_acc . '.pdf', 'I');
    }
    public function trial_balance_to_excel()
    {
        
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        $filename = 'trial_balance_' . date('dmYHis') . '.csv';
  
        // Set the headers to force download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
         // Open output stream
        $output = fopen('php://output', 'w');
        
        $trail_account_headers = $this->reports->trail_balance_account_headers();
        $trail_accounts = $this->reports->trail_balance_details();
        $accounts_data = [];
        foreach($trail_accounts as $row)
        {
            $accounts_data[$row['coa_header_id']][] = $row;
        }
        $trail_account_details = $accounts_data;

        if($trail_account_headers)
        {
            $debit_total =0;
            $credit_total =0;
            foreach ($trail_account_headers as $value) {
                fputcsv($output, ['','','']); 
                fputcsv($output, [$value['coa_header']]);  
                foreach ($trail_account_details[$value['coa_header_id']] as $key => $row) {
                    $credit = 0.00;
                    $debit = 0.00;
                    $code = $row['acid'];
                    if($row['amount']>0)
                    {
                        $debit = $row['amount'];
                        $debit_total += $row['amount'];
                    }
                    else{
                        $credit = abs($row['amount']);
                        $credit_total += abs($row['amount']);
                    }
                    fputcsv($output, [$row['holder'],number_format($debit,2),number_format($credit,2)]);  
                   
                }
            }
            fputcsv($output, ['','','']); 
            fputcsv($output, ['Total',number_format($debit_total,2),number_format($credit_total,2)]); 
        }
        
        

        fclose($output);
        exit;
    }
    public function journal_entries()
    {
        //$data['permissions'] = load_permissions('Data and Reports','Accounting','Journal Entries');
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000');        
        $data = [];
        $head['title'] = "Journal Entries";
        
        $data['journal_headers'] = $this->reports->unique_transaction_number_with_date();
        $journal_data = $this->reports->journal_summary_for_each_transactions();
        $journal_details = [];
        foreach($journal_data as $row)
        {
            $journal_details[$row['transaction_number']][] = $row;
        }
        $data['journal_data'] = $journal_details;
        // echo "<pre>"; print_r($data['journal_data']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/journal_entries', $data);
        $this->load->view('fixed/footer');
    }
    public function journal_entries_to_pdf()
    {
        ini_set('memory_limit', '64M');
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000');        
        $data = [];
        $head['title'] = "Journal Entries";

        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;
        
        $data['journal_headers'] = $this->reports->unique_transaction_number_with_date();
        $journal_data = $this->reports->journal_summary_for_each_transactions();
        $journal_details = [];
        foreach($journal_data as $row)
        {
            $journal_details[$row['transaction_number']][] = $row;
        }
        $data['journal_data'] = $journal_details;
        // echo "<pre>"; print_r($data['companyNanme']); die();
        $html = $this->load->view('reports/journalentriesprintpdf-' . LTR, $data, true);   
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('balance-sheet-report' . $pay_acc . '.pdf', 'I');
    }
    //erp2024 24-12-2024 reports ends

    
    public function sale_purchase_report()
    {     
         
        $daterange = $this->input->post('daterange'); 
        $start_date = $this->input->post('filter_expiry_date_from');
        $end_date = $this->input->post('filter_expiry_date_to');  

        $_SESSION['SaleReportData'] = [       
            'daterange' => $daterange,   
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
       
         $data = array();
         $resarray = [];
         $merged_sale_array =[];
   
            $saleresults = $this->sales->sale_by_date($start_date,$end_date);
            $sale_pur_results = $this->sales->purchase_by_date($start_date,$end_date);
             
        foreach ($saleresults as &$sales) {
                    $sales['sale_type'] = 'Delivery'; // Replace 'some_value' with your desired value
                }
           
                 $merged_sale_array = array_merge($sale_pur_results,$saleresults);  
                 $product_ids = [];

                if(!empty($merged_sale_array))
                {
                    foreach($merged_sale_array as $row){
                   
                        $product_qty = $row['product_qty']; 
                        $purchqty = $row['purchqty'];      
                        $total_poduct_price =  $product_qty * $row['price'];
                        $total_cost =  $product_qty * $row['cost'];
                     
                        $resarray[]= array(
                            'created_date' => $row['created_date'],
                            'product_name' => $row['product_code'],
                            'product_des' => $row['product_name'],
                            'product_qty' => $product_qty,
                            'purchase_date' => $row['purchdate'],
                            'cost' => $row['cost'],
                            'purchqty' => $purchqty,
                            'total_cost' => $total_cost,
                            'onhand' => $row['qty'],
                            'pro_id' => $row['pid']
                        );
                //  print_r($resarray); die();
                    }
                }
        $data['permissions'] = load_permissions('Sales','Reports','Sale Purchase Report');  
        $data['lists'] = $resarray; 
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;  
        $data['daterange'] = $daterange;  
        $head['title'] = "Sale Purchase Report";
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/purchasesales_report', $data);
        $this->load->view('fixed/footer');


    }

    public function purchase_sales_pdf()
    {                    
        // if (!$this->aauth->premission(10)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000');      
        $head['title'] = "Purchase Sales Report";
        $head['usernm'] = $this->aauth->get_user()->username;
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;
  
        $daterange = $_SESSION['SaleReportData']['daterange'];
        $start_date = $_SESSION['SaleReportData']['start_date'];
        $end_date = $_SESSION['SaleReportData']['end_date'];      
       
        $data = array();
        $resarray = [];
        $merged_sale_array =[];
   
            $saleresults = $this->sales->sale_by_date($start_date,$end_date);          
            $sale_pur_results = $this->sales->purchase_by_date($start_date,$end_date);
          
        foreach ($saleresults as &$sales) {
                    $sales['sale_type'] = 'Delivery';
                }
           
                 $merged_sale_array = array_merge($sale_pur_results,$saleresults);  
                 $product_ids = [];


                if(!empty($merged_sale_array))
                {
                    foreach($merged_sale_array as $row){
                   
                        $product_qty = $row['product_qty']; 
                        $purchqty = $row['purchqty'];            
                        $total_poduct_price =  $product_qty * $row['price'];
                        $total_cost =  $product_qty * $row['cost'];
                     

                        $resarray[]= array(
                            'created_date' => $row['created_date'],
                            'product_name' => $row['product_code'],
                            'product_des' => $row['product_name'],
                            'product_qty' => $product_qty,
                            'purchase_date' => $row['purchdate'],
                            'cost' => $row['cost'],
                            'purchqty' => $purchqty,
                            'total_cost' => $total_cost,
                            'onhand' => $row['qty']
                            
                        );

                    }
                }

        $data['lists'] = $resarray; 
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;  
        $data['daterange'] = $daterange;  
      
        $html = $this->load->view('sales/purchasesalereportprintpdf-' . LTR, $data, true);
            
        ini_set('memory_limit', '64M');
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);       
        $pdf->Output('purchase-sales-report.pdf', 'I');  
    }
    public function export_to_excell()
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        $filename = 'purchase_sales_report_' . date('Y-m-d') . '.csv';

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
        fputcsv($output, ['Item Code', 'Item Description','Quantity Sold', 'Cost', 'Sale Date', 'Purchase Date',  'Quantity Purchased','Onhand']);          
        $daterange = $_SESSION['SaleReportData']['daterange'];  
        $start_date = $_SESSION['SaleReportData']['start_date'];
        $end_date = $_SESSION['SaleReportData']['end_date'];       
       
        $data = array();
        $resarray = [];
        $merged_sale_array =[];
   
            $saleresults = $this->sales->sale_by_date($start_date,$end_date);  
            $sale_pur_results = $this->sales->purchase_by_date($start_date,$end_date);
          
        foreach ($saleresults as &$sales) {
                    $sales['sale_type'] = 'Delivery'; 
                }
                $merged_sale_array = array_merge($sale_pur_results,$saleresults);  
                $product_ids = [];

                if(!empty($merged_sale_array))
                {
                    foreach($merged_sale_array as $row){  
                            $product_qty = $row['product_qty']; 
                            $purchqty = $row['purchqty'];                   
                            $total_poduct_price =  $product_qty * $row['price'];
                            $total_cost =  $product_qty * $row['cost'];
                            $created_date = !empty($row['created_date']) ? date('d-m-Y', strtotime($row['created_date'])) : $row['created_date'];
                            $product_name = $row['product_code'];
                            $product_des = $row['product_name'];
                            $product_qty = $product_qty;
                            $purchase_date = $row['purchdate'];
                            $cost = $row['cost'];
                            $purchqty = $purchqty;       
                            $onhand = $row['qty'];
                            
                    $item_total = $item_total +  $product_qty;   
                    $sub_total_cost = $sub_total_cost + $cost;
                    $purchitem_total = $purchitem_total +  $purchqty;

                    fputcsv($output, [$product_name, $product_des, $product_qty, $cost, $created_date, $purchase_date, $purchqty,$onhand]);
                }              
                
            }
           
             $report_item_total = $report_item_total + $item_total;
             $report_cost_total = $report_cost_total + $sub_total_cost;
             $report_purchitem_total = $report_purchitem_total + $purchitem_total;

             fputcsv($output, ['','Total', $item_total,$sub_total_cost, '', '', $purchitem_total]);    
             fputcsv($output, ['','', 'Total Sold Items','Sub Total Cost', '','', 'Total Purchased Items']);
             fputcsv($output, ['','',$report_item_total, $report_cost_total,'', '', $report_purchitem_total]);
        
        fclose($output);
        exit;
    }
   
    public function inventory_aging_report()
    {
        $data = [];
        $head['title'] = "Inventory Aging Report";
        $dat= $this->input->post('filter_expiry_date_to');  
        $_SESSION['SaleReportData'] = [       
        
                  'end_date' => $dat
              ];
    
     if(isset($dat))
     {
        $currentDate = new DateTime($dat);
     }
     else{
        $currentDate = new DateTime();
     }
      $months = [];   
      for ($i = 0; $i < 12; $i++) {
          $months[] = $currentDate->format('F Y'); 
          $currentDate->modify('-1 month');      
      }
  
        $data['months'] = $months; 
        $data['permissions'] = load_permissions('Sales','Reports','Inventory Aging Report');  
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/inventory_aging_report', $data);
        $this->load->view('fixed/footer');
    }
    
    public function inventoryaging_ajax_list()
    {
        $eid = 0;
        // if ($this->aauth->premission(9)) {
        //     $eid = $this->input->post('eid');
        // }
        $j=1;

        $end_date = $_SESSION['SaleReportData']['end_date'];   
        $monthly_sales = $this->sales->get_monthly_salesreport($end_date);
  
        $data = array();
        foreach ($monthly_sales as $invoices) {
            $row = array();
            $row[] = $j;
            $pr_id= $invoices['product_id'];   
            $row[] = '<a href="' . base_url("products/edit?id=$pr_id") . '">'.$invoices['product_code']. '</a>';
            $row[] = '<a href="' . base_url("products/edit?id=$pr_id") . '">'.$invoices['product_name']. '</a>';
            $row[] = $invoices['onhand'];
            for ($i = 12; $i >= 1; $i--): 
                $row[] = number_format($invoices["Month_$i"], 2); 
            endfor;     
            $data[] = $row;
            $j++;
              
        }
    
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->salesorderreport->count_all($eid),
            "recordsFiltered" => $this->salesorderreport->count_filtered($eid),
            "data" => $data,
        );
        echo json_encode($output);
     
    }

    //erp2024 05-03-2025
    public function profit_and_loss_new()
    {
        $data = [];
        $head['title'] = "Profit & Loss";
        
        $this->load->model('transactions_model', 'transactions');   
        $data['income']  = $this->reports->coa_accounts_total_transaction_amount('Income',date('Y'));
        $data['expense']  = $this->reports->coa_accounts_total_transaction_amount('Expenses',date('Y'));

        $date = date('Y')."-01-01";
        $data['revenue_income']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Revenue',$date);
        $data['cogs']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Direct Costs',$date);
        $data['otherincome']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Income',$date);
        $data['otherexpense']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Expense',$date);
       
        // echo "<pre>"; print_r($data['otherexpense']); die();

        $this->load->view('fixed/header', $head);
        $this->load->view('reports/profit_and_loss_report_new', $data);
        $this->load->view('fixed/footer');
    }

    public function profit_and_loss_to_prf_new()
    {
        ini_set('memory_limit', '64M');
        $data = [];
        $head['title'] = "Profit & Loss Report";
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;
        $head['title'] = "Profit & Loss";
        
        $this->load->model('transactions_model', 'transactions');   
        $data['income']  = $this->reports->coa_accounts_total_transaction_amount('Income',date('Y'));
        $data['expense']  = $this->reports->coa_accounts_total_transaction_amount('Expenses',date('Y'));

        $date = date('Y')."-01-01";
        $data['revenue_income']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Revenue',$date);
        $data['cogs']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Direct Costs',$date);
        $data['otherincome']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Income',$date);
        $data['otherexpense']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Expense',$date);

        $html = $this->load->view('reports/profitandlossprintpdfnew-' . LTR, $data, true);   
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        // $pdf->AddPage('L');
        $pdf->WriteHTML($html);       
        $pdf->Output('balance-sheet-report' . $pay_acc . '.pdf', 'I');
    }

    public function profit_and_loss_to_excel_new()
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        $filename = 'profit_and_loss_excel_' . date('dmYHis') . '.csv';
  
        // Set the headers to force download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
         // Open output stream
        $output = fopen('php://output', 'w');
        
        // Write the CSV header
        
        
        
        $income  = $this->reports->coa_accounts_total_transaction_amount('Income',date('Y'));
        $expense  = $this->reports->coa_accounts_total_transaction_amount('Expenses',date('Y'));

        $this->load->model('transactions_model', 'transactions');   

        $date = date('Y')."-01-01";
        $revenue_income  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Revenue',$date);
        $cogs           = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Direct Costs',$date);
        $otherincome  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Income',$date);
        $otherexpense  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Expense',$date);


        $revenue_value = 0;
        $grand_revenue = 0;
        fputcsv($output, ['Revenue','']);  
        if($revenue_income)
        {
            foreach ($revenue_income as $key => $value) {
                $account_id = $value['account_id'];
                $revenue_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_revenue += $revenue_value;                             
                $revenue_value1 = ($revenue_value>0) ? number_format($revenue_value,2) : "(".number_format(abs($revenue_value),2).")";
                fputcsv($output, [$value['holder'],$revenue_value1]);  
            }
            fputcsv($output, ['Total Revenue',abs($grand_revenue)]);  
        }
        fputcsv($output, ['','']);  
        fputcsv($output, ['Costs of Goods Sold(COGS)','']);  
        $cogs_value = 0;
        $grand_cogs = 0;
        if($cogs)
        {
            foreach ($cogs as $key => $value) {
                $account_id = $value['account_id'];
                $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                $cogs_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_cogs += $cogs_value; 
                fputcsv($output, [$value['holder'],abs($cogs_value)]);  
                
            }
            fputcsv($output, ['','']);  
            fputcsv($output, ['Total COGS',abs($grand_revenue)]);  
        }
        fputcsv($output, ['','']);  
        fputcsv($output, ['Gross Profit',abs($grand_revenue)-abs($grand_cogs)]);  
        fputcsv($output, ['','']);  
        fputcsv($output, ['Other Income','']);  

        $otherincome_value = 0;
        $grand_otherincome = 0;
        if($otherincome)
        {
            foreach ($otherincome as $key => $value) {
               $account_id = $value['account_id'];
               $otherincome_value = ($value['amount']) ? ($value['amount']):0.00;      
               $grand_otherincome += $otherincome_value;                             
               $otherincome_value1 = ($otherincome_value>0) ? number_format($otherincome_value,2) : number_format(abs($otherincome_value),2);
               
               fputcsv($output, [$value['holder'],$otherincome_value1]);  
            }
            fputcsv($output, ['Total Other Income',number_format(abs($grand_otherincome),2)]);  
        }

        fputcsv($output, ['','']);  
        fputcsv($output, ['Other Expense','']);  
        $otherexpense_value = 0;
        $grand_otherexpense = 0;
        if($otherexpense)
        {
            foreach ($otherexpense as $key => $value) {
               $account_id = $value['account_id'];
               $otherexpense_value = ($value['amount']) ? ($value['amount']):0.00;      
               $grand_otherexpense += $otherexpense_value;                             
               $otherexpense_value1 = ($otherexpense_value>0) ?  "(".number_format(abs($otherexpense_value),2).")" : "(".number_format(abs($otherexpense_value),2).")";
            //    fputcsv($output, [$value['holder'],abs($otherexpense_value1)]);
            fputcsv($output, [$value['holder'],($otherexpense_value1)]);  
            }
            fputcsv($output, ['Total Other Expense',number_format(abs($grand_otherexpense),2)]); 
        }

        $grandtotal = (abs($grand_revenue)+abs($grand_otherincome)) - (abs($grand_cogs)+abs($grand_otherexpense));
        fputcsv($output, ['','']);  
        fputcsv($output, ['Net Income',$grandtotal]);  

        
        fclose($output);
        exit;
    }


    public function cash_flow()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data = [];
        $head['title'] = "Cash Flow";
        
        $this->load->model('transactions_model', 'transactions');   
        $data['income']  = $this->reports->coa_accounts_total_transaction_amount('Income',date('Y'));
        $data['expense']  = $this->reports->coa_accounts_total_transaction_amount('Expenses',date('Y'));

        $date = date('Y')."-01-01";
        $data['revenue_income']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Revenue',$date);
        $data['cogs']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Direct Costs',$date);
        $data['otherincome']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Income',$date);
        $data['otherexpense']  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Expense',$date);
       

        $data['accounts_payable'] = default_chart_of_account('accounts_payable');
        $data['accounts_recievable'] = default_chart_of_account('accounts_receivable');
        $data['accounts_inventory'] = default_chart_of_account('inventory');
        $data['operating_activities']  = $this->reports->operating_activities($data['accounts_payable'],$data['accounts_recievable'],$data['accounts_inventory']);
        
        $data['accounts_payable_data']  = $this->reports->coa_accounts_total_transaction_amount_by_accountcode($data['accounts_payable'],date('Y-m-d'));

        $data['accounts_recievable_data']  = $this->reports->coa_accounts_total_transaction_amount_by_accountcode($data['accounts_recievable'],date('Y-m-d'));

        $data['accounts_inventory_data']  = $this->reports->coa_accounts_total_transaction_amount_by_accountcode($data['accounts_inventory'],date('Y-m-d'));


        $data['ar_sales_on_credit']  = $this->reports->ar_sales_on_credit(date('m'));
        $data['ar_sales_payment_received']  = $this->reports->ar_sales_payment_received(date('m'));
        $data['ar_sales_return']  = $this->reports->ar_sales_return(date('m'));
        $data['ap_purchase_credit']  = $this->reports->ap_purchase_credit();
        $data['ap_purchase_paid']  = $this->reports->ap_purchase_paid();
        $data['ap_purchase_return']  = $this->reports->ap_purchase_return();



        // echo "<pre>"; print_r($data['ap_purchase_paid']); die();
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/cash_flow', $data);
        $this->load->view('fixed/footer');
    }
    

    public function cash_flow_to_prf()
    {
        ini_set('memory_limit', '64M');
        $data = [];
        $head['title'] = "Cash Flow Report";
        $loc = location($this->aauth->get_user()->loc);
        $configurations = $this->session->userdata('configurations');
        $data['config_currency'] = $configurations['config_currency'];
        $data['companyNanme']=$loc['cname'];
        $company = '' . $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
        $data['lang']['company'] = $company;
        $head['title'] = "Cash Flow Report";
        $head['title'] = "Cash Flow";
        
        $this->load->model('transactions_model', 'transactions');   
        $data['income']  = $this->reports->coa_accounts_total_transaction_amount('Income',date('Y'));
        $data['expense']  = $this->reports->coa_accounts_total_transaction_amount('Expenses',date('Y'));

        $date = date('Y')."-01-01";
        $revenue_income  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Revenue',$date);
        $cogs = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Direct Costs',$date);
        $otherincome  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Income',$date);
        $otherexpense  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Expense',$date);
       

        $data['ar_sales_on_credit']  = $this->reports->ar_sales_on_credit(date('m'));
        $data['ar_sales_payment_received']  = $this->reports->ar_sales_payment_received(date('m'));
        $data['ar_sales_return']  = $this->reports->ar_sales_return(date('m'));
        $data['ap_purchase_credit']  = $this->reports->ap_purchase_credit();
        $data['ap_purchase_paid']  = $this->reports->ap_purchase_paid();
        $data['ap_purchase_return']  = $this->reports->ap_purchase_return();

        $revenue_value = 0;
        $grand_revenue = 0;
        if($revenue_income)
        {
            foreach ($revenue_income as $key => $value) {
                $account_id = $value['account_id'];
                $revenue_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_revenue += $revenue_value;                             
                
            }
        }
            
        $cogs_value = 0;
        $grand_cogs = 0;
        if($cogs)
        {
            foreach ($cogs as $key => $value) {
                $account_id = $value['account_id'];
                $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                $cogs_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_cogs += $cogs_value; 
                
            }
        }
        $otherincome_value = 0;
        $grand_otherincome = 0;
        if($otherincome)
        {
            foreach ($otherincome as $key => $value) {
                $account_id = $value['account_id'];
                $otherincome_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_otherincome += $otherincome_value;                             
                $otherincome_value1 = ($otherincome_value>0) ? number_format($otherincome_value,2) : number_format(abs($otherincome_value),2);
                
            }
        }
        
        $otherexpense_value = 0;
        $grand_otherexpense = 0;
        if($otherexpense)
        {
            foreach ($otherexpense as $key => $value) {
                $account_id = $value['account_id'];
                $otherexpense_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_otherexpense += $otherexpense_value;                             
                $otherexpense_value1 = ($otherexpense_value>0) ?  "(".number_format(abs($otherexpense_value),2).")" : "(".number_format(abs($otherexpense_value),2).")";
                
            }
        }
        $net_income = (abs($grand_revenue)+abs($grand_otherincome)) - (abs($grand_cogs)+abs($grand_otherexpense));
        $data['net_income'] = $net_income;

        $html = $this->load->view('reports/cashflowprintpdf-' . LTR, $data, true);   
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        // $pdf->AddPage('L');
        $pdf->WriteHTML($html);       
        $pdf->Output('balance-sheet-report' . $pay_acc . '.pdf', 'I');
    }

    public function cash_flow_to_to_excel()
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', '600000'); 
        $filename = 'cash_flow_excel_' . date('dmYHis') . '.csv';
  
        // Set the headers to force download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
         // Open output stream
        $output = fopen('php://output', 'w');
        
  
        
        $date = date('Y')."-01-01";
        $revenue_income  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Revenue',$date);
        $cogs = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Direct Costs',$date);
        $otherincome  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Income',$date);
        $otherexpense  = $this->reports->coa_accounts_total_transaction_amount_for_type_revenue('Other Expense',$date);

        $revenue_value = 0;
        $grand_revenue = 0;
        if($revenue_income)
        {
            foreach ($revenue_income as $key => $value) {
                $account_id = $value['account_id'];
                $revenue_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_revenue += $revenue_value;                             
                
            }
        }
            
        $cogs_value = 0;
        $grand_cogs = 0;
        if($cogs)
        {
            foreach ($cogs as $key => $value) {
                $account_id = $value['account_id'];
                $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                $cogs_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_cogs += $cogs_value; 
                
            }
        }
        $otherincome_value = 0;
        $grand_otherincome = 0;
        if($otherincome)
        {
            foreach ($otherincome as $key => $value) {
                $account_id = $value['account_id'];
                $otherincome_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_otherincome += $otherincome_value;                             
                $otherincome_value1 = ($otherincome_value>0) ? number_format($otherincome_value,2) : number_format(abs($otherincome_value),2);
                
            }
        }
        
        $otherexpense_value = 0;
        $grand_otherexpense = 0;
        if($otherexpense)
        {
            foreach ($otherexpense as $key => $value) {
                $account_id = $value['account_id'];
                $otherexpense_value = ($value['amount']) ? ($value['amount']):0.00;      
                $grand_otherexpense += $otherexpense_value;                             
                $otherexpense_value1 = ($otherexpense_value>0) ?  "(".number_format(abs($otherexpense_value),2).")" : "(".number_format(abs($otherexpense_value),2).")";
                
            }
        }
        $net_income = (abs($grand_revenue)+abs($grand_otherincome)) - (abs($grand_cogs)+abs($grand_otherexpense));
        $data['net_income'] = $net_income;

        if($net_income)
        {            
            fputcsv($output, ['Net Income',$net_income]);  
            
        }
        $ar_sales_on_credit  = $this->reports->ar_sales_on_credit(date('m'));
        $ar_sales_payment_received  = $this->reports->ar_sales_payment_received(date('m'));
        $ar_sales_return  = $this->reports->ar_sales_return(date('m'));
        $ap_purchase_credit  = $this->reports->ap_purchase_credit();
        $ap_purchase_paid  = $this->reports->ap_purchase_paid();
        $ap_purchase_return  = $this->reports->ap_purchase_return();
        $grand_total = 0;
        if($ar_sales_on_credit)
        {              
               $grand_total  -=abs($ar_sales_on_credit);   
               fputcsv($output, ['Account Receivable (Sales on Credit)',"(".abs($ar_sales_on_credit).")"]);               
        }
        if($ar_sales_payment_received)
        {               
              $grand_total  +=abs($ar_sales_payment_received);  
              fputcsv($output, ['Account Receivable (Customer Payment Received)',abs($ar_sales_payment_received)]);  

        }
        if($ar_sales_return)
        {                  
              $grand_total  +=abs($ar_sales_return);  
              fputcsv($output, ['Account Receivable (Sales Returned by Customer)',abs($ar_sales_return)]); 
        }
        if($ap_purchase_credit)
        {                             
               $grand_total  +=abs($ap_purchase_credit); 
               fputcsv($output, ['Account Payable (Purchase on Credit)',abs($ap_purchase_credit)]); 
        }
        if($ap_purchase_paid)
        {                       
              $grand_total  -=abs($ap_purchase_paid);
              fputcsv($output, ['Account Payable (Payment Made)',"(".abs($ap_purchase_paid).")"]); 
        }
        if($ap_purchase_return)
        {                  
              $grand_total  -=abs($ap_purchase_return); 
              fputcsv($output, ['Account Payable (Purchase Returned)',"(".abs($ap_purchase_return).")"]);
        }
        $ending = 'Ending Cash Balance'." (".date('F d, Y').")";
        fputcsv($output, ["",""]);
        fputcsv($output, [$ending,$grand_total]);
        // fputcsv($output, ['','','','','','']); 
        // fputcsv($output, ['Net Profit',($grand_income_first-$grand_expense_first),($grand_income_second-$grand_expense_second),($grand_income_third-$grand_expense_third),($grand_income_fourth-$grand_expense_fourth),($grand_income_total-$grand_expense_total)]);  
        fclose($output);
        exit;
    }

    public function average_cost()
    {
        $query = $this->db->query("SELECT * FROM average_price_table"); 
        $data['list'] = $query->result();
        $head['title'] = "Average Cost";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/averagecost', $data);
        $this->load->view('fixed/footer');
    }

    public function average_costing()
    {
      
        $data['list'] = $this->reports->get_average_cost_product_lists();
        $data['products'] = $this->reports->products_from_average_cost_table();
        $data['permissions'] = load_permissions('Stock','Reports','Average Cost');
        // echo "<pre>"; print_r($data['permissions']); die();
        $head['title'] = "Average Cost";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/averagecosting', $data);
        $this->load->view('fixed/footer');
    }

    public function ajax_averagecost_list()
    {
      
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL); 
        // $list = $this->reports->get_average_cost_product_lists();
        $lists = $this->reports->get_datatables($this->limited);
        $data = array();
        $no = $this->input->post('start');
        foreach ($lists as $list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('d-m-Y H:i:s',strtotime($list->transaction_date_time));
            $row[] = $list->product_name."(".$list->product_code.")";
            $row[] = ($list->transaction_type_name=="Purchase")? "<b>".$list->transaction_type_name."</b>":$list->transaction_type_name;
            $row[] = $list->transaction_quantity;
            $row[] = $list->onhand_quantity;
            $row[] = number_format($list->product_cost,2);
            $row[] = ($list->transaction_type_name=="Purchase")? "<b>".number_format($list->product_average_cost,2)."</b>" :number_format($list->product_average_cost,2) ;
            $row[] = number_format($list->product_inventory_value,2);
            $row[] = $list->employee;
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->reports->count_all($this->limited),
            "recordsFiltered" => $this->reports->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    //Aswathy 05-05-2025
    public function lead_reports()
    {        
        $head['title'] = "Lead Report";       
        $this->load->view('fixed/header', $head);        
        $this->load->view('lead_tree_view_report');
        // $this->load->view('reports/lead_tree_view');
        $this->load->view('fixed/footer');
    }


     // Get Leads
     public function get_leads() {
        $leads = $this->reports->get_leads();
        $data = [];
        foreach ($leads as $lead) {
            if($lead['quote_count'] >0 ){
                $data[] = [
                    'id' => $lead['lead_number'],
                    'text' => $lead['lead_number'],
                    'children' => true,
                    'icon' => 'fa fa-book' // blue lead icon
                ];
            }
            else{
                $data[] = [
                    'id' => $lead['lead_number'],
                    'text' => $lead['lead_number'],
                    'children' => false,
                    'icon' => 'fa fa-book' // blue lead icon
                ];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    // Get Quotes
    public function get_quote() {
        $lead_number = $this->input->get('id');
        $quotes = $this->reports->get_quotes_by_enquiry($lead_number);
        $data = [];
    
        foreach ($quotes as $quote) {
            $data[] = [
                'id' => 'quote_' . $quote['quote_number'],
                'text' => $quote['quote_number'],
                'children' => false,
                'icon' => 'fa fa-book'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    public function lead_details() {
        $lead_number = $this->input->get('lead_number');
        $items = $this->reports->get_lead_items($lead_number);
        // return view as HTML table
                $html = ' <div class="lead-block mb-2">
                        <h5> <span>Customer Name :  '.$items[0]['customer_name'] .'</span> &nbsp;</h5></div>';
              $html .= '<table class="table table-bordered table-striped" id="items-table">';
              $html .= '<thead ><tr><th>Prduct Code</th><th>Product Name</th><th>Date</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>';
          
              foreach ($items as $item) {
                  $html .= '<tr>';
                  $html .= '<td>' . $item['product_code'] . '</td>';
                  $html .= '<td>' . $item['product_name'] . '</td>';
                  $html .= '<td>' . $item['date_received'] . '</td>';
                  $html .= '<td>' . $item['qty'] . '</td>';
                  $html .= '<td>' . $item['product_price'] . '</td>';
                  $html .= '<td>' . $item['subtotal'] . '</td>';
                  $html .= '</tr>';
              }
          
              $html .= '</tbody></table>';
          
              echo $html;
          
    }
    
    public function quote_details() {
        $quote_number = $this->input->get('quote_number');
        $items = $this->reports->get_quote_items($quote_number); // You'll create this
         // return view as HTML table
        //  $html = ' <div class="lead-block mb-2"><h5> <span>Customer Name :  '.$items[0]['customer_name'] .'</span> &nbsp;</h5></div>';
         $html = '<table class="table table-bordered table-striped" id="items-table">';
         $html .= '<thead><tr><th>Product Code</th><th>Product Name</th><th>Date</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>';
     
         foreach ($items as $item) {
             $html .= '<tr>';
             $html .= '<td>' . $item['product_code'] . '</td>';
             $html .= '<td>' . $item['product_name'] . '</td>';
             $html .= '<td>' . $item['invoicedate'] . '</td>';
             $html .= '<td>' . $item['qty'] . '</td>';
             $html .= '<td>' . $item['product_price'] . '</td>';
             $html .= '<td>' . $item['subtotal'] . '</td>';
             $html .= '</tr>';
         }
     
         $html .= '</tbody></table>';
     
         echo $html;
     
    }
    public function employee_supervisor_report()
    {        
        $data = [];
        // $data['permissions'] = load_permissions('Sales','Reports','Sales Orders Report');
        $head['title'] = "Employee Supervisor Report";       
        $this->load->view('fixed/header', $head);
        $this->load->view('reports/employee_supervisor_report');
        $this->load->view('fixed/footer');
    }

    public function get_supervisors() {
        $supervisors = $this->reports->get_supervisors();
        $data = [];

        foreach ($supervisors as $sup) {
            $data[] = [
                'id' => 'sup_' . $sup['id'],
                'text' => $sup['name'],
                'children' => true,
                'icon' => 'fa fa-user-plus'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }
      // Get Employees under a supervisor
      public function get_employees() {
        $supervisor_id = str_replace('sup_', '', $this->input->get('id'));
        $employees = $this->reports->get_employees_by_supervisor($supervisor_id);
        $data = [];
        foreach ($employees as $emp) {
            $data[] = [
                'id' => 'emp_' . $emp['id'],
                'text' => $emp['name'],
                'children' => false,
                'icon' => 'fa fa-user text-secondary'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    public function get_profile() {
        $id = $this->input->get('id');
        $profile = $this->reports->get_employee_by_id($id);
    
        if (!$profile) {
            echo '<p class="text-danger">Profile not found.</p>';
            return;
        }
    
        $html = '<ul class="list-group1 list-unstyled">';
        $html .= '<li class="list-group-item1"><strong>Name : </strong> ' . $profile['name'] . '</li>';
        $html .= '<li class="list-group-item1"><strong>City : </strong> ' . $profile['city'] . '</li>';
        $html .= '<li class="list-group-item1"><strong>Address : </strong> ' . $profile['address'] . '</li>';
        $html .= '<li class="list-group-item1"><strong>Phone : </strong> ' . $profile['phone'] . '</li>';
        $html .= '<li class="list-group-item1"><strong>Postbox : </strong> ' . $profile['postbox'] . '</li>';
        $html .= '</ul>';
    
        echo $html;
    }

    public function purchase_orders_tree_report()
    {        
        
        $head['title'] = "Purchase Order Report";       
        $this->load->view('fixed/header', $head);        
        $this->load->view('reports/purchase_orders_tree_report');
        $this->load->view('fixed/footer');
    }

    public function get_roots() {

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $purchase_orders = $this->reports->get_pos_with_items();
        $data = [];
        foreach ($purchase_orders as $po) {
          if ($po['item_count'] > 0) {
            $data[] = [
              'id' =>$po['po_name'],
              'text' =>$po['po_name'],
              'pid' => $po['po_id'],
              'children' => true
            ];
          }else{
            $data[] = [
              'id' => $po['po_name'],
              'text' => '#',
              'children' => false
            ];
          }
        }
      
        header('Content-Type: application/json');
        echo json_encode($data);
      }
    
      public function get_children() {
        $parent_id = $this->input->get('id');
        $data = [];
    
        // CASE 1: If it's a Reciept node (load Expense)
        if (strpos($parent_id, 'reciept_') === 0) {
            $reciept_id = str_replace('reciept_', '', $parent_id);
            $reciept = $this->has_purchase_reciept($reciept_id); // You had $po_number mistakenly
    
            if ($this->has_expense_for_reciept($reciept_id)) {
                $data[] = [
                    'id' => 'expense_' . $reciept_id,
                    'text' => 'Expense',
                    'code' => $reciept['pr_name'],
                    'htext' => 'Expense Details',
                    'type' => 'expense_folder',
                    'children' => false,
                    'icon' => 'fa fa-money-bill'
                ];
            }
        }
    
        // CASE 2: Generic PO node (Items + Reciept)
        else if ($parent_id) {
            $data[] = [
                'id' => 'items_' . $parent_id,
                'text' => 'Items',
                'htext' => 'Item Details',
                'children' => false,
                'type' => 'items_folder',
                'icon' => 'fa fa-list'
            ];
    
            $reciept = $this->has_purchase_reciept($parent_id);
            if ($reciept) {
                $data[] = [
                    'id' => 'reciept_' . $parent_id,
                    'code' => $reciept['pr_name'],
                    'text' => 'Recirpt',
                    'htext' => 'Recirpt Details',
                    'children' => ($reciept['expense_count'] > 0),
                    'type' => 'recirpt_folder',
                    'icon' => 'fa fa-file'
                ];
            }
        }
    
        header('Content-Type: application/json');
        echo json_encode($data);
    }
      public function item_details() {
        $item_id = $this->input->get('item_id');
        $po_id = $this->input->get('po_id');    
        // public function get_items_table() {
          $purchase_id = $this->input->get('purchase_id');
      
          $items = $this->reports->get_items_by_po($purchase_id); // Create this model function
      
          $html = '<table class="table table-bordered table-striped" id="items-table">';
          $html .= '<thead ><tr><th>Code</th><th>Name</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>';
      
          foreach ($items as $item) {
              $html .= '<tr>';
              $html .= '<td>' . $item['product_code'] . '</td>';
              $html .= '<td>' . $item['product_name'] . '</td>';
              $html .= '<td>' . $item['qty'] . '</td>';
              $html .= '<td>' . $item['product_price'] . '</td>';
              $html .= '<td>' . $item['subtotal'] . '</td>';
              $html .= '</tr>';
          }
      
          $html .= '</tbody></table>';
      
          echo $html;
      
      }
    public function reciept_details(){
        $purchase_reciept_id = $this->input->get('purchase_reciept_id');
        $purchase_reciept_id = str_replace('reciept_', '', $purchase_reciept_id);
    
          $items = $this->reports->get_reciept_by_po($purchase_reciept_id); // Create this model function
      
          $html = '<table class="table table-bordered table-striped" id="items-table">';
          $html .= '<thead ><tr><th>Code</th><th>Name</th><th>Qty</th><th>Price</th><th>Date</th><th>Total</th></tr></thead><tbody>';
      
          foreach ($items as $item) {
              $html .= '<tr>';
              $html .= '<td>' . $item['product_code'] . '</td>';
              $html .= '<td>' . $item['product_name'] . '</td>';
              $html .= '<td>' . $item['product_qty'] . '</td>';
              $html .= '<td>' . $item['price'] . '</td>';
              $html .= '<td>' . $item['date'] . '</td>';
              $html .= '<td>' . $item['amount'] . '</td>';
              $html .= '</tr>';
          }
      
          $html .= '</tbody></table>';
      
          echo $html;
      
    }
    public function expense_details(){
      $purchase_reciept_id = $this->input->get('purchase_reciept_id');
    
          $items = $this->reports->get_expenses_by_pr($purchase_reciept_id); // Create this model function
      
          $html = '<table class="table table-bordered table-striped" id="items-table">';
          $html .= '<thead ><tr><th>Bill No</th><th>Name</th><th>Date</th><th>Amount</th></tr></thead><tbody>';
      
          foreach ($items as $item) {
              $html .= '<tr>';
              $html .= '<td>' . $item['bill_no'] . '</td>';
              $html .= '<td>' . $item['expense_name'] . '</td>';
              $html .= '<td>' . $item['date'] . '</td>';
              $html .= '<td>' . $item['costing_amount'] . '</td>';
              $html .= '</tr>';
          }
      
          $html .= '</tbody></table>';
      
          echo $html;
      
    }
      private function has_purchase_return($po_number) {
        return $this->reports->check_po_return($po_number);
      }
      public function has_purchase_reciept($po_number){
        return $this->reports->check_po_reciept($po_number);
      }
      public function has_expense_for_reciept($reciept_id) {
        return $this->reports->check_expense_for_receipt($reciept_id); // returns true/false
    }
}
