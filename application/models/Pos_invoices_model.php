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

class Pos_invoices_model extends CI_Model
{
    var $table = 'cberp_invoices';
    var $column_order = array(null, 'cberp_invoices.tid', 'cberp_customers.name', 'cberp_invoices.invoicedate', 'cberp_invoices.total', 'cberp_invoices.status', null);
    var $column_search = array('cberp_invoices.tid', 'cberp_customers.name', 'cberp_invoices.invoicedate', 'cberp_invoices.total','cberp_invoices.status');
    var $order = array('cberp_invoices.tid' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    // public function lastinvoice()
    // {
    //     $this->db->select('tid');
    //     $this->db->from($this->table);
    //     $this->db->order_by('id', 'DESC');
    //     $this->db->limit(1);
    //     //erp2024 removed condition 17-06-2024
    //     // $this->db->where('i_class', 1);
    //     //erp2024 removed condition 17-06-2024
    //     $query = $this->db->get();
    //     if ($query->num_rows() > 0) {
    //         return $query->row()->tid;
    //     } else {
    //         return 1000;
    //     }
    // }

	public function lastinvoice()
    {
        $this->configurations = $this->session->userdata('configurations');
        $prefix = $this->configurations['invoiceprefix']; 
        $this->db->select('invoice_number');
        $this->db->from($this->table);
        $this->db->order_by('invoice_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        // die($this->db->last_query());
        if ($query->num_rows() > 0) {
            $last_invoice_number = $query->row()->invoice_number;
            $parts = explode('/', $last_invoice_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $prefix.$next_number;
        } else {
            return $prefix.'1001';
        }
    }

    public function sales_products($id,$productIds="")
    {

        $this->db->select('cberp_sales_orders_items.*,cberp_sales_orders.subtotal as mainsub,cberp_sales_orders.discount as maindiscount,cberp_sales_orders.total as maintotal,cberp_sales_orders.tax as maintax');
        $this->db->from('cberp_sales_orders_items');
        $this->db->join('cberp_sales_orders', 'cberp_sales_orders.id = cberp_sales_orders_items.tid', 'left');
        $this->db->where('cberp_sales_orders.tid', $id);
        if(!empty($productIds)){
            $this->db->where_in('pid', $productIds);
        }        
        $query = $this->db->get();
        return $query->result_array();
    }
    public function salesorder_cust($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_customers');
        $this->db->where('cberp_customers.customer_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function invoice_details($id, $eid = '',$loc=null)
    {

        $this->db->select('cberp_invoices.*, SUM(cberp_invoices.shipping + cberp_invoices.shipping_tax) AS shipping,cberp_customers.*,cberp_invoices.loc as loc,cberp_invoices.invoice_number AS iid,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms');
        $this->db->from($this->table);
        $this->db->where('cberp_invoices.invoice_number', $id);
        if ($eid) {
            $this->db->where('cberp_invoices.employee_id', $eid);
        }
        // if (@$this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA and !$loc) { $this->db->where('cberp_invoices.loc', 0); }
        // if($loc){ $this->db->where('cberp_invoices.loc', $loc); }

        $this->db->join('cberp_customers', 'cberp_invoices.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_invoices.payment_terms', 'left');
        $query = $this->db->get();
        return $query->row_array();

    }

    // public function invoice_products($id)
    // {

    //     $this->db->select('cberp_invoice_items.*,cberp_products.onhand_quantity AS totalQty, cberp_products.product_name AS product_name, cberp_products.product_code');
    //     $this->db->from('cberp_invoice_items');
    //     $this->db->join('cberp_products', 'cberp_products.product_code = cberp_invoice_items.product_code', 'left');
    //     $this->db->where('invoice_number', $id);
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }

	public function invoice_products($id)
	{
		$this->db->select(
			'cberp_invoice_items.*, 
			cberp_products.onhand_quantity AS totalQty, 
			cberp_product_description.product_name, 
			cberp_products.product_code'
		);
		$this->db->from('cberp_invoice_items');
		$this->db->join('cberp_products', 'cberp_products.product_code = cberp_invoice_items.product_code', 'left');
		$this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_invoice_items.product_code', 'left');
		$this->db->where('cberp_invoice_items.invoice_number', $id);

		$query = $this->db->get();
		return $query->result_array();
	}


    public function currencies()
    {

        $this->db->select('*');
        $this->db->from('cberp_currencies');

        $query = $this->db->get();
        return $query->result_array();

    }

    public function currency_d($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_currencies');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function warehouses()
    {
        $this->db->select('*');
        $this->db->from('cberp_store');
    //    if ($this->aauth->get_user()->loc) {
    //         $this->db->where('loc', $this->aauth->get_user()->loc);
    //       if(BDATA)  $this->db->or_where('loc', 0);
    //     }  elseif(!BDATA) { $this->db->where('loc', 0); }

        $query = $this->db->get();

        return $query->result_array();

    }

    public function invoice_transactions($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where('tid', $id);
        $this->db->where('ext', 0);
        $query = $this->db->get();
        return $query->result_array();

    }


            public function items_with_product($id)
    {

        $this->db->select('cberp_invoice_items.*,cberp_products.onhand_quantity AS alert');
        $this->db->from('cberp_invoice_items');
        $this->db->where('tid', $id);
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
        $query = $this->db->get();
        return $query->result_array();

    }


    public function invoice_delete($id, $eid = '')
    {

        $this->db->trans_start();

        $this->db->select('status');
        $this->db->from('cberp_invoices');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->row_array();

          if ($this->aauth->get_user()->loc) {
            if ($eid) {

                $res = $this->db->delete('cberp_invoices', array('id' => $id, 'eid' => $eid, 'loc' => $this->aauth->get_user()->loc));


            } else {
                $res = $this->db->delete('cberp_invoices', array('id' => $id, 'loc' => $this->aauth->get_user()->loc));
            }
        }

        else {
            if (BDATA) {
                if ($eid) {

                    $res = $this->db->delete('cberp_invoices', array('id' => $id, 'eid' => $eid));


                } else {
                    $res = $this->db->delete('cberp_invoices', array('id' => $id));
                }
            } else {


                if ($eid) {

                    $res = $this->db->delete('cberp_invoices', array('id' => $id, 'eid' => $eid, 'loc' => 0));


                } else {
                    $res = $this->db->delete('cberp_invoices', array('id' => $id, 'loc' => 0));
                }
            }
        }
        $affect = $this->db->affected_rows();
        if ($res) {
            if ($result['status'] != 'canceled') {
                $this->db->select('pid,qty');
                $this->db->from('cberp_invoice_items');
                $this->db->where('tid', $id);
                $query = $this->db->get();
                $prevresult = $query->result_array();
                foreach ($prevresult as $prd) {
                    $amt = $prd['qty'];
                    $this->db->set('qty', "qty+$amt", FALSE);
                    $this->db->where('pid', $prd['pid']);
                    $this->db->update('cberp_products');
                }
            }
            if ($affect) $this->db->delete('cberp_invoice_items', array('tid' => $id));
            $data = array('type' => 9, 'rid' => $id);
            $this->db->delete('cberp_metadata', $data);
            if ($this->db->trans_complete()) {
                return true;
            } else {
                return false;
            }
        }
    }


    private function _get_datatables_query($opt = '')
    {
        $this->db->select('cberp_invoices.id,cberp_invoices.tid,cberp_invoices.invoicedate,cberp_invoices.invoiceduedate,cberp_invoices.total,cberp_invoices.status,cberp_customers.name');
        $this->db->from($this->table);
        $this->db->where('cberp_invoices.i_class', 1);
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
        //   elseif(!BDATA) { $this->db->where('cberp_invoices.loc', 0); }
        $this->db->join('cberp_customers', 'cberp_invoices.csd=cberp_customers.customer_id', 'left');

        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
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
        $this->db->where('cberp_invoices.i_class', 1);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // }
        //   elseif(!BDATA) { $this->db->where('cberp_invoices.loc', 0); }
        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($opt) {
            $this->db->where('eid', $opt);

        }
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_invoices.loc', 0); }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('cberp_invoices.id');
        $this->db->from($this->table);
        $this->db->where('cberp_invoices.i_class', 1);
        if ($opt) {
            $this->db->where('cberp_invoices.eid', $opt);
        }
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_invoices.loc', 0); }
        return $this->db->count_all_results();
    }


    public function billingterms()
    {
        $this->db->select('id,title');
        $this->db->from('cberp_terms');
        $this->db->where('type', 1);
        $this->db->or_where('type', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function employee($id)
    {
        $this->db->select('cberp_employees.name,cberp_employees.sign,cberp_users.roleid');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function meta_insert($id, $type, $meta_data)
    {

        $data = array('type' => $type, 'rid' => $id, 'col1' => $meta_data);
        if ($id) {
            return $this->db->insert('cberp_metadata', $data);
        } else {
            return 0;
        }
    }

    public function attach($id)
    {
        $this->db->select('cberp_metadata.*');
        $this->db->from('cberp_metadata');
        $this->db->where('cberp_metadata.type', 1);
        $this->db->where('cberp_metadata.rid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function meta_delete($id, $type, $name)
    {
        if (@unlink(FCPATH . 'userfiles/attach/' . $name)) {
            return $this->db->delete('cberp_metadata', array('rid' => $id, 'type' => $type, 'col1' => $name));
        }
    }

    public function gateway_list($enable = '')
    {

        $this->db->from('cberp_gateways');
        if ($enable == 'Yes') {
            $this->db->where('enable', 'Yes');
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function drafts()
    {


        $this->db->select('cberp_draft.id,cberp_draft.tid,cberp_draft.invoicedate');
        $this->db->from('cberp_draft');
    //    $this->db->where('cberp_draft.loc', $this->aauth->get_user()->loc);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(12);
        $query = $this->db->get();
        return $query->result_array();

    }

    public function draft_products($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_draft_items');
        $this->db->where('tid', $id);
        $query = $this->db->get();
        return $query->result_array();

    }

    public function draft_details($id, $eid = '')
    {
		

        $this->db->select('
		cberp_draft.*,
		SUM(cberp_draft.shipping + cberp_draft.ship_tax) AS shipping,
		cberp_customers.*,
		cberp_customers.customer_id AS cid,
		cberp_draft.id AS iid,
		cberp_terms.id AS termid,
		cberp_terms.title AS termtit,
		cberp_terms.terms AS terms');
        $this->db->from('cberp_draft');
        $this->db->where('cberp_draft.id', $id);
        if ($eid) {
            $this->db->where('cberp_draft.eid', $eid);
        }
        $this->db->join('cberp_customers', 'cberp_draft.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_draft.term', 'left');
        $query = $this->db->get();
		//echo $this->db->last_query();
		//exit();
		// echo "<pre>";
        // $result =  $query->row_array();
		// print_r($result);
		// echo "</pre>";
		
        return $query->row_array();

    }

        public function accountslist()
    {
        $this->db->select('*');
        $this->db->from('cberp_accounts');

        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //    if(BDATA) $this->db->or_where('loc', 0);
        // }else{
        //      if(!BDATA) $this->db->where('loc', 0);
        // }

        $query = $this->db->get();
        return $query->result_array();
    }
    
}
