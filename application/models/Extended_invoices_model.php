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

class Extended_invoices_model extends CI_Model
{
    var $table = 'cberp_invoice_items';
    var $column_order = array(null, 'cberp_invoices.tid', 'cberp_customers.name', 'cberp_invoices.invoicedate','cberp_invoice_items.product','cberp_invoice_items.code', 'cberp_invoice_items.subtotal', 'cberp_invoice_items.qty', 'cberp_invoice_items.discount','cberp_invoice_items.tax');
    var $column_search = array('cberp_invoices.tid', 'cberp_customers.name', 'cberp_invoices.invoicedate', 'cberp_invoice_items.subtotal','cberp_invoice_items.qty','cberp_invoice_items.tax','cberp_invoice_items.product','cberp_invoice_items.code');
    var $order = array('cberp_invoices.tid' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }






    private function _get_datatables_query($opt = '')
    {
        $this->db->select('cberp_invoices.id,cberp_invoices.tid,cberp_invoices.eid,cberp_invoices.invoicedate,cberp_invoices.invoiceduedate,cberp_invoices.invoice_number,cberp_invoice_items.subtotal,cberp_invoice_items.qty,cberp_invoice_items.product,cberp_invoice_items.code,cberp_invoice_items.discount,cberp_invoice_items.tax,cberp_customers.name');
        $this->db->from($this->table);
        //$this->db->where('cberp_invoices.i_class', 1);
          $this->db->where('cberp_invoices.status !=', 'canceled');
        if ($opt) {
            $this->db->where('cberp_invoices.eid', $opt);
        }
        if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
        {
            $this->db->where('DATE(cberp_invoices.invoicedate) >=', datefordatabase($this->input->post('start_date')));
            $this->db->where('DATE(cberp_invoices.invoicedate) <=', datefordatabase($this->input->post('end_date')));
        }

        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // }
        // elseif(!BDATA) { $this->db->where('cberp_invoices.loc', 0); }

        $this->db->join('cberp_invoices', 'cberp_invoices.id=cberp_invoice_items.tid', 'left');
        $this->db->join('cberp_customers', 'cberp_invoices.csd=cberp_customers.customer_id', 'left');

        $i = 0;
        $search_value = $this->input->post('search')['value'];
        $search_value_clean = str_replace(',', '', $search_value);
        foreach ($this->column_search as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $search_value_clean);
                } else {
                    $this->db->or_like($item, $search_value_clean);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get();

      //  $this->db->join('cberp_invoices', 'cberp_invoices.id=cberp_invoice_items.tid', 'left');
        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($opt) {
            $this->db->where('eid', $opt);

        }

        //       $this->db->join('cberp_invoices', 'cberp_invoices.id=cberp_invoice_items.tid', 'left');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('cberp_invoice_items.id');
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }



}
