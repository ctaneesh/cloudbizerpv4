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


class Cronjob_model extends CI_Model
{
    var $table = 'cberp_accounts';

    public function __construct()
    {
        parent::__construct();

    }

    public function config()
    {
        $this->db->select('key1 AS cornkey, key2 AS rec_email,url AS email,method AS rec_due,other AS recemail');
        $this->db->from('univarsal_api');
        $this->db->where('id', 55);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function generate()
    {

        $random = rand(11111111, 99999999);
        $data = array(
            'key1' => $random

        );
        $this->db->set($data);
        $this->db->where('id', 55);


        if ($this->db->update('univarsal_api')) {
            return true;
        } else {
            return false;

        }

    }


    public function due_mail()
    {

        $duedate = date('Y-m-d');

        $this->db->select('cberp_invoices.*,cberp_customers.name,cberp_customers.email');
        $this->db->from('cberp_invoices');
        $this->db->where('DATE(cberp_invoices.invoiceduedate)<=', $duedate);
        $this->db->where('cberp_invoices.status', 'due');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id=cberp_invoices.csd', 'left');
        $query = $this->db->get();
        return $query->result_array();


    }


    public function reports()
    {

        $year = date('Y');

        $this->db->delete('cberp_reports', array('year' => $year));


        $query = $this->db->query("SELECT MONTH(invoicedate) AS month,YEAR(invoicedate) AS year,COUNT(tid) AS invoices,SUM(total) AS sales,SUM(items) AS items FROM cberp_invoices WHERE (YEAR(invoicedate)='$year') GROUP BY MONTH(invoicedate)");
        $arrayA = $query->result_array();

        $query = $this->db->query("SELECT MONTH(date) AS month,YEAR(date) AS year,SUM(credit) AS income,SUM(debit) AS expense FROM cberp_transactions WHERE (YEAR(date)='$year') GROUP BY MONTH(date)");
        $arrayB = $query->result_array();
        $output = array();

        $arrayAB = array_merge($arrayA, $arrayB);


        foreach ($arrayAB as $value) {
            $id = $value['month'];
            if (!isset($output[$id])) {
                $output[$id] = array();
            }
            $output[$id] = array_merge($output[$id], $value);
        }


        uasort($output, array_compare('month'));


        $batch = array();
        $i = 0;
        foreach ($output as $row) {

            $batch[$i] = array('month' => $row['month'], 'year' => $row['year'], 'invoices' => @$row['invoices'], 'sales' => @$row['sales'], 'items' => @$row['items'], 'income' => @$row['income'], 'expense' => @$row['expense']);
            $i++;
        }

        $this->db->insert_batch('cberp_reports', $batch);

        return true;


    }

    public function exchange_rate($base, $exchangeRates = '')
    {

        $updateData = array();
        //$cindex = 0;
        $this->db->select('id,code,rate');
        $this->db->from('cberp_currencies');
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $key => $value) {

            $index = $base . $value['code'];
            $updateData[] = array('id' => $value['id'], 'rate' => $exchangeRates[$index]);
            //  print_r($value);

        }
//print_r($updateData);
        $this->db->update_batch('cberp_currencies', $updateData, 'id');


    }

