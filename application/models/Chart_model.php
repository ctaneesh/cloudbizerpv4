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

class Chart_model extends CI_Model
{

    public function productcat($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default :
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('cberp_invoice_items.qty');
        $this->db->select_sum('cberp_invoice_items.subtotal');
        $this->db->select('cberp_invoice_items.pid');
        $this->db->select('cberp_product_category.title');
        $this->db->from('cberp_invoice_items');
        $this->db->group_by('cberp_product_category.id');
        $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_invoice_items.tid', 'left');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
        $this->db->join('cberp_product_category', 'cberp_product_category.id = cberp_products.pcat', 'left');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(cberp_invoices.invoicedate) >=', $day1);
        $this->db->where('DATE(cberp_invoices.invoicedate) <=', $day2);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('cberp_invoices.loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_invoices.loc', 0);
        // }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function trendingproducts($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default :
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }

        $this->db->select_sum('cberp_invoice_items.qty');
        $this->db->select('cberp_products.product_name');
        $this->db->from('cberp_invoice_items');
        $this->db->group_by('cberp_invoice_items.pid');
        $this->db->join('cberp_invoices', 'cberp_invoices.id = cberp_invoice_items.tid', 'left');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');

        $this->db->where('DATE(cberp_invoices.invoicedate) >=', $day1);
        $this->db->where('DATE(cberp_invoices.invoicedate) <=', $day2);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('cberp_invoices.loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_invoices.loc', 0);
        // }
        $this->db->order_by('cberp_invoice_items.qty', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function profitchart($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default :
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }

        $this->db->select_sum('cberp_metadata.col1');
        $this->db->select('cberp_metadata.d_date');
        $this->db->from('cberp_metadata');
        $this->db->group_by('cberp_metadata.d_date');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(cberp_metadata.d_date) >=', $day1);
        $this->db->where('DATE(cberp_metadata.d_date) <=', $day2);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function customerchart($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default :
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('cberp_invoices.total');
        $this->db->select('cberp_customers.name');
        $this->db->from('cberp_invoices');
        $this->db->group_by('cberp_invoices.csd');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_invoices.csd', 'left');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(cberp_invoices.invoicedate) >=', $day1);
        $this->db->where('DATE(cberp_invoices.invoicedate) <=', $day2);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('cberp_invoices.loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_invoices.loc', 0);
        // }
        $this->db->order_by('cberp_invoices.total', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    public function incomechart($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default :
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('credit');
        $this->db->select('date');
        $this->db->from('cberp_transactions');
        $this->db->group_by('date');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(date) >=', $day1);
        $this->db->where('DATE(date) <=', $day2);
        $this->db->where('type', 'Income');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function expenseschart($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default :
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('debit');
        $this->db->select('date');
        $this->db->from('cberp_transactions');
        $this->db->group_by('date');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(date) >=', $day1);
        $this->db->where('DATE(date) <=', $day2);
        $this->db->where('type', 'Expense');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function incexp($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default :
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('debit');
        $this->db->select_sum('credit');
        $this->db->select('type');
        $this->db->from('cberp_transactions');
        $this->db->group_by('type');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(date) >=', $day1);
        $this->db->where('DATE(date) <=', $day2);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //     if (BDATA) $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


}
