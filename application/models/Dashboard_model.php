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

class Dashboard_model extends CI_Model
{
    public function todayInvoice($today)
    {
        $where = "DATE(invoice_date) ='$today'";
        $this->db->where($where);
        $this->db->from('cberp_invoices');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        return $this->db->count_all_results();

    }

    public function todaySales($today)
    {

        $where = "DATE(invoice_date) ='$today'";
        $this->db->select_sum('total');
        $this->db->from('cberp_invoices');
        $this->db->where($where);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        return $query->row()->total;
    }

    public function todayInexp($today)
    {
        $this->db->select('SUM(debit) as debit,SUM(credit) as credit', FALSE);
        $this->db->where("DATE(date) ='$today'");
        $this->db->where("type!='Transfer'");
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $this->db->from('cberp_transactions');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function recent_payments()
    {
        $this->db->limit(13);
        $this->db->order_by('id', 'DESC');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $this->db->from('cberp_transactions');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function stock()
    {
        $whr = '';
        // if ($this->aauth->get_user()->loc) {
        //  $whr = ' AND (cberp_store.loc=' . $this->aauth->get_user()->loc . ')';
        // } elseif (!BDATA) {
        //  $whr = ' AND (cberp_store.loc=0)';
        // }

        $query = $this->db->query("SELECT cberp_products.*,cberp_product_description.product_name FROM cberp_products JOIN cberp_product_description ON cberp_product_description.product_code= cberp_products.product_code   WHERE (cberp_products.onhand_quantity<=cberp_products.alert_quantity) $whr ORDER BY cberp_product_description.product_name ASC LIMIT 10");

        return $query->result_array();
    }

    public function todayItems($today)
    {
        $where = "DATE(invoice_date) ='$today'";
        $this->db->select_sum('items');
        $this->db->from('cberp_invoices');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row()->items;
    }

    public function todayProfit($today)
    {
        // $where = "DATE(cberp_metadata.d_date) ='$today'";
        // $this->db->select_sum('cberp_metadata.col1');
        // $this->db->from('cberp_metadata');
        // $this->db->join('cberp_invoices', 'cberp_metadata.rid=cberp_invoices.id', 'left');
        // $this->db->where($where);
        // $this->db->where('cberp_metadata.type', 9);
        // $query = $this->db->get();
        // return $query->row()->col1;
    }

    public function incomeChart($today, $month, $year)
    {
        $whr = '';
        // if ($this->aauth->get_user()->loc) {
        //  $whr = ' AND (loc=' . $this->aauth->get_user()->loc . ')';
        // } elseif (!BDATA) {
        //  $whr = ' AND (loc=0)';
        // }
        $query = $this->db->query("SELECT SUM(credit) AS total,date FROM cberp_transactions WHERE ((DATE(date) BETWEEN DATE('$year-$month-01') AND '$today') AND type='Income')  $whr GROUP BY date ORDER BY date DESC");
        return $query->result_array();
    }

    public function expenseChart($today, $month, $year)
    {
        $whr = '';
        // if ($this->aauth->get_user()->loc) {
        //  $whr = ' AND (loc=' . $this->aauth->get_user()->loc . ')';
        // } elseif (!BDATA) {
        //  $whr = ' AND (loc=0)';
        // }
        $query = $this->db->query("SELECT SUM(debit) AS total,date FROM cberp_transactions WHERE ((DATE(date) BETWEEN DATE('$year-$month-01') AND '$today') AND type='Expense')  $whr GROUP BY date ORDER BY date DESC");
        return $query->result_array();
    }

    public function countmonthlyChart()
    {
        $today = date('Y-m-d');
        $whr = '';
        // if ($this->aauth->get_user()->loc) {
        //  $whr = ' AND (loc=' . $this->aauth->get_user()->loc . ')';
        // } elseif (!BDATA) {
        //  $whr = ' AND (loc=0)';
        // }
        $query = $this->db->query("SELECT COUNT(invoice_number) AS ttlid,SUM(total) AS total,DATE(invoice_date) as date FROM cberp_invoices WHERE (DATE(invoice_date) BETWEEN '$today' - INTERVAL 30 DAY AND '$today')  $whr GROUP BY DATE(invoice_date) ORDER BY date DESC");
        return $query->result_array();
    }


    public function monthlyInvoice($month, $year)
    {
        $today = date('Y-m-d');
		$days=date("t", strtotime($today));
        $where = "DATE(invoice_date) BETWEEN '$year-$month-01' AND '$year-$month-$days'";
        $this->db->where($where);
        $this->db->from('cberp_invoices');
        //       if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        // $this->db->get();
        return $this->db->count_all_results();

    }

    public function monthlySales($month, $year)
    {
        $today = date('Y-m-d');
		$days=date("t", strtotime($today));
        $where = "DATE(invoice_date) BETWEEN '$year-$month-01' AND '$year-$month-$days'";
        $this->db->select_sum('total');
        $this->db->from('cberp_invoices');
        $this->db->where($where);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $query = $this->db->get();
        return $query->row()->total;
    }


    public function recentInvoices()
    {
        $whr = '';

        // if ($this->aauth->get_user()->loc) {
        //  $whr = ' WHERE (i.loc=' . $this->aauth->get_user()->loc . ') ';
        // } elseif (!BDATA) {
        //    $whr = ' WHERE (i.loc=0) ';
        // }
        $query = $this->db->query("SELECT i.invoice_date,i.total,i.status,i.i_class,c.name,c.picture,i.customer_id,i.invoice_number
        FROM cberp_invoices AS i LEFT JOIN cberp_customers AS c ON i.customer_id=c.customer_id $whr ORDER BY i.invoice_date DESC LIMIT 10");
       
        return $query->result_array();

    }

        public function recentBuyers()
    {
        $this->db->trans_start();
        $whr = '';
        // if ($this->aauth->get_user()->loc) {
        //  $whr = ' WHERE (i.loc=' . $this->aauth->get_user()->loc . ') ';
        // } elseif (!BDATA) {
        //    $whr = ' WHERE (i.loc=0) ';
        // }
        $query = $this->db->query("SELECT  MAX(i.invoice_number) AS iid, i.customer_id, SUM(i.total) AS total, c.cid, MAX(c.picture) AS picture, MAX(c.name) AS name, MAX(i.status) AS status
        FROM cberp_invoices AS i
        LEFT JOIN (
            SELECT 
                cberp_customers.customer_id AS cid,
                cberp_customers.picture AS picture,
                cberp_customers.name AS name
            FROM cberp_customers
        ) AS c ON c.cid = i.customer_id
        $whr
        GROUP BY i.customer_id
        ORDER BY invoice_date DESC
        LIMIT 10;");
       
        $result= $query->result_array();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return 'sql';
        }
        else
        {
            return $result;
        }

    }

    public function tasks($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_todolist');
        $this->db->where('eid', $id);
        $this->db->limit(10);
        $this->db->order_by('DATE(duedate)', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function clockin($id)
    {
        $this->db->select('clock');
        $this->db->where('id', $id);
        $this->db->from('cberp_employees');
        $query = $this->db->get();
        $emp = $query->row_array();
        if (!$emp['clock']) {
            $data = array(
                'clock' => 1,
                'clockin' => time(),
                'clockout' => 0
            );
            $this->db->set($data);
            $this->db->where('id', $id);
            $this->db->update('cberp_employees');
            $this->aauth->applog("[Employee ClockIn]  ID $id", $this->aauth->get_user()->username);
        }
        return true;
    }

    public function clockout($id)
    {

        $this->db->select('clock,clockin');
        $this->db->where('id', $id);
        $this->db->from('cberp_employees');
        $query = $this->db->get();
        $emp = $query->row_array();

        if ($emp['clock']) {

            $data = array(
                'clock' => 0,
                'clockin' => 0,
                'clockout' => time()
            );

            $total_time = time() - $emp['clockin'];


            $this->db->set($data);
            $this->db->where('id', $id);

            $this->db->update('cberp_employees');
            $this->aauth->applog("[Employee ClockOut]  ID $id", $this->aauth->get_user()->username);

            $today = date('Y-m-d');

            $this->db->select('id,adate');
            $this->db->where('emp', $id);
            $this->db->where('DATE(adate)', date('Y-m-d'));
            $this->db->from('cberp_attendance');
            $query = $this->db->get();
            $edate = $query->row_array();
            if ($edate['adate']) {


                $this->db->set('actual_hours', "actual_hours+$total_time", FALSE);
                $this->db->set('tto', date('H:i:s'));
                $this->db->where('id', $edate['id']);
                $this->db->update('cberp_attendance');


            } else {
                $data = array(
                    'emp' => $id,
                    'adate' => date('Y-m-d'),
                    'tfrom' => gmdate("H:i:s", $emp['clockin']),
                    'tto' => date('H:i:s'),
                    'note' => 'Self Attendance',
                    'actual_hours' => $total_time
                );


                $this->db->insert('cberp_attendance', $data);
            }

        }
        return true;
    }


}