    public function subs()
    {
        $last = 1;
        $last_id = 1;

        //$this->db->select('method AS rec_due');
        // $this->db->from('univarsal_api');
        //  $query1 = $this->db->get();
        // $this->db->where('id', 55);
        //  $result = $query1->row_array();
        //  $config = $result['rec_due'];

        $config = 0;

		$this->db->select('id,tid');
        $this->db->from('cberp_invoices');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query_id = $this->db->get();
		$result_id = $query_id->row_array();
		$last_id = $result_id['id'];

        $this->db->select('id,tid');
        $this->db->where('i_class >', 1);
        $this->db->from('cberp_invoices');

        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        $result_t = $query->row_array();
        $last = $result_t['tid'];


        if ($config == 0) {
            $duedate = date('Y-m-d');

            $this->db->from('cberp_invoices');
            $this->db->where('DATE(invoiceduedate)<=', $duedate);
            $this->db->where('i_class', 2);
            $query = $this->db->get();
            $result = $query->result_array();
            $invoice_list = array();
            $old_invoice_list = array();
            $inv_index = 0;
            foreach ($result as $row) {

                $last++;
                $last_id++;


                $ndate = date("Y-m-d", strtotime($row['invoiceduedate'] . " +" . $row['r_time'] . 's'));

                $data = array('id' => $last_id, 'tid' => $last, 'invoicedate' => $row['invoiceduedate'], 'invoiceduedate' => $ndate, 'subtotal' => $row['subtotal'], 'shipping' => $row['shipping'], 'discount' => $row['discount'], 'tax' => $row['tax'], 'total' => $row['total'], 'notes' => $row['notes'], 'csd' => $row['csd'], 'eid' => $row['eid'], 'items' => $row['items'], 'taxstatus' => $row['taxstatus'], 'discstatus' => $row['discstatus'], 'format_discount' => $row['format_discount'], 'refer' => $row['refer'], 'term' => $row['term'], 'multi' => $row['multi'], 'i_class' => 2, 'loc' => $row['loc'], 'r_time' => $row['r_time']);

                $data2 = array(
                    'id' => $row['id'],
                    'i_class' => '3');
                $invoice_list[$inv_index] = $data;
                $old_invoice_list[$inv_index] = $data2;
                $inv_index++;
                $this->db->from('cberp_invoice_items');
                $this->db->where('tid', $row['id']);
                $query = $this->db->get();
                $result_p = $query->result_array();
                $productlist_p = array();
                $prodindex_p = 0;
                foreach ($result_p as $rowp) {


                    $data_p = array(
                        'tid' => $last_id,
                        'pid' => $rowp['pid'],
                        'product' => $rowp['product'],
                        'qty' => $rowp['qty'],
                        'price' => $rowp['price'],
                        'tax' => $rowp['tax'],
                        'discount' => $rowp['discount'],
                        'subtotal' => $rowp['subtotal'],
                        'totaltax' => $rowp['totaltax'],
                        'totaldiscount' => $rowp['totaldiscount'],
                        'product_des' => $rowp['product_des'],
                        'unit' => $rowp['unit']
                    );

                    $productlist_p[$prodindex_p] = $data_p;
                    $prodindex_p++;
                    $amt = $rowp['qty'];
                    if ($rowp['pid'] > 0) {
                        $this->db->set('qty', "qty-$amt", FALSE);
                        $this->db->where('pid', $rowp['pid']);
                        $this->db->update('cberp_products');
                    }
                    //    $itc += $amt;


                }
                $this->db->insert_batch('cberp_invoice_items', $productlist_p);

                //profit calculation
                $t_profit = 0;
                $this->db->select('cberp_invoice_items.pid, cberp_invoice_items.price, cberp_invoice_items.qty, cberp_products.product_cost');
                $this->db->from('cberp_invoice_items');
                $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
                $this->db->where('cberp_invoice_items.tid', $last_id);
                $query = $this->db->get();
                $pids = $query->result_array();
                foreach ($pids as $profit) {
                    $t_cost = $profit['product_cost'] * $profit['qty'];
                    $s_cost = $profit['price'] * $profit['qty'];

                    $t_profit += $s_cost - $t_cost;
                }
                $data = array('type' => 9, 'rid' => $last_id, 'col1' => $t_profit, 'd_date' => date('Y-m-d'));

                $this->db->insert('cberp_metadata', $data);

            }

            if ($result) {

                $this->db->insert_batch('cberp_invoices', $invoice_list);
                if ($this->db->update_batch('cberp_invoices', $old_invoice_list, 'id')) {
                    return true;

                } else {
                    return false;

                }
            }

        }

    }

    public function stock()
    {

        $query = $this->db->query("SELECT product_name,product_price,qty,unit FROM cberp_products WHERE qty<=alert ORDER BY product_name");
        $result = $query->result_array();
        $html_table = '<h2>Product Stock Alert</h2><p>Dear Business Owner, You have some products running low/out of stock.</p><table><tr><th>Product Name</th><th>Qty</th><th>Price</th></tr>';
        foreach ($result as $row) {

            $html_table .= '<tr><td>' . $row['product_name'] . '</td><td>' . amountFormat_general($row['qty']) . ' ' . $row['unit'] . '</td><td>' . amountExchange($row['product_price'], $invoice['multi'], $this->aauth->get_user()->loc). '</td></tr>';

        }
        $html_table .= '</table>';

        return $html_table;


    }

    public function expiry()
    {

        $query = $this->db->query("SELECT product_name,product_price,qty,unit,expiry FROM cberp_products WHERE DATE(expiry)<='" . date('Y-m-d') . "' ORDER BY product_name");
        $result = $query->result_array();
        $html_table = '<h2>Product Expiry Alert</h2><p>Dear Business Owner, You have some products near to expire.</p><table><tr><th>Product Name</th><th>Qty</th><th>Price</th></tr>';
        foreach ($result as $row) {

            $html_table .= '<tr><td>' . $row['product_name'] . '</td><td>' . $row['qty'] . ' ' . $row['unit'] . '</td><td>' . $row['product_price'] . '</td></tr>';

        }
        $html_table .= '</table>';

        return $html_table;


    }

        public function customer_mail($limit=100,$start=1)
    {

        $this->db->select('id,name,email,phone');
        $this->db->from('cberp_customers');
        if($limit && $start) $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result_array();


    }


}
