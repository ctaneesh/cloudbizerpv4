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

class Reports_model extends CI_Model
{
    var $column_order_statements = array('cberp_transactions.date', 'cberp_transactions.note', 'cberp_transactions.debit', 'cberp_transactions.credit');
        
    var $order_statments = array('cberp_quotes.tid' => 'desc');
    var $column_search_statements = array('cberp_transactions.date', 'cberp_transactions.note', 'cberp_transactions.debit', 'cberp_transactions.credit');
   
    var $column_order_avgcosting = array(null, 'cberp_average_cost.transaction_date_time','cberp_products.product_name','cberp_cost_transaction_type.transaction_type_name', 'cberp_average_cost.transaction_quantity', 'cberp_average_cost.onhand_quantity', 'cberp_average_cost.product_cost','cberp_average_cost.product_average_cost', 'cberp_average_cost.product_inventory_value', 'cberp_employees.name');

    var $column_search_avgcosting = array('cberp_average_cost.transaction_date_time','cberp_products.product_name','cberp_cost_transaction_type.transaction_type_name', 'cberp_average_cost.transaction_quantity', 'cberp_average_cost.onhand_quantity', 'cberp_average_cost.product_cost','cberp_average_cost.product_average_cost', 'cberp_average_cost.product_inventory_value', 'cberp_employees.name');
    
    var $order_avgcosting = array('cberp_average_cost.product_id' => 'asc');


    public function viewstatement($pay_acc, $trans_type, $sdate, $edate, $ttype)
    {

        if ($trans_type == 'All') {
            $where = "acid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') ";
        } else {
            $where = "acid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type'";
        }
        // if ($this->aauth->get_user()->loc) {
        //     $where .= " AND loc='" . $this->aauth->get_user()->loc . "'";
        // } elseif (!BDATA) {
        //     $where .= " AND type='$trans_type AND loc='0'";
        // }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function get_account_statements($pay_acc, $trans_type, $sdate, $edate)
    {

        if ($trans_type == 'All') {
            $where = "acid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') ";
        } else {
            $where = "acid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type'";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);
        $this->db->order_by('id', 'DESC');
       
        $order = array('id' => 'desc');
        $query = $this->db->get();
        $result = $query->result_array();

        return  $result;
    }
    public function get_statements($pay_acc, $trans_type, $sdate, $edate)
    {

        if ($trans_type == 'All') {
            $where = "acid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') ";
        } else {
            $where = "acid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type'";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);
        $this->db->order_by('id', 'DESC');
       // search section starts
       $i = 0;
       $search_value = $this->input->post('search')['value'];
       $search_value_clean = str_replace(',', '', $search_value);
       foreach ($this->column_search_statements as $item) // loop column
       {
           if ($this->input->post('search')['value']) // if datatable send POST for search
           {

                   if ($i === 0) {
                       $this->db->group_start(); // Open group for OR conditions
                   }

                   // Check if the current column is 'cberp_transactions.date'
                   if ($item === 'cberp_transactions.date') {
                       // Convert search value to Y-m-d format if it's a date input
                       $formatted_date = date('Y-m-d', strtotime($search_value_clean));
                       $this->db->or_like($item, $formatted_date); // Search in date column
                   } else {
                       // Regular search for other columns
                       $this->db->or_like($item, $search_value_clean);
                   }

                   // Close the group on the last iteration
                   if (count($this->column_search_statements) - 1 == $i) {
                       $this->db->group_end();
                   }
           }
           $i++;
       }
       // search section starts

       
        if (isset($_POST['order'])) // here order processing
       {
           $this->db->order_by($this->column_order_statements[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
       } else if (isset($this->column_order_statements)) {
           $order = $this->column_order_statements;
           $this->db->order_by(key($column_order_statements), $column_order_statements[key($order)]);
       }
       else{ 
           $order = array('id' => 'desc');
       }
       if ($_POST['length'] != -1){
           $this->db->limit($_POST['length'], $_POST['start']);
       }  
        $query = $this->db->get();
        $result = $query->result_array();

        return  $result;
    }
    public function get_statements_count($pay_acc, $trans_type, $sdate, $edate)
    {

        if ($trans_type == 'All') {
            $where = "acid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') ";
        } else {
            $where = "acid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type'";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);

        $total_count = $this->db->count_all_results();
        return $total_count;
    }
   

    
    public function sales_tax_statement($sdate, $edate, $lid)
    {
            
            $where = "DATE(cberp_invoices.invoicedate) BETWEEN '$sdate' AND '$edate'"; 
            $this->db->select('
            cberp_customers.tax_id AS VAT_Number,
            cberp_invoices.tid AS invoice_number,
            cberp_invoices.total AS amount,
            cberp_invoices.tax AS tax,
            cberp_customers.name AS customer_name,
            cberp_customers.company AS Company_Name,
            cberp_invoices.invoicedate AS date
        ');
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_customers', 'cberp_invoices.csd = cberp_customers.customer_id');  
        $this->db->where($where);

            // $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_invoices.csd');
            // $this->db->where($where);
            // if ($lid > 0) {
            //     $this->db->where('cberp_invoices.id', $lid);  // Correct column reference
            // }
       // search section starts
       $i = 0;
        //    $search_value = $this->input->post('search')['value'];
        //    $search_value_clean = str_replace(',', '', $search_value);
        //    foreach ($this->column_search_statements as $item) // loop column
        //    {
        //        if ($this->input->post('search')['value']) // if datatable send POST for search
        //        {

        //                if ($i === 0) {
        //                    $this->db->group_start(); // Open group for OR conditions
        //                }

        //                // Check if the current column is 'cberp_transactions.date'
        //                if ($item === 'cberp_transactions.date') {
        //                    // Convert search value to Y-m-d format if it's a date input
        //                    $formatted_date = date('Y-m-d', strtotime($search_value_clean));
        //                    $this->db->or_like($item, $formatted_date); // Search in date column
        //                } else {
        //                    // Regular search for other columns
        //                    $this->db->or_like($item, $search_value_clean);
        //                }

        //                // Close the group on the last iteration
        //                if (count($this->column_search_statements) - 1 == $i) {
        //                    $this->db->group_end();
        //                }
        //        }
        //        $i++;
        //    }
        // search section starts

        
        //     if (isset($_POST['order'])) // here order processing
        //    {
        //        $this->db->order_by($this->column_order_statements[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        //    } else if (isset($this->column_order_statements)) {
        //        $order = $this->column_order_statements;
        //        $this->db->order_by(key($column_order_statements), $column_order_statements[key($order)]);
        //    }
        //    else{ 
        //        $order = array('id' => 'desc');
        //    }
       $this->db->order_by('cberp_invoices.id','DESC');
       if ($_POST['length'] != -1){
           $this->db->limit($_POST['length'], $_POST['start']);
       }  
 
       $query = $this->db->get();
        $result = $query->result_array();

        return  $result;
    }
    public function get_sales_tax_statement_count($sdate, $edate, $lid)
    {


            $where = " (DATE(cberp_invoices.invoicedate) BETWEEN '$sdate' AND '$edate' )";
            if ($lid > 0)
            {
                $this->db->where('cberp_invoices',$lid);  
            }
           
            $this->db->select('cberp_customers.tax_id AS VAT_Number,cberp_invoices.tid AS invoice_number,cberp_invoices.total AS amount,cberp_invoices.tax AS tax,cberp_customers.name AS customer_name,cberp_customers.company AS Company_Name,cberp_invoices.invoicedate AS date');
            $this->db->from('cberp_invoices');
            $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_invoices.csd');
            $this->db->where($where);
            $total_count = $this->db->count_all_results();
            return $total_count;
    }

    public function purchase_tax_statement($sdate, $edate, $lid)
    {
        $where = "DATE(cberp_purchase_orders.invoicedate) BETWEEN '$sdate' AND '$edate'"; 
        $this->db->select('
             cberp_suppliers.tax_id AS VAT_Number,
             cberp_purchase_orders.tid AS invoice_number,
             cberp_purchase_orders.total AS amount,
             cberp_purchase_orders.tax AS tax,
             cberp_suppliers.name AS customer_name,
             cberp_suppliers.company AS Company_Name,
             cberp_purchase_orders.invoicedate AS date
        ');
        $this->db->from('cberp_purchase_orders');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_purchase_orders.csd','left');  
        $this->db->where($where);
        $this->db->order_by('cberp_purchase_orders.id','DESC');
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }  
 
        $query = $this->db->get();
        $result = $query->result_array();

        return  $result;
    }
    public function get_purchses_tax_statement_count($sdate, $edate, $lid)
    {
        $where = "DATE(cberp_purchase_orders.invoicedate) BETWEEN '$sdate' AND '$edate'"; 
        $this->db->select('
             cberp_suppliers.tax_id AS VAT_Number,
             cberp_purchase_orders.tid AS invoice_number,
             cberp_purchase_orders.total AS amount,
             cberp_purchase_orders.tax AS tax,
             cberp_suppliers.name AS customer_name,
             cberp_suppliers.company AS Company_Name,
             cberp_purchase_orders.invoicedate AS date
        ');
        $this->db->from('cberp_purchase_orders');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_purchase_orders.csd','left');  
        $this->db->where($where);
        $this->db->order_by('cberp_purchase_orders.id','DESC');
        $total_count = $this->db->count_all_results();
        return $total_count;
    }


    public function get_statements_employee($pay_emp, $trans_type, $sdate, $edate)
    {

        if ($trans_type == 'All') {
            $where = "payerid	='$pay_emp'  AND ext='4' AND (DATE(date) BETWEEN '$sdate' AND '$edate') ";
        } else {
            $where = "payerid	='$pay_emp'  AND ext='4' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type'";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);


        //  $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function get_statements_cat($pay_cat, $trans_type, $sdate, $edate)
    {

        if ($trans_type == 'All') {
            $where = "cat='$pay_cat' AND (DATE(date) BETWEEN '$sdate' AND '$edate') ";
        } else {
            $where = "cat='$pay_cat' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type'";
        }
        // if ($this->aauth->get_user()->loc) {
        //     $where .= " AND loc='" . $this->aauth->get_user()->loc . "'";
        // } elseif (!BDATA) {
        //     $where .= " AND loc='0'";
        // }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);


        //  $this->db->order_by('id', 'DESC');
       
          
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    //transaction account statement

    var $table = 'cberp_transactions';
    var $column_order = array(null, 'account', 'type', 'cat', 'amount', 'stat');
    var $column_search = array('id', 'account');
    var $order = array('id' => 'asc');
    var $opt = '';


    //income statement


    public function incomestatement()
    {
        $this->db->select_sum('lastbal');
        $this->db->from('cberp_accounts');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }

        $query = $this->db->get();
        $result = $query->row_array();

        $lastbal = $result['lastbal'];

        $this->db->select_sum('credit');
        $this->db->from('cberp_transactions');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $this->db->where('type', 'Income');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(date) >=', "$month-01");
        $this->db->where('DATE(date) <=', $today);

        $query = $this->db->get();
        $result = $query->row_array();

        $motnhbal = $result['credit'];
        return array('lastbal' => $lastbal, 'monthinc' => $motnhbal);

    }

    public function customincomestatement($acid, $sdate, $edate)
    {


        $this->db->select_sum('credit');
        $this->db->from('cberp_transactions');
        if ($acid > 0) {
            $this->db->where('acid', $acid);
        }
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $this->db->where('type', 'Income');
        $this->db->where('DATE(date) >=', $sdate);
        $this->db->where('DATE(date) <=', $edate);
        // $this->db->where("DATE(date) BETWEEN '$sdate' AND '$edate'");
        $query = $this->db->get();
        $result = $query->row_array();

        return $result;
    }

    //expense statement


    public function expensestatement()
    {


        $this->db->select_sum('debit');
        $this->db->from('cberp_transactions');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $this->db->where('type', 'Expense');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(date) >=', "$month-01");
        $this->db->where('DATE(date) <=', $today);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        $result = $query->row_array();

        $motnhbal = $result['debit'];
        return array('monthinc' => $motnhbal);

    }

    public function customexpensestatement($acid, $sdate, $edate)
    {


        $this->db->select_sum('debit');
        $this->db->from('cberp_transactions');
        if ($acid > 0) {
            $this->db->where('acid', $acid);
        }
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $this->db->where('type', 'Expense');
        $this->db->where('DATE(date) >=', $sdate);
        $this->db->where('DATE(date) <=', $edate);
        $query = $this->db->get();
        $result = $query->row_array();

        return $result;
    }

    public function statistics($limit = false)
    {
        $this->db->from('cberp_reports');
        // if($limit) $this->db->limit(12);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_customer_account_statements($pay_acc, $trans_type, $sdate, $edate)
    {

        if ($trans_type == 'All') {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND ext=0";
        } else {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type' AND ext=0";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);
        
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }
    public function get_customer_statements($pay_acc, $trans_type, $sdate, $edate)
    {

        if ($trans_type == 'All') {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND ext=0";
        } else {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type' AND ext=0";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);
        // search section starts
        $i = 0;
        $search_value = $this->input->post('search')['value'];
        $search_value_clean = str_replace(',', '', $search_value);
        foreach ($this->column_search_statements as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                    if ($i === 0) {
                        $this->db->group_start(); // Open group for OR conditions
                    }

                    // Check if the current column is 'cberp_transactions.date'
                    if ($item === 'cberp_transactions.date') {
                        // Convert search value to Y-m-d format if it's a date input
                        $formatted_date = date('Y-m-d', strtotime($search_value_clean));
                        $this->db->or_like($item, $formatted_date); // Search in date column
                    } else {
                        // Regular search for other columns
                        $this->db->or_like($item, $search_value_clean);
                    }

                    // Close the group on the last iteration
                    if (count($this->column_search_statements) - 1 == $i) {
                        $this->db->group_end();
                    }
            }
            $i++;
        }
        // search section starts

        
         if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order_statements[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->column_order_statements)) {
            $order = $this->column_order_statements;
            $this->db->order_by(key($column_order_statements), $column_order_statements[key($order)]);
        }
        else{ 
            $order = array('id' => 'desc');
        }
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }  
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function get_customer_statements_count($pay_acc, $trans_type, $sdate, $edate)
    {
        if ($trans_type == 'All') {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND ext=0";
        } else {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type' AND ext=0";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);        
        $total_count = $this->db->count_all_results();
        return $total_count;
    }

    public function get_supplier_account_statements($pay_acc, $trans_type, $sdate, $edate)
    {
        

        if ($trans_type == 'All') {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND ext=1";
        } else {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type' AND ext=1";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);

        

        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();

        return $result;
    }
    public function get_supplier_statements($pay_acc, $trans_type, $sdate, $edate)
    {
        

        if ($trans_type == 'All') {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND ext=1";
        } else {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type' AND ext=1";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);

        // search section starts
        $i = 0;
        $search_value = $this->input->post('search')['value'];
        $search_value_clean = str_replace(',', '', $search_value);
        foreach ($this->column_search_statements as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                    if ($i === 0) {
                        $this->db->group_start(); // Open group for OR conditions
                    }

                    // Check if the current column is 'cberp_transactions.date'
                    if ($item === 'cberp_transactions.date') {
                        // Convert search value to Y-m-d format if it's a date input
                        $formatted_date = date('Y-m-d', strtotime($search_value_clean));
                        $this->db->or_like($item, $formatted_date); // Search in date column
                    } else {
                        // Regular search for other columns
                        $this->db->or_like($item, $search_value_clean);
                    }

                    // Close the group on the last iteration
                    if (count($this->column_search_statements) - 1 == $i) {
                        $this->db->group_end();
                    }
            }
            $i++;
        }
        // search section starts

        
         if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order_statements[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->column_order_statements)) {
            $order = $this->column_order_statements;
            $this->db->order_by(key($column_order_statements), $column_order_statements[key($order)]);
        }
        else{ 
            $order = array('id' => 'desc');
        }
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }  

        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();

        return $result;
    }
    public function get_supplier_statements_count($pay_acc, $trans_type, $sdate, $edate)
    {

        if ($trans_type == 'All') {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND ext=1";
        } else {
            $where = "payerid='$pay_acc' AND (DATE(date) BETWEEN '$sdate' AND '$edate') AND type='$trans_type' AND ext=1";
        }
        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where($where);
        $query = $this->db->get();
        $total_count = $this->db->count_all_results();
        return $total_count;
    }

    //
    //income statement


    public function profitstatement()
    {


    }

    public function customprofitstatement($lid, $sdate, $edate)
    {


        $this->db->select_sum('cberp_metadata.col1');
        $this->db->from('cberp_metadata');
        $this->db->where('cberp_metadata.type', 9);
        $this->db->where('DATE(cberp_metadata.d_date) >=', $sdate);
        $this->db->where('DATE(cberp_metadata.d_date) <=', $edate);
        $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_metadata.rid', 'left');

        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_invoices.loc', $lid);
        // } else {

            $this->db->group_start();
            $this->db->where('cberp_invoices.loc', $lid);
            $this->db->or_where('cberp_invoices.loc', 0);
            $this->db->group_end();
        // }

        $query = $this->db->get();
        $result = $query->row_array();

        return $result;
    }

    public function customcommission($lid, $sdate, $edate)
    {

        $this->db->select('c_rate');
        $this->db->from('cberp_employees');
        $this->db->where('id', $lid);
        $query = $this->db->get();
        $result_e = $query->row_array();
        $this->db->select_sum('total');
        $this->db->from('cberp_invoices');
        $this->db->where('eid', $lid);
        $this->db->where('status !=', 'canceled');
        $this->db->where('DATE(cberp_invoices.invoicedate) >=', $sdate);
        $this->db->where('DATE(cberp_invoices.invoiceduedate) <=', $edate);
        $query = $this->db->get();
        $result = $query->row_array();
        if ($result_e['c_rate'] > 0 AND $result['total'] > 0) {
            $amount = ($result_e['c_rate'] * $result['total']) / 100;
            return $amount;
        } else {
            return 0;
        }
    }

    //sales statement


    public function salesstatement()
    {

    }

    public function customsalesstatement($lid, $sdate, $edate)
    {
        $this->db->select_sum('total');
        $this->db->from('cberp_invoices');
        $this->db->where('DATE(invoicedate) >=', $sdate);
        $this->db->where('DATE(invoicedate) <=', $edate);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_invoices.loc', $lid);
        // } else {
            $this->db->group_start();
            $this->db->where('cberp_invoices.loc', $lid);
            $this->db->or_where('cberp_invoices.loc', 0);
            $this->db->group_end();
        // }

        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    //products statement


    public function productsstatement()
    {
        $this->db->select_sum('qty');
        $this->db->select_sum('subtotal');
        $this->db->from('cberp_invoice_items');
        $query = $this->db->get();
        $result = $query->row_array();
        $qty = $result['qty'];
        $subtotal = $result['subtotal'];

        $this->db->select_sum('cberp_invoice_items.qty');
        $this->db->select_sum('cberp_invoice_items.subtotal');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_invoice_items.tid', 'left');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(cberp_invoices.invoicedate) >=', "$month-01");
        $this->db->where('DATE(cberp_invoices.invoicedate) <=', $today);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_invoices.loc', 0);
        // }
        $query = $this->db->get();
        $result = $query->row_array();
        $qty_m = $result['qty'];
        $subtotal_m = $result['subtotal'];
        return array('qty' => $qty, 'qty_m' => $qty_m, 'subtotal' => $subtotal, 'subtotal_m' => $subtotal_m);
    }

    public function customproductsstatement($lid, $sdate, $edate)
    {

        $this->db->select_sum('cberp_invoice_items.qty');
        $this->db->select_sum('cberp_invoice_items.subtotal');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_invoice_items.tid', 'left');
        $this->db->where('DATE(cberp_invoices.invoicedate) >=', $sdate);
        $this->db->where('DATE(cberp_invoices.invoicedate) <=', $edate);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_invoices.loc', $lid);
        // } else {
            $this->db->group_start();
            $this->db->where('cberp_invoices.loc', $lid);
            $this->db->or_where('cberp_invoices.loc', 0);
            $this->db->group_end();
        // }

        $query = $this->db->get();
        $result = $query->row_array();

        return $result;
    }

    public function customproductsstatement_cat($lid, $sdate, $edate)
    {

        $this->db->select_sum('cberp_invoice_items.qty');
        $this->db->select_sum('cberp_invoice_items.subtotal');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_invoice_items.tid', 'left');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
        $this->db->where('DATE(cberp_invoices.invoicedate) >=', $sdate);
        $this->db->where('DATE(cberp_invoices.invoicedate) <=', $edate);
        if ($lid > 0) {
            $this->db->where('cberp_products.pid', $lid);
        }
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    //fetch data

    public function fetchdata($page)
    {
        switch ($page) {
            case 'products' :
                $this->db->select_sum('cberp_invoice_items.qty');
                $this->db->select_sum('cberp_invoice_items.subtotal');
                $this->db->from('cberp_invoice_items');
                $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_invoice_items.tid', 'left');
                // if ($this->aauth->get_user()->loc) {
                //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
                // } elseif (!BDATA) {
                //     $this->db->where('cberp_invoices.loc', 0);
                // }
                $query = $this->db->get();
                $result = $query->row_array();
                $qty = $result['qty'];
                $subtotal = $result['subtotal'];
                $this->db->select_sum('cberp_invoice_items.qty');
                $this->db->select_sum('cberp_invoice_items.subtotal');
                $this->db->from('cberp_invoice_items');
                $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_invoice_items.tid', 'left');
                // if ($this->aauth->get_user()->loc) {
                //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
                // } elseif (!BDATA) {
                //     $this->db->where('cberp_invoices.loc', 0);
                // }
                $month = date('Y-m');
                $today = date('Y-m-d');
                $this->db->where('DATE(cberp_invoices.invoicedate) >=', "$month-01");
                $this->db->where('DATE(cberp_invoices.invoicedate) <=', $today);
                $query = $this->db->get();
                $result = $query->row_array();
                $qty_m = $result['qty'];
                $subtotal_m = $result['subtotal'];
                return array('p1' => $qty, 'p2' => $qty_m, 'p3' => amountExchange($subtotal, 0, $this->aauth->get_user()->loc), 'p4' => amountExchange($subtotal_m, 0, $this->aauth->get_user()->loc));
                break;
            case 'sales' :
                $this->db->select_sum('total');
                $this->db->from('cberp_invoices');
                // if ($this->aauth->get_user()->loc) {
                //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
                // } elseif (!BDATA) {
                //     $this->db->where('cberp_invoices.loc', 0);
                // }
                $query = $this->db->get();
                $result = $query->row_array();
                $lastbal = $result['total'];
                $this->db->select_sum('total');
                $this->db->from('cberp_invoices');
                // if ($this->aauth->get_user()->loc) {
                //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
                // } elseif (!BDATA) {
                //     $this->db->where('cberp_invoices.loc', 0);
                // }
                $month = date('Y-m');
                $today = date('Y-m-d');
                $this->db->where('DATE(invoicedate) >=', "$month-01");
                $this->db->where('DATE(invoicedate) <=', $today);
                $query = $this->db->get();
                $result = $query->row_array();
                $motnhbal = $result['total'];
                return array('p1' => amountExchange($lastbal, 0, $this->aauth->get_user()->loc), 'p2' => amountExchange($motnhbal, 0, $this->aauth->get_user()->loc), 'p3' => 0, 'p4' => 0);

                break;

            case 'profit':

                $this->db->select_sum('cberp_metadata.col1');
                $this->db->from('cberp_metadata');
                $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_metadata.rid', 'left');
                $this->db->where('cberp_metadata.type', 9);
                // if ($this->aauth->get_user()->loc) {
                //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
                // } elseif (!BDATA) {
                //     $this->db->where('cberp_invoices.loc', 0);
                // }
                $query = $this->db->get();
                $result = $query->row_array();
                $lastbal = $result['col1'];
                $this->db->select_sum('cberp_metadata.col1');
                $this->db->from('cberp_metadata');
                $this->db->where('cberp_metadata.type', 9);
                $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_metadata.rid', 'left');
                // if ($this->aauth->get_user()->loc) {
                //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
                // } elseif (!BDATA) {
                //     $this->db->where('cberp_invoices.loc', 0);
                // }
                $month = date('Y-m');
                $today = date('Y-m-d');
                $this->db->where('DATE(cberp_metadata.d_date) >=', "$month-01");
                $this->db->where('DATE(cberp_metadata.d_date) <=', $today);
                $query = $this->db->get();
                $result = $query->row_array();
                $motnhbal = $result['col1'];
                return array('p1' => amountExchange($lastbal, 0, $this->aauth->get_user()->loc), 'p2' => amountExchange($motnhbal, 0, $this->aauth->get_user()->loc), 'p3' => 0, 'p4' => 0);
        }
    }


        public function product_customer_statements($customer, $sdate, $edate)
    {
        $this->db->select('cberp_invoice_items.*,cberp_invoices.invoicedate,cberp_invoices.tid AS inv');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_invoice_items.tid', 'left');

        $this->db->where('DATE(cberp_invoices.invoicedate) >=', $sdate);
        $this->db->where('DATE(cberp_invoices.invoicedate) <=', $edate);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_invoices.loc', 0);
        // }
         $this->db->where('cberp_invoices.csd', $customer);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }


        public function product_supplier_statements($customer, $sdate, $edate)
    {
        $this->db->select('cberp_purchase_order_items.*,cberp_purchase_orders.invoicedate,cberp_purchase_orders.tid AS inv');
        $this->db->from('cberp_purchase_order_items');
        $this->db->join('cberp_purchase_orders', 'cberp_purchase_orders.id = cberp_purchase_order_items.tid', 'left');
        $this->db->where('DATE(cberp_purchase_orders.invoicedate) >=', $sdate);
        $this->db->where('DATE(cberp_purchase_orders.invoicedate) <=', $edate);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_purchase_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_purchase_orders.loc', 0);
        // }
         $this->db->where('cberp_purchase_orders.csd', $customer);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }
    public function unpaid_customer_list()
    {
        $this->db->select('cberp_customers.customer_id,cberp_customers.company'); 
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_invoices.csd');
        $this->db->where_in('status', ['due', 'partial']);
        $this->db->group_by('cberp_invoices.csd');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;

    }

    public function payment_list_for_compay_by_id($id)
    {
        $this->db->select("
            SUM(CASE 
                WHEN DATE(invoiceduedate) = CURDATE() 
                THEN total ELSE 0 END) AS today_total,
        
            SUM(CASE 
                WHEN invoiceduedate BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() - INTERVAL 1 DAY
                THEN total ELSE 0 END) AS total_1_30_days,
        
            SUM(CASE 
                WHEN invoiceduedate BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() - INTERVAL 31 DAY 
                THEN total ELSE 0 END) AS total_31_60_days,
        
            SUM(CASE 
                WHEN invoiceduedate BETWEEN CURDATE() - INTERVAL 90 DAY AND CURDATE() - INTERVAL 61 DAY 
                THEN total ELSE 0 END) AS total_61_90_days,
        
            SUM(CASE 
                WHEN invoiceduedate < CURDATE() - INTERVAL 90 DAY 
                THEN total ELSE 0 END) AS total_above_90_days
        ");
        
        $this->db->from('cberp_invoices');
        $this->db->where('status', 'due');
        $this->db->where('csd', $id);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    

    }

    public function payment_list_for_supplier_by_id($id)
    {
        $this->db->select("total,tid,purchase_number,pamnt,invoicedate,invoiceduedate,status,

            CASE 
                WHEN invoiceduedate BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
                THEN (total - pamnt) ELSE 0 END AS total_1_30_days,

            CASE 
                WHEN invoiceduedate BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() - INTERVAL 31 DAY 
                THEN (total - pamnt) ELSE 0 END AS total_31_60_days,

            CASE 
                WHEN invoiceduedate BETWEEN CURDATE() - INTERVAL 90 DAY AND CURDATE() - INTERVAL 61 DAY 
                THEN (total - pamnt) ELSE 0 END AS total_61_90_days,

            CASE 
                WHEN invoiceduedate < CURDATE() - INTERVAL 90 DAY 
                THEN (total - pamnt) ELSE 0 END AS total_above_90_days
        ");
        
        $this->db->from('cberp_purchase_orders');
        $this->db->where('csd', $id);
        $this->db->where('status !=', 'post dated cheque');
        $this->db->where('invoiceduedate <=', 'NOW()', false);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }


    public function supplier_list_for_pay()
    {
        $this->db->select('cberp_suppliers.supplier_id,cberp_suppliers.name,cberp_suppliers.company'); 
        $this->db->from('cberp_purchase_orders');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_purchase_orders.csd');
        // $this->db->where('status', 'due');
        $this->db->where('(cberp_purchase_orders.total - cberp_purchase_orders.pamnt) > 0');
        $this->db->group_by('cberp_purchase_orders.csd');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;

    }

    //list all customer detalis
    public function customer_list()
    {
        $this->db->select('cberp_customers.customer_id,cberp_customers.company,cberp_customers.name'); 
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_invoices.csd');
        // $this->db->where('status', 'due');
        $this->db->group_by('cberp_invoices.csd');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;

    }
    

    public function supplier_lists()
    {
        $this->db->select('cberp_suppliers.supplier_id,cberp_suppliers.name,cberp_suppliers.company'); 
        $this->db->from('cberp_purchase_orders');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_purchase_orders.customer_id');
        // $this->db->where('status', 'due');
        $this->db->group_by('cberp_purchase_orders.customer_id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;

    }

    public function payment_list_for_suppliers_byid($id)
    {
        $this->db->select("id,payment_status AS status,duedate,purchase_order_date,order_total,purchase_number,paid_amount,
            CASE 
                WHEN duedate BETWEEN CURDATE() - INTERVAL 30 DAY AND  CURDATE()
                THEN (order_total - paid_amount) ELSE 0 END AS total_1_30_days,
        
            CASE 
                WHEN duedate BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() - INTERVAL 31 DAY 
                THEN  (order_total - paid_amount)  ELSE 0 END AS total_31_60_days,
        
            CASE 
                WHEN duedate BETWEEN CURDATE() - INTERVAL 90 DAY AND CURDATE() - INTERVAL 61 DAY 
                THEN  (order_total - paid_amount)  ELSE 0 END AS total_61_90_days,
        
            CASE 
                WHEN duedate < CURDATE() - INTERVAL 90 DAY 
                THEN  (order_total - paid_amount)  ELSE 0 END AS total_above_90_days
        ");
        
        $this->db->from('cberp_purchase_orders');
        // $this->db->where('status', 'due');
        $this->db->where('customer_id', $id);
        
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();
        
        return $result;   

    }

    public function invoice_list_for_compay_by_id($id)
    {
        $this->db->select("id, status, invoiceduedate, invoicedate, total, tid,invoice_number, payment_recieved_amount,

            CASE 
                WHEN invoiceduedate BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
                THEN (total - payment_recieved_amount) ELSE 0 END AS total_1_30_days,

            CASE 
                WHEN invoiceduedate BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() - INTERVAL 31 DAY 
                THEN (total - payment_recieved_amount) ELSE 0 END AS total_31_60_days,

            CASE 
                WHEN invoiceduedate BETWEEN CURDATE() - INTERVAL 90 DAY AND CURDATE() - INTERVAL 61 DAY 
                THEN (total - payment_recieved_amount) ELSE 0 END AS total_61_90_days,

            CASE 
                WHEN invoiceduedate < CURDATE() - INTERVAL 90 DAY 
                THEN (total - payment_recieved_amount) ELSE 0 END AS total_above_90_days
        ");
        
        $this->db->from('cberp_invoices');
        $this->db->where('csd', $id);
        $this->db->where('status !=', 'post dated cheque');
        $this->db->where('invoiceduedate <=', 'NOW()', false);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }

    public function coa_accounts_with_transactions()
    {
        $this->db->select('cberp_accounts.acn, cberp_accounts.holder, cberp_accounts.lastbal, cberp_coa_types.typename');
        $this->db->from('cberp_coa_types');
        $this->db->join('cberp_accounts', 'cberp_accounts.account_type_id = cberp_coa_types.coa_type_id');
        $this->db->join('cberp_coa_headers', 'cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id');
        $this->db->where('cberp_accounts.lastbal !=', 0);
        $this->db->order_by('cberp_coa_headers.accounting_sort_order','ASC');
        $query = $this->db->get();
        return $query->result_array(); 

    }

    public function balance_sheet_report()
    {
        $this->db->select('
            cberp_coa_headers.coa_header_id,
            cberp_coa_headers.coa_header,
            cberp_coa_types.coa_type_id,
            cberp_coa_types.typename,
            cberp_accounts.acn,
            cberp_accounts.holder,
            cberp_accounts.default_flg,
            cberp_accounts.adate,
            cberp_accounts.lastbal,
            cberp_accounts.code,
            cberp_accounts.loc,
            cberp_accounts.parent_account_id
        ');
        $this->db->from('cberp_coa_headers');
        $this->db->join('cberp_coa_types', 'cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id');
        $this->db->join('cberp_accounts', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id');
        $this->db->where_in('cberp_coa_headers.coa_header', ['Assets', 'Liabilities', 'Equity']);
        //remove Cash & Cash Equivalents
        $this->db->where_in('cberp_accounts.holder', ['Bank - Current Account','Petty Cash', 'Accounts Receivable', 'Less: Allowance for Doubtful Accounts','Inventory','Prepaid Expenses','Short-Term Investments','Property, Plant & Equipment (PPE)','Less: Accumulated Depreciation','Intangible Assets (Patents, Goodwill)','Long-Term Investments','Deferred Tax Assets','Accounts Payable','Short-Term Loan Payable','Long-Term Loan Payable','Retained Earnings']);
        // $this->db->where('cberp_accounts.lastbal !=',0.00);
        $this->db->order_by('accounting_sort_order', 'ASC');
        $this->db->order_by('cberp_coa_types.id', 'ASC');

        // Execute the query
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();

    }
    

    // Function to fetch quarter-wise and month-wise sums for a given year
    public function coa_accounts_total_transaction_amount($header,$year) {
        // Build the query
        $this->db->select("
            cberp_transactions.acid AS account_id,
            cberp_accounts.holder,
            QUARTER(cberp_transactions.date) AS quarter,
            cberp_coa_headers.coa_header,
            cberp_coa_types.typename,
            CONCAT(
                CASE 
                    WHEN QUARTER(cberp_transactions.date) = 1 THEN 'First'
                    WHEN QUARTER(cberp_transactions.date) = 2 THEN 'Second'
                    WHEN QUARTER(cberp_transactions.date) = 3 THEN 'Third'
                    WHEN QUARTER(cberp_transactions.date) = 4 THEN 'Fourth'
                END
            ) AS quarter_label,
            MONTH(cberp_transactions.date) AS month,
            DATE_FORMAT(cberp_transactions.date, '%M') AS month_name,
            YEAR(cberp_transactions.date) AS year, 
            CASE 
                WHEN cberp_transactions.debit > 0 THEN 'debit'
                WHEN cberp_transactions.credit > 0 THEN 'credit'
            END AS transtype,
            SUM(cberp_transactions.debit - cberp_transactions.credit) AS amount
        ");
        
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid', 'inner');
        $this->db->join('cberp_coa_types', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id', 'inner');
        $this->db->join('cberp_coa_headers', 'cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id', 'inner');
        
        // Filter by year (passed dynamically)
        $this->db->where('YEAR(cberp_transactions.date)', $year);
        $this->db->where('cberp_coa_headers.coa_header', $header);
        $this->db->where('cberp_transactions.cat !=', 'Purchase');
        
        // Group by account ID, year, quarter, and month
        $this->db->group_by([
            'cberp_transactions.acid',
            'YEAR(cberp_transactions.date)',
            'QUARTER(cberp_transactions.date)',
            'MONTH(cberp_transactions.date)'
        ]);
        
        // Order by account ID, year, quarter, and month
        $this->db->order_by('account_id', 'asc');
        $this->db->order_by('year', 'asc');
        $this->db->order_by('quarter', 'asc');
        $this->db->order_by('month', 'asc');

        // Execute the query and return the results
        $query = $this->db->get();
        return $query->result_array();
    }

    public function trail_balance_details()
    {
        $this->db->select('cberp_coa_headers.coa_header_id,cberp_coa_headers.coa_header,cberp_transactions.acid, cberp_transactions.debit, cberp_transactions.credit, cberp_transactions.date, SUM(cberp_transactions.debit - cberp_transactions.credit) AS amount,cberp_accounts.holder,cberp_accounts.parent_account_id');
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->join('cberp_coa_types', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id');
        $this->db->join('cberp_coa_headers', 'cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id');
        $this->db->group_by('cberp_transactions.acid');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();


    }
    public function trail_balance_account_headers()
    {
        $this->db->select('cberp_coa_headers.coa_header_id,cberp_coa_headers.coa_header');
        $this->db->from('cberp_accounts');
        $this->db->join('cberp_coa_types', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id');
        $this->db->join('cberp_coa_headers', 'cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id');
        $this->db->where('cberp_accounts.lastbal !=', 0);
        $this->db->group_by('cberp_coa_headers.coa_header_id');
        $query = $this->db->get();
        return $query->result_array();

    }

    public function journal_summary_for_each_transactions()
    {
        $this->db->select('
            cberp_transactions.date,
            cberp_transactions.debit AS debitamount,
            cberp_transactions.credit AS creditamount,
            cberp_transactions.invoice_number AS typenumber,
            cberp_transactions.transaction_number,
            cberp_transactions.cat AS transcategory,
            cberp_accounts.holder,
            cberp_accounts.acn,
            cberp_payment_transaction_link.bank_transaction_number,
            cberp_payment_transaction_link.trans_type AS transationtype,
            cberp_bank_transactions.trans_ref_number AS bank_transaction_refernce,
            cberp_invoices.id AS invoiceid,
            cberp_purchase_receipts.id AS receipt_number,
            cberp_expense_claims.claim_number,
            cberp_delivery_notes.delevery_note_id,
            cberp_delivery_notes.invoice_number AS deliverynote_invoice_number,
            cberp_delivery_returns.delivery_return_number,
            deliveryinvoice.id AS deliverynote_invoiceid,
            cberp_stock_returns.id AS invoice_returnid
        ');
        $this->db->from('cberp_transactions');

        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');

        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.transaction_number = cberp_transactions.transaction_number', 'left');

        $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number', 'left');
        $this->db->join('cberp_invoices', 'cberp_invoices.transaction_number = cberp_transactions.transaction_number', 'left');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.transaction_number = cberp_transactions.transaction_number', 'left');
        $this->db->join('cberp_invoices AS deliveryinvoice', 'deliveryinvoice.invoice_number = cberp_delivery_notes.invoice_number', 'left');
        $this->db->join('cberp_delivery_returns', 'cberp_delivery_returns.transaction_number = cberp_transactions.transaction_number', 'left');
        $this->db->join('cberp_stock_returns', 'cberp_stock_returns.transaction_number = cberp_transactions.transaction_number', 'left');
        $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.transaction_number = cberp_transactions.transaction_number', 'left');
        $this->db->join('cberp_expense_claims', 'cberp_expense_claims.transaction_number = cberp_transactions.transaction_number', 'left');
        $this->db->order_by('cberp_transactions.date', 'DESC');
        $query = $this->db->get();

        return $query->result_array();

    }

    public function unique_transaction_number_with_date()
    {
        $this->db->select('cberp_transactions.transaction_number, cberp_transactions.date');
        $this->db->from('cberp_transactions');
        $this->db->group_by('cberp_transactions.transaction_number');
        $query = $this->db->get();
        return $query->result_array();

    }

    //erp2024 05-03-2024
    public function coa_accounts_total_transaction_amount_for_type_revenue($header,$date) {
        // Build the query
        $this->db->select("
            cberp_transactions.acid AS account_id,
            cberp_accounts.holder,
            YEAR(cberp_transactions.date) AS year,
            cberp_coa_headers.coa_header,
            cberp_coa_types.typename,
            SUM(cberp_transactions.credit - cberp_transactions.debit) AS amount
        ");
        
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid', 'left');
        $this->db->join('cberp_coa_types', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id', 'left');
        $this->db->join('cberp_coa_headers', 'cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id', 'left');
        
        // Filter by year (passed dynamically)
        $this->db->where('cberp_transactions.date >=', $date);
        // $this->db->where('YEAR(cberp_transactions.date)', $year);
        $this->db->where('cberp_coa_types.typename', $header);
        
        // Group by account ID, year, quarter, and month
        $this->db->group_by([
            'cberp_transactions.acid',
            'YEAR(cberp_transactions.date)',
            'cberp_coa_headers.coa_header',
            'cberp_coa_types.typename'
        ]);

        
        // Order by account ID, year, quarter, and month
        $this->db->order_by('account_id', 'asc');
        $this->db->order_by('year', 'asc');
        // $this->db->order_by('quarter', 'asc');
        // $this->db->order_by('month', 'asc');

        // Execute the query and return the results
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }

    public function operating_activities($accounts_payable_id,$accounts_recievable_id,$accounts_inventory_id)
    {
        $this->db->select("acn as account_id,holder,lastbal as amount");
        
        $this->db->from('cberp_accounts');     
        $this->db->where_in('acn', [$accounts_payable_id,$accounts_recievable_id,$accounts_inventory_id]);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }

    public function coa_accounts_total_transaction_amount_by_accountcode($acid,$date) {
        // Build the query
        $this->db->select("
            cberp_transactions.acid AS account_id,
            cberp_accounts.holder,
            YEAR(cberp_transactions.date) AS year,
            cberp_coa_headers.coa_header,
            cberp_coa_types.typename,
            SUM(cberp_transactions.credit - cberp_transactions.debit) AS amount
        ");
        
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid', 'left');
        $this->db->join('cberp_coa_types', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id', 'left');
        $this->db->join('cberp_coa_headers', 'cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id', 'left');
        
        // Filter by year (passed dynamically)
        $this->db->where('cberp_transactions.date >=', $date);
        // $this->db->where('YEAR(cberp_transactions.date)', $year);
        $this->db->where('cberp_transactions.acid', $acid);
        
        // Group by account ID, year, quarter, and month
        $this->db->group_by([
            'cberp_transactions.acid',
            'YEAR(cberp_transactions.date)',
            'cberp_coa_headers.coa_header',
        ]);

        
        // Order by account ID, year, quarter, and month
        $this->db->order_by('account_id', 'asc');
        $this->db->order_by('year', 'asc');
        // $this->db->order_by('quarter', 'asc');
        // $this->db->order_by('month', 'asc');

        // Execute the query and return the results
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }

    public function ar_sales_on_credit($month) {
        $date = date('Y')."-01-01";
        $this->db->select("SUM(cberp_invoices.total) AS invoicetotal");
        $this->db->from('cberp_invoices');
        $this->db->where('cberp_invoices.invoicedate >=', $date);
        $this->db->where('cberp_invoices.status', 'due');
        $this->db->where('cberp_invoices.invoice_type ', 'POS');
        $query1 = $this->db->get();
        // die($this->db->last_query());
        $invoiceTotal = $query1->row()->invoicetotal ?? 0; 

        $this->db->select("SUM(cberp_delivery_notes.total_amount) AS deliverytotal");
        $this->db->from('cberp_delivery_notes');
        $this->db->where('cberp_delivery_notes.created_date >=', $date);
        $this->db->where('cberp_delivery_notes.status !=', 'Invoiced');
        $query2 = $this->db->get();
        // die($this->db->last_query());
        $deliveryTotal = $query2->row()->deliverytotal ?? 0; 
        return ($invoiceTotal+$deliveryTotal);
    }
    public function ar_sales_return($month) {
        
        $date = date('Y')."-01-01";
        $time = " 00:00:00";
        $this->db->select("SUM(cberp_stock_returns.subtotal) AS invoicetotal");
        $this->db->from('cberp_stock_returns');
        $this->db->where('cberp_stock_returns.created_dt >=', $date.$time);
        // $this->db->where('cberp_stock_returns.status', 'due');
        $this->db->where('cberp_stock_returns.invoice_id IS NOT NULL');
        $this->db->where('cberp_stock_returns.delivery_return_number IS NULL');
        $query1 = $this->db->get();
        // die($this->db->last_query());
        $invoiceTotal = $query1->row()->invoicetotal ?? 0; 

        $this->db->select("SUM(cberp_delivery_returns.total_amount) AS returntotal");
        $this->db->from('cberp_delivery_returns');
        $this->db->where('cberp_delivery_returns.created_date >=', $date);
        // $this->db->where('cberp_delivery_returns.convert_to_credit_note_flag !=', '1');
        $this->db->where('cberp_delivery_returns.approval_flg', '1');
        $query2 = $this->db->get();
        
        $returntotal = $query2->row()->returntotal ?? 0; 
        return ($returntotal);
    }
    public function ar_sales_payment_received($month) {
        $date = date('Y')."-01-01";
        $this->db->select("SUM(cberp_invoices.pamnt) AS total_paid_amount");
        $this->db->from("cberp_invoices");        
        $this->db->where("cberp_invoices.payment_recieved_date >=",$date);

        $query = $this->db->get();
        // die($this->db->last_query());
        $totalPaidAmount = $query->row()->total_paid_amount ?? 0;
        return ($totalPaidAmount);
    }
    public function ap_purchase_credit() {
   
        $date = date('Y')."-01-01";
        $time = " 00:00:00";
        $this->db->select("SUM(cberp_purchase_receipts.bill_amount) AS purchasetotal");
        $this->db->from("cberp_purchase_receipts");        
        $this->db->where("cberp_purchase_receipts.created_date >=",$date.$time);
        // $this->db->where("cberp_purchase_receipts.receipt_type ",'Genuine');

        $query = $this->db->get();
        $purchasetotal = $query->row()->purchasetotal ?? 0;
        return ($purchasetotal);
    }

    public function ap_purchase_paid() {
        
        $date = date('Y')."-01-01";
        $time = " 00:00:00";
        $this->db->select("SUM(cberp_purchase_receipts.purchase_paid_amount) AS paid_amount");
        $this->db->from("cberp_purchase_receipts");        
        $this->db->where("cberp_purchase_receipts.purchase_paid_date >=",$date);
        // $this->db->where("cberp_purchase_receipts.receipt_type ",'Genuine');

        $query = $this->db->get();
        $paid_amount = $query->row()->paid_amount ?? 0;
        return ($paid_amount);
    }

    public function ap_purchase_return() {
        
        $date = date('Y')."-01-01";
        $time = " 00:00:00";
        $this->db->select("SUM(cberp_stock_returns.total) AS returntotal");
        $this->db->from('cberp_stock_returns');
        $this->db->where('cberp_stock_returns.sent_dt >=', $date.$time);
        // $this->db->where('cberp_stock_returns.status', 'due');
        $this->db->where('cberp_stock_returns.invoice_id IS NULL');
        $this->db->where('cberp_stock_returns.delivery_return_number IS NULL');
        $query1 = $this->db->get();
        // die($this->db->last_query());
        $returntotal = $query1->row()->returntotal ?? 0; 

        return ($returntotal);
    }

   
    public function products_from_average_cost_table()
    {
        $this->db->select('cberp_products.pid,cberp_products.product_code,cberp_products.product_name');
        $this->db->from('cberp_average_cost');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_average_cost.product_id');
        $this->db->join('cberp_product_ai', 'cberp_product_ai.product_id = cberp_products.pid', 'left');
        $this->db->group_by('cberp_average_cost.product_id');
        $query = $this->db->get();
        return $query->result_array(); 
    }

    public function get_average_cost_product_lists()
    {
        $this->db->select('cberp_average_cost.*, cberp_products.product_name, cberp_products.product_code, cberp_cost_transaction_type.transaction_type_name,cberp_employees.name as employee');
        $this->db->from('cberp_average_cost');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_average_cost.product_id');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_average_cost.added_by');
        $this->db->join('cberp_cost_transaction_type', 'cberp_cost_transaction_type.transaction_type_id = cberp_average_cost.transaction_type');
        // if($product_id)
        // {
        //     $this->db->where('cberp_average_cost.product_id', $product_id);
        // }       
        // $this->db->group_by('cberp_average_cost.product_id');
        $query = $this->db->get();
        return ($query->result());
    }

    private function _get_datatables_query($opt = '')
    {
        $this->db->select('cberp_average_cost.*, cberp_product_description.product_name, cberp_product_description.product_code, cberp_cost_transaction_type.transaction_type_name,cberp_employees.name as employee');
        $this->db->from('cberp_average_cost');
        // $this->db->join('cberp_products', 'cberp_products.product_code = cberp_average_cost.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_average_cost.product_code');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_average_cost.added_by');
        $this->db->join('cberp_cost_transaction_type', 'cberp_cost_transaction_type.transaction_type_id = cberp_average_cost.transaction_type');

        // if ($this->input->post('start_date') && $this->input->post('end_date')) {
        //     $start_date = datefordatabase($this->input->post('start_date'));
        //     $end_date = datefordatabase($this->input->post('end_date'));
        //     $this->db->where("DATE(cberp_invoices.invoicedate) BETWEEN '$start_date' AND '$end_date'");
        // }

        if($this->input->post('product_code'))
        {
            $this->db->where('cberp_average_cost.product_code', $this->input->post('product_code'));
        }       
        $i = 0;
        
        foreach ($this->column_search_avgcosting as $item) {
            if ($this->input->post('search')['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }
                if (count($this->column_search_avgcosting) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) 
        {
            $this->db->order_by('cberp_average_cost.product_id', 'ASC');
           
                $this->db->order_by($this->column_order_avgcosting[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
            
            // $this->db->order_by('cberp_average_cost.transaction_date_time', 'ASC');
        
        } 
        // else if (isset($this->column_order_avgcosting)) {
        //     $order = $this->column_order_avgcosting;
        //     $this->db->order_by(key($column_order_avgcosting), $column_order_avgcosting[key($order)]);
        // }
        else {
            $this->db->order_by('cberp_average_cost.product_id', 'ASC');
            // $this->db->order_by('cberp_average_cost.transaction_date_time', 'ASC');
        }
    }


    function get_datatables($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($opt) {
            $this->db->where('product_id', $opt);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('cberp_average_cost.id');
        $this->db->from('cberp_average_cost');
        // $this->db->where('cberp_invoices.i_class', 0);
        if ($opt) {
            $this->db->where('cberp_average_cost.product_id', $opt);

        }
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_invoices.loc', 0); }
        return $this->db->count_all_results();
    }

    public function load_parent_by_id($id)
    {
        $this->db->select('cberp_accounts.acn,cberp_accounts.holder');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $id);
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->row_array();
        return $result;
    }

    //Aswathy Starts 
    public function get_supervisors() {
        $this->db->select('id, name');
        $this->db->from('cberp_employees');
        $this->db->where('id  IN (SELECT DISTINCT reportingto FROM cberp_employees WHERE reportingto IS NOT NULL)', NULL, FALSE);
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }

    public function get_employees_by_supervisor($supervisor_id) {
        $this->db->select('id, name');
        $this->db->from('cberp_employees');
        $this->db->where('reportingto', $supervisor_id);
        return $this->db->get()->result_array();
    }
    public function get_employee_by_id($id) {
        $this->db->select('id, name, address,city, phone, postbox');
        $this->db->from('cberp_employees');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function get_leads() {
        $this->db->select(' cberp_customer_leads.lead_id,cberp_customer_leads.lead_number, cberp_customer_leads.date_received,cberp_customers.name,COUNT(cberp_quotes.id) AS quote_count');
        $this->db->from('cberp_customer_leads');
        $this->db->join('cberp_customers', 'cberp_customer_leads.customer_id = cberp_customers.customer_id');
        $this->db->join('cberp_quotes', 'cberp_quotes.lead_id = cberp_customer_leads.lead_id');
        $this->db->group_by('cberp_customer_leads.lead_id');
        $this->db->order_by('cberp_customer_leads.lead_id', 'asc');
        $result = $this->db->get();
        // echo $this->db->last_query(); die(); 
        return $result->result_array();
    }
    public function get_lead_items($lead_id){
        $this->db->select('customer_name,cberp_customer_leads.date_received,code as product_code,product as product_name, qty, price as product_price,cberp_customer_lead_items.subtotal');
        $this->db->from('cberp_customer_lead_items'); 
        $this->db->join('cberp_customer_leads', 'cberp_customer_lead_items.tid = cberp_customer_leads.lead_id', 'left');
        $this->db->where('cberp_customer_leads.lead_number', $lead_id);
                // echo $this->db->last_query(); die(); 
        return $this->db->get()->result_array();
    
    }
    public function get_quotes_by_enquiry($lead_number) {
        $this->db->select('quote_number');
        $this->db->from('cberp_quotes');
        $this->db->join('cberp_customer_leads', 'cberp_quotes.lead_id = cberp_customer_leads.lead_id');
        $this->db->where('cberp_customer_leads.lead_number', $lead_number);
        return $this->db->get()->result_array();
    }
    public function get_quote_items($quote_number) {
        $this->db->select('invoicedate,cberp_quotes.quote_number, cberp_quotes_items.code as product_code, cberp_quotes_items.product as product_name, cberp_quotes_items.qty, cberp_quotes_items.price as product_price, cberp_quotes_items.subtotal');
        $this->db->from('cberp_quotes_items');
        $this->db->join('cberp_quotes', 'cberp_quotes_items.tid = cberp_quotes.id');
        $this->db->where('cberp_quotes.quote_number', $quote_number);
        return $this->db->get()->result_array();
    }

    //purchase order tree view
        
    public function get_pos_with_items() {
        $this->db->select('po.id AS po_id, po.purchase_number AS po_name, COUNT(i.id) AS item_count');
        $this->db->from('cberp_purchase_orders po');
        $this->db->join('cberp_purchase_order_items i', 'i.purchase_number = po.purchase_number');
        $this->db->group_by('po.purchase_number');
        $this->db->order_by('po.id', 'asc');
        $result = $this->db->get();
        return $result->result_array();
    }
    public function get_items_by_po($po_id) {
        $this->db->select('cberp_products.product_code,cberp_product_description.product_name, quantity as qty, price as product_price,cberp_purchase_order_items.subtotal');
        $this->db->from('cberp_purchase_order_items'); 
        $this->db->join('cberp_purchase_orders', 'cberp_purchase_order_items.purchase_number = cberp_purchase_orders.purchase_number', 'left');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_purchase_order_items.product_code', 'left');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_purchase_order_items.product_code');
        $this->db->where('cberp_purchase_orders.purchase_number', $po_id);
        $result = $this->db->get();
        // die( $this->db->last_query());
        return $result->result_array();
    }
    public function check_po_return($po_number) {
        $query = $this->db->get_where('purchase_returns', ['po_number' => $po_number]);
        return $query->num_rows() > 0;

        $this->db->select('po.id AS po_id, po.purchase_number AS po_name, COUNT(i.id) AS item_count');
        $this->db->from('cberp_purchase_orders po');
        $this->db->join('cberp_purchase_order_items i', 'i.tid = po.id', 'left');
        $this->db->group_by('po.id');
        $this->db->order_by('po.id', 'desc');
        $result = $this->db->get();
        return $result->result_array();
    }
    public function check_po_reciept($po_number){
        $this->db->select('cberp_purchase_receipts.id, cberp_purchase_receipts.transaction_number, COUNT(cberp_purchase_receipt_items.id) as item_count, COUNT(cberp_purchase_receipt_expenses.id) as expense_count');
        $this->db->from('cberp_purchase_receipts');
        $this->db->join('cberp_purchase_receipt_items', 'cberp_purchase_receipt_items.purchase_reciept_number = cberp_purchase_receipts.purchase_reciept_number');
        $this->db->join('cberp_purchase_receipt_expenses', 'cberp_purchase_receipt_expenses.purchase_reciept_number = cberp_purchase_receipts.purchase_reciept_number', 'left');
        $this->db->join('cberp_purchase_orders', 'cberp_purchase_receipts.purchase_number = cberp_purchase_orders.purchase_number', 'left');
    
        $this->db->where('cberp_purchase_orders.purchase_number', $po_number);
        $this->db->group_by('cberp_purchase_receipts.purchase_reciept_number');
        $this->db->order_by('cberp_purchase_receipts.id', 'desc');
        $result = $this->db->get();
        return $result->row_array();
    }
    
    
    public function check_expense_for_receipt($reciept_id){
        $this->db->select('COUNT(e.id) AS expense_count');
        $this->db->from('cberp_purchase_receipt_expenses e');
        $this->db->where('e.stockreciptid',$reciept_id);
        // $this->db->group_by('pr.id');
        // $this->db->order_by('pr.id', 'desc');
        $result = $this->db->get();
        return $result->row_array();
    }
    public function get_reciept_by_po($purchase_reciept_id){
        $this->db->select('cberp_purchase_receipt_items.created_date as date, cberp_purchase_receipt_items.id AS id, cberp_purchase_receipts.purchase_reciept_number as recipt_id, cberp_products.product_name, cberp_purchase_receipt_items.product_code, cberp_purchase_receipt_items.ordered_quantity as product_qty, price, amount');
        $this->db->from('cberp_purchase_receipt_items');
        $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.purchase_reciept_number = cberp_purchase_receipt_items.purchase_reciept_number', 'left');
        $this->db->join('cberp_purchase_orders', 'cberp_purchase_receipts.purchase_number = cberp_purchase_orders.purchase_number', 'left');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_purchase_receipt_items.product_code', 'left');
        $this->db->where('cberp_purchase_orders.purchase_number', $purchase_reciept_id);
        $this->db->group_by('cberp_purchase_receipt_items.id');
        $this->db->order_by('cberp_purchase_receipt_items.id', 'desc');
        $result = $this->db->get();
        // die($this->db->last_query());
        return $result->result_array();
        //purchase_id
    }
    
    public function get_expenses_by_pr($purchase_reciept_id){
        $this->db->select('e.id,stockreciptid,expense_name,e.bill_number_cost as bill_no,e.bill_date_cost as date,e.costing_amount');
        $this->db->from('cberp_purchase_receipt_expenses e');
        $this->db->join('cberp_purchase_receipts pr', 'pr.id = e.stockreciptid', 'left');
        $this->db->join('cberp_purchase_orders', 'pr.purchase_id = cberp_purchase_orders.id', 'left');
        $this->db->where('cberp_purchase_orders.purchase_number',$purchase_reciept_id);
        $this->db->group_by('e.id');
        $this->db->order_by('e.id', 'desc');
        $result = $this->db->get();
        return $result->result_array();
    }
    //Aswathy Ends
}


