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

class Invoices_model extends CI_Model
{
    
    var $table = 'cberp_invoices';
    var $column_order = array(null, 'cberp_invoices.invoice_number','cberp_invoices.invoice_type', 'cberp_customers.name', 'cberp_invoices.invoice_date', 'cberp_invoices.due_date','cberp_invoices.grand_total', 'cberp_invoices.status', null);
    var $column_search = array('cberp_invoices.invoice_number','cberp_invoices.invoice_type', 'cberp_customers.name', 'cberp_invoices.invoice_date', 'cberp_invoices.due_date', 'cberp_invoices.grand_total','cberp_invoices.status');
    var $order = array('cberp_invoices.invoice_date' => 'asc');

    public function __construct()
    {
        parent::__construct();
    }

    public function lastinvoice()
    {
        $this->configurations = $this->session->userdata('configurations');
        $prefix = $this->configurations['invoiceprefix']; 
        $this->db->select('invoice_number');
        $this->db->from('cberp_invoices');
        $this->db->order_by('invoice_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
       
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

    
    public function lastenquiry()
    {
        $this->db->select('lead_number');
        $this->db->from('cberp_customer_leads');
        $this->db->where("lead_number IS NOT NULL");
        $this->db->order_by('lead_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_lead_number = $query->row()->lead_number;
            $parts = explode('/', $last_lead_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $next_number;
        } else {
            return '1001';
        }
    }



    public function invoice_details($invoice_number, $employee_id = '',$p=true)
    {
        $this->db->select('cberp_invoices.*,cberp_invoices.status as invoicestatus,cberp_invoices.transaction_number as inv_transaction_number,cberp_invoices.shipping, SUM(cberp_invoices.shipping + cberp_invoices.shipping_tax) AS totalshipping,cberp_customers.*,cberp_invoices.loc as loc,cberp_invoices.invoice_number AS iid,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms,cberp_customers.avalable_credit_limit,cberp_invoices.status as paymentstatus,cberp_store.store_name as warehousename');
        $this->db->from($this->table);
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);
        if ($employee_id) {
            $this->db->where('cberp_invoices.employee_id', $employee_id);
        }
        $this->db->join('cberp_customers', 'cberp_invoices.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_invoices.payment_terms', 'left');
        $this->db->join('cberp_store', 'cberp_store.store_id = cberp_invoices.store_id', 'left');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    public function delnote_details($id)
    {
        $this->db->select('cberp_delivery_notes.salesorder_id,cberp_delivery_notes.salesorder_number,cberp_delivery_notes.delevery_note_id,cberp_delivery_notes.delnote_number,cberp_sales_orders.customer_reference_number,cberp_sales_orders.invoicedate as refdate');
        $this->db->from('cberp_delivery_notes');
        $this->db->join('cberp_sales_orders', 'cberp_sales_orders.id = cberp_delivery_notes.salesorder_id');
        $this->db->where('cberp_delivery_notes.delevery_note_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function invoice_products($invoice_number)
    {
        
        $this->db->select('cberp_invoice_items.*,cberp_invoice_items.price AS product_price, cberp_invoice_items.quantity AS product_qty, cberp_product_description.product_name,cberp_products.product_code,cberp_products.product_cost,cberp_products.onhand_quantity as onhandqty,cberp_products.onhand_quantity as totalQty,cberp_products.unit,cberp_product_description.arabic_name,cberp_product_pricing.minimum_price as minprice,cberp_products.maximum_discount_rate as maximumdiscount,cberp_products.income_account_number,cberp_products.expense_account_number,cberp_invoice_items.discount_type AS delnote_discounttype, cberp_invoice_items.total_discount AS deliverytotaldiscount, cberp_invoice_items.discount AS product_discount, cberp_invoice_items.subtotal AS deliverysubtotal, cberp_invoice_items.total_tax AS deliverytaxtotal,cberp_invoice_items.product_code AS product_id');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_invoice_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code');
        $this->db->where('cberp_invoice_items.invoice_number', $invoice_number);
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




    public function currencies()
    {

        $this->db->select('*');
        $this->db->from('cberp_currencies');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function currency_d($id, $loc = 0)
    {
        if ($loc) {
            $query = $this->db->query("SELECT cur FROM cberp_locations WHERE id='$loc' LIMIT 1");
            $row = $query->row_array();
            $id = $row['cur'];
        }
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
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // if(BDATA)  $this->db->or_where('loc', 0);
        // }  elseif(!BDATA) { $this->db->where('loc', 0); }

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

    public function invoice_delete($id, $eid = '')
    {
        $this->db->trans_start();
        $this->db->select('tid,total,status');
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

                        $alert= $this->custom->api_config(66);
            if ($alert['method'] == 1) {
                 $this->load->model('communication_model');
                 $subject= $result['tid'].' '. $this->lang->line('DELETED');
                 $body=$subject.'<br> '. $this->lang->line('Amount').' '. $result['total'].'<br> '. $this->lang->line('Employee').' '. $this->aauth->get_user()->username.'<br> ID# '. $result['tid'];
               $out= $this->communication_model->send_corn_email($alert['url'], $alert['url'], $subject, $body, false, '');
            }

            if ($this->db->trans_complete()) {
                return true;
            } else {
                return false;
            }
        }

    }


    private function _get_datatables_query($opt = '')
    {

        $this->db->select('cberp_invoices.invoice_number,cberp_invoices.invoice_date,cberp_invoices.due_date,cberp_invoices.grand_total,cberp_invoices.status,cberp_customers.name,cberp_invoices.customer_id,cberp_invoices.invoice_type,cberp_invoices.paid_amount');
        $this->db->from($this->table);
        $this->db->join('cberp_customers', 'cberp_invoices.customer_id=cberp_customers.customer_id', 'left');

        if ($this->input->post('start_date') && $this->input->post('end_date')) {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(cberp_invoices.invoice_date) BETWEEN '$start_date' AND '$end_date'");
        }

        $i = 0;

        foreach ($this->column_search as $item) {
            if ($this->input->post('search')['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order']) && !empty($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
           
        } else {
            // Default ordering
            $this->db->order_by('cberp_invoices.invoice_number', 'DESC');
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
        // if ($opt) {
        //     $this->db->where('eid', $opt);
        // }
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
        // $this->db->where('cberp_invoices.i_class', 0);
        // if ($opt) {
        //     $this->db->where('cberp_invoices.eid', $opt);

        // }
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

    // public function get_enquiry_count(){
    //     $today = date('Y-m-d');
    //     $query = $this->db->query("
    //     SELECT 
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
    //         SUM(CASE WHEN DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_count,

    //         SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_assigned_count,
    //         SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_assigned_count,
    //         SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_assigned_count,
    //         SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_assigned_count,
    //         SUM(CASE WHEN enquiry_status = 'Completed' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_assigned_count,

    //         SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_open_count,
    //         SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_open_count,
    //         SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_open_count,
    //         SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_open_count,
    //         SUM(CASE WHEN enquiry_status = 'Open' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_open_count,

    //         SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_closed_count,
    //         SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_closed_count,
    //         SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_closed_count,
    //         SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_closed_count,
    //         SUM(CASE WHEN enquiry_status = 'Closed' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_closed_count,

    //         SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
    //         SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
    //         SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
    //         SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
    //         SUM(CASE WHEN enquiry_status = 'Draft' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN total ELSE 0 END) AS yearly_total,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN total ELSE 0 END) AS quarterly_total,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN total ELSE 0 END) AS monthly_total,
    //         SUM(CASE WHEN created_date BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN total ELSE 0 END) AS weekly_total,
    //         SUM(CASE WHEN DATE(created_date) = '$today' THEN total ELSE 0 END) AS daily_total
    //     FROM customer_leads WHERE lead_number IS NOT NULL
    // ");


    //     return $query->row();
    // }

    public function get_enquiry_count($ranges)
    {
        $today = date('Y-m-d');
        $startMonth    = $ranges['month'];
        $startWeek     = $ranges['week'];
        $startQuarter  = $ranges['quarter'];
        $startYear     = $ranges['year'];
        $query = $this->db->query("
            SELECT 
                -- Total enquiry counts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- 'Completed' status
                SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_assigned_count,
                SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_assigned_count,
                SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_assigned_count,
                SUM(CASE WHEN enquiry_status = 'Completed' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_assigned_count,
                SUM(CASE WHEN enquiry_status = 'Completed' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_assigned_count,

                -- 'Open' status
                SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_open_count,
                SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_open_count,
                SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_open_count,
                SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_open_count,
                SUM(CASE WHEN enquiry_status = 'Open' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_open_count,

                -- 'Closed' status
                SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_closed_count,
                SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_closed_count,
                SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_closed_count,
                SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_closed_count,
                SUM(CASE WHEN enquiry_status = 'Closed' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_closed_count,

                -- 'Draft' status
                SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN enquiry_status = 'Draft' AND created_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN enquiry_status = 'Draft' AND DATE(created_date) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- Total amounts
                SUM(CASE WHEN created_date BETWEEN '$startYear' AND '$today' THEN total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN created_date BETWEEN '$startQuarter' AND '$today' THEN total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN created_date BETWEEN '$startMonth' AND '$today' THEN total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN created_date BETWEEN '$startWeek' AND '$today' THEN total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(created_date) = '$today' THEN total ELSE 0 END) AS daily_total

            FROM cberp_customer_leads
            WHERE lead_number IS NOT NULL
        ");

        return $query->row();
    }

    public function get_dynamic_count($table,$datefield,$amountfield,$condition=""){
        $query = $this->db->query("SELECT 
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN $amountfield ELSE 0 END) AS yearly_total,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 3 MONTH AND CURDATE() THEN $amountfield ELSE 0 END) AS quarterly_total,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN $amountfield ELSE 0 END) AS monthly_total,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN $amountfield ELSE 0 END) AS weekly_total,
            SUM(CASE WHEN DATE($datefield) = CURDATE() THEN $amountfield ELSE 0 END) AS daily_total,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE NULL END) AS yearly_count,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 3 MONTH AND CURDATE() THEN 1 ELSE NULL END) AS quarterly_count,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE NULL END) AS monthly_count,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE NULL END) AS weekly_count,
            COUNT(CASE WHEN DATE($datefield) = CURDATE() THEN 1 ELSE NULL END) AS daily_count
        FROM $table $condition");
        return $query->row();
    }

    public function customer_enqid($enqid)
    {
        $this->db->select('lead_id');
        $this->db->from('customer_enquiry');        
        $this->db->where('general_enqid', $enqid);
        $query = $this->db->get();
        $lead_id = $query->row()->lead_id;
        return $lead_id;
    }
    public function customer_leadid_by_id($enqid)
    {
        $this->db->select('lead_id');
        $this->db->from('cberp_customer_leads');        
        $this->db->where('lead_id', $enqid);
        $query = $this->db->get();
        $lead_id = $query->row()->lead_id;
        return $lead_id;
    }

    public function create_customer($customer_data){
        $this->db->select('customer_id');
        $this->db->from('cberp_customers');        
        $this->db->where('email', $customer_data['email']);
        $query = $this->db->get();
        // die($this->db->last_query());
        $customer_id = $query->row()->id;
        if(!empty($customer_id )){
            return $customer_id;
        }
        else{
            $this->db->insert('cberp_customers', $customer_data);
            $customer_id = $this->db->insert_id();
            $temp_password = 12345;
            $password = password_hash($temp_password, PASSWORD_DEFAULT);
            $userstatus = "active";
            $user_type = "Member";
            $lang = "english";
            $is_deleted = 0;
            $code = rand(100000, 999999);
            $newdata = array(
                'user_id' => $customer_id,
                'cid' => $customer_id,
                'status' => "active",
                'user_type' => "Member",
                'lang' => "english",
                'is_deleted' => 0,
                'code' => $code,
                'name' => $customer_data['name'],
                'email' => $customer_data['email'],
                'password' => $password,
                
            );
            $this->db->insert('users', $newdata);
            return $customer_id;
        }      

    }

    public function dew_invoices_by_customerid($customerid)
    {
        $this->db->select('cberp_invoices.invoice_number AS invoiceid,cberp_invoices.invoice_number AS invoiceumber,cberp_invoices.invoice_number, cberp_invoices.invoice_date, cberp_invoices.due_date, cberp_invoices.subtotal, cberp_invoices.grand_total as total, cberp_invoices.status,cberp_invoices.paid_amount as payment_recieved_amount');
        $this->db->from('cberp_invoices');
        
        $this->db->where('cberp_invoices.customer_id', $customerid); 
        $this->db->group_start(); // Start grouping conditions
        $this->db->where('cberp_invoices.status', 'due');
        $this->db->or_where('cberp_invoices.status', 'partial');
        $this->db->group_end();
        $query = $this->db->get();
        //  die($this->db->last_query());
        $result = $query->result_array(); 
       
        return $result;
    }

    public function payment_method_details($invoice_number)
    {
        $this->db->select('*');
        $this->db->from('cberp_payments');
        $this->db->where('cberp_payments.invoice_number', $invoice_number); 
        $query = $this->db->get();         
        // die($this->db->last_query());
        return  $query->row_array();
    }
    public function payment_pending_invoice($customer_id)
    {
        $this->db->select('cberp_invoices.invoice_number');
        $this->db->from('cberp_invoices');
        $this->db->where('cberp_invoices.customer_id', $customer_id);
        $this->db->or_where('cberp_invoices.status', 'due');
        $this->db->or_where('cberp_invoices.status', 'partial');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function transactions_ai_details($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_payments');
        $this->db->where('cberp_payments.id', $id); 
        $query = $this->db->get();         
        return  $query->row_array();
    }

    // #erp2024 29-09-2024
    public function sales_reference_bydelnoteid($id)
    {
        $this->db->select('cberp_sales_orders.customer_reference_number,cberp_sales_orders.salesorder_date as refdate,cberp_delivery_notes.delivery_note_number,cberp_delivery_notes.created_date');
        $this->db->from('cberp_sales_orders');  
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.salesorder_number = cberp_sales_orders.salesorder_number');
        $this->db->where('cberp_delivery_notes.delivery_note_number', $id);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    // erp2024 23-10-2024 starts
    public function customerByInvoiceid($invoice_number)
    {
        $this->db->select('cberp_customers.*,cberp_country.name as countryname');
        $this->db->from('cberp_customers');        
        $this->db->join('cberp_invoices', 'cberp_invoices.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_customers.country');
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function invoice_already_exist_or_not($invoice_number){
        $this->db->select('invoice_number');
        $this->db->from('cberp_invoices');
        $this->db->where("cberp_invoices.invoice_number",$invoice_number);
        $query2 = $this->db->get();
        $result2 = $query2->row_array();
        if ($result2) {
            return $result2['invoice_number'];
        } else {
            return 0;
        }
    }

    public function check_product_existornot($invoice_number,$product_code)
    {
        $this->db->select('product_code');
        $this->db->from('cberp_invoice_items');
        $this->db->where('invoice_number', $invoice_number);
        $this->db->where('product_code', $product_code);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }

    }
    // erp2024 23-10-2024 ends

    //erp2024  04-11-2024 starts
    public function invoice_by_id($id)
    {
        //delevery_note_id
        $this->db->select('cberp_invoices.*,cberp_invoices.invoice_number as invoiceid,cberp_invoices.store_id as warehouseid,cberp_invoices.delevery_note_number as delevery_note_id,cberp_invoices.delevery_note_number,cberp_invoices.reference as delnoterefer,cberp_invoices.invoice_date as delnoteinvoicedate, cberp_customers.*,cberp_invoices.status as notestatus');
        $this->db->from('cberp_invoices');        
        $this->db->join('cberp_customers', 'cberp_invoices.customer_id = cberp_customers.customer_id', 'left');
        $this->db->where('cberp_invoices.invoice_number', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function invoice_products_for_return($tid)
    {
        $this->db->select('cberp_invoice_items.*,cberp_invoice_items.product_code AS product_id, cberp_products.product_code AS prdcode, cberp_product_description.product_name AS prdname,cberp_products.unit');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_invoice_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_products.product_code = cberp_product_description.product_code');
        $this->db->where('cberp_invoice_items.invoice_number', $tid);
        // $this->db->group_by('cberp_invoice_items.tid');
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->result_array();

    }
    public function product_qty_update_to_invoice_items_table($invoice_number, $product_code, $return_qty, $damaged_qty,$returnamt) {
        $this->db->select('return_quantity,damaged_quantity');
        $this->db->from('cberp_invoice_items');
        $this->db->where('cberp_invoice_items.invoice_number', $invoice_number);
        $this->db->where('cberp_invoice_items.product_code', $product_code);
        $query = $this->db->get();    
        // echo $this->db->last_query(); die();
        $result = $query->row_array();
        if ($result) { 
            $current_ret_qty = intval($result['return_quantity']);
            $current_damage_qty = intval($result['damaged_qty']);
        } else {
            $current_ret_qty = 0; 
            $current_damage_qty = 0;
        }
        
        $returned_qty = intval($current_ret_qty) + intval($return_qty);
        $returned_damage_qty = intval($current_damage_qty) + intval($damaged_qty);
        
        $this->db->set('approved_return_amount', $returnamt);
        $this->db->set('return_quantity', $returned_qty);
        $this->db->set('damaged_quantity', $returned_damage_qty);
        $this->db->where('cberp_invoice_items.invoice_number', $invoice_number);
        $this->db->where('cberp_invoice_items.product_code', $product_code);
        $this->db->update('cberp_invoice_items');
        // die($this->db->last_query());

    }
    

    public function reset_credit_accounts($transaction_number)
    {
        // Fetch the data
        $this->db->select('cberp_transactions.acid, cberp_transactions.account, cberp_transactions.credit AS creditamount');
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_transactions.transaction_number', $transaction_number);
        $this->db->where('cberp_transactions.credit >', 0);
        $query = $this->db->get();
        $data = $query->result_array();  
        
       
        // Group the data by 'acid' and sum the 'creditamount'
        $groupedData = [];
        foreach ($data as $row) {
            if (isset($groupedData[$row['acid']])) {
                $groupedData[$row['acid']]['creditamount'] += $row['creditamount']; // Sum the creditamount
            } else {
                $groupedData[$row['acid']] = [
                    'acid' => $row['acid'],
                    'creditamount' => $row['creditamount']
                ];
            }
        }
    
        $batchSize = 500; // Define the batch size
        
        // Process the grouped data in chunks
        for ($i = 0; $i < count($groupedData); $i += $batchSize) {
            $chunk = array_slice($groupedData, $i, $batchSize);
            $sql = "UPDATE cberp_accounts SET lastbal = CASE";            
            // Iterate over the grouped data
            foreach ($chunk as $row) {
                $sql .= " WHEN acn = '{$row['acid']}' THEN lastbal + {$row['creditamount']}";
            }
    
            // Add the WHERE condition for the IN clause (with acid wrapped in single quotes)
            $sql .= " END WHERE acn IN ('" . implode("','", array_column($chunk, 'acid')) . "')";
            // Execute the batch update for the current chunk
            $this->db->query($sql);
           
        }
    }
    public function reset_debit_accounts($transaction_number)
    {
        // Fetch the data
        $this->db->select('cberp_transactions.acid, cberp_transactions.account, cberp_transactions.debit AS debitamount');
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_transactions.transaction_number', $transaction_number);
        $this->db->where('cberp_transactions.debit >', 0);
        $query = $this->db->get();
        $data = $query->result_array();  
        // Group the data by 'acid' and sum the 'debitamount'
        $groupedData = [];
        foreach ($data as $row) {
            if (isset($groupedData[$row['acid']])) {
                $groupedData[$row['acid']]['debitamount'] += $row['debitamount']; // Sum the debitamount
            } else {
                $groupedData[$row['acid']] = [
                    'acid' => $row['acid'],
                    'debitamount' => $row['debitamount']
                ];
            }
        }

        $batchSize = 500; // Define the batch size
        
        // Process the grouped data in chunks
        for ($i = 0; $i < count($groupedData); $i += $batchSize) {
            $chunk = array_slice($groupedData, $i, $batchSize);
            $sql = "UPDATE cberp_accounts SET lastbal = CASE";
            
            // Iterate over the grouped data
            foreach ($chunk as $row) {
                $sql .= " WHEN acn = '{$row['acid']}' THEN lastbal - {$row['debitamount']}";
            }

            // Add the WHERE condition for the IN clause (with acid wrapped in single quotes)
            $sql .= " END WHERE acn IN ('" . implode("','", array_column($chunk, 'acid')) . "')";
           
            // Execute the batch update for the current chunk
            $this->db->query($sql);
            // Optionally output the SQL query for debugging
           
        }
    }

    public function transaction_number_invoiceid($invoice_number)
    {
        $this->db->select('cberp_invoices.transaction_number');
        $this->db->from('cberp_invoices');
        $this->db->where('cberp_invoices.invoice_number',  $invoice_number);
        $query = $this->db->get();
        $data = $query->row_array();  
        return $data;
    }
    public function reset_transaction_amounts($transactionNumber)
    {
        $this->db->select('id, debit, credit, transaction_number');
        $this->db->where('transaction_number', $transactionNumber);
        $query = $this->db->get('cberp_transactions');
        $transactions = $query->result();
        if($transactions){
            $updateData = [];
            foreach ($transactions as $transaction) {
                $updateData[] = [
                    'id' => $transaction->id,
                    'debit' => $transaction->credit,
                    'credit' => $transaction->debit
                ];
            }
            $this->db->update_batch('cberp_transactions', $updateData, 'id');
        }
    }
    public function cancel_transactions($transactionNumber)
    {
       $this->db->delete('cberp_transactions', ['transaction_number'=> $transactionNumber]);
    }

    public function get_invoice_number($invoice_number)
    {
        $this->db->select('invoice_number');
        $this->db->from("cberp_invoices");
        $this->db->where('invoice_number', $invoice_number);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->invoice_number;
        }
        else{
            return $invoice_number;
        }
    }

    public function reset_invoice_payment_accounts($transaction_number)
    {
       
        // Fetch the data
        $this->db->select('cberp_transactions.acid, cberp_transactions.credit AS creditamount, cberp_transactions.debit AS debitamount');
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_transactions.transaction_number', $transaction_number);
        $query = $this->db->get();
        $data = $query->result_array();  
      
        if ($data) {
            foreach ($data as $row) {
                $debitamount = $row['debitamount'];
                $creditamount = $row['creditamount'];
                $acn = $row['acid'];
               
                if ($debitamount>0) {
                    $this->db->set('lastbal', "lastbal - $debitamount", FALSE);
                    $this->db->where('acn', $acn);
                    $this->db->update('cberp_accounts');

                    $this->db->set('trans_amount', "trans_amount - $debitamount", FALSE);
                    $this->db->where('from_trans_number', $transaction_number);
                    $this->db->update('cberp_bank_transactions');


                    // $this->db->update('cberp_transactions',['credit'=>$debitamount,'debit'=>$creditamount],['transaction_number'=>$transaction_number,'acid'=>$acn]);
                }
                else
                {
                    $this->db->set('lastbal', "lastbal + $creditamount", FALSE);
                    $this->db->where('acn', $acn);
                    $this->db->update('cberp_accounts');
                    // $this->db->update('cberp_transactions',['credit'=>$debitamount,'debit'=>$creditamount],['transaction_number'=>$transaction_number,'acid'=>$acn]);
                }
            }
            $this->db->delete('cberp_transactions',['transaction_number'=>$transaction_number]);
        }

        // Optionally, return the fetched data if needed for debugging or further processing
        // return $data;
    }
    public function check_invoice_ispaid($invoice_number)
    {


        $this->db->select('cberp_payment_transaction_link.transaction_number AS payment_transaction_number, cberp_invoices.status');
        $this->db->from("cberp_invoices");
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_invoices.invoice_number');
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);
        $query = $this->db->get();
        return $query->row_array();  
    }
    public function invoice_payments_received($invoice_number)
    {
        $this->db->select('
            cberp_invoices.invoice_number,
            cberp_payment_transaction_link.status,
            cberp_payment_transaction_link.bank_transaction_number,
            cberp_payment_transaction_link.created_by,
            cberp_payment_transaction_link.created_dt,
            cberp_payment_transaction_link.cancelled_by,
            cberp_payment_transaction_link.cancelled_dt,
            cberp_payment_transaction_link.note,
            cberp_bank_transactions.trans_account_id,
            cberp_bank_transactions.trans_chart_of_account_id,
            cberp_bank_transactions.trans_amount,
            cberp_bank_transactions.trans_ref_number,
            cberp_bank_transactions.trans_customer_id,
            cberp_bank_transactions.trans_payment_method,
            cberp_customers.name AS customer,
            account_chart.holder AS chart_holder,
            account_trans.holder AS trans_holder
        ');
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_invoices.invoice_number');
        $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_bank_transactions.trans_customer_id', 'left');
        $this->db->join('cberp_accounts AS account_chart', 'account_chart.acn = cberp_bank_transactions.trans_chart_of_account_id');
        $this->db->join('cberp_accounts AS account_trans', 'account_trans.acn = cberp_bank_transactions.trans_account_id');
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);
        $this->db->where('cberp_payment_transaction_link.trans_type', 'Invoice');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_invoice_transaction_details($invoice_number)
    {
        $this->db->select('
            cberp_invoices.transaction_number,
            cberp_transactions.debit,
            cberp_transactions.credit,
            cberp_transactions.date,
            cberp_transactions.acid,
            cberp_employees.name AS employee,
            cberp_accounts.holder,
            cberp_accounts.acn
        ');
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_invoices.transaction_number');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_transactions.eid', 'left');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);

        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }
    public function get_deliverynote_invoice_transaction_details($invoice_number)
    {
        $this->db->select('cberp_delivery_notes.transaction_number, cberp_transactions.debit, cberp_transactions.credit, cberp_transactions.date, 
        cberp_transactions.acid, cberp_employees.name AS employee, cberp_accounts.holder, cberp_accounts.acn');
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.invoice_number = cberp_invoices.invoice_number');
        $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_delivery_notes.transaction_number');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_transactions.eid', 'left');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_delivery_notes.invoice_number', $invoice_number);

        $query = $this->db->get();
        $result = $query->result_array(); // Fetch all rows as an array

        return $result; // Return the query result

    }

    //erp2024 09-12-2024 
    public function delnote_by_invoice_number($invoice_number)
    {

        $this->db->select('cberp_delivery_notes.*');
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.invoice_number = cberp_invoices.invoice_number');
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();

    }
    public function delnote_by_invoice_number_with_status($invoice_number)
    {
        $this->db->select('cberp_delivery_notes.*');
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.invoice_number = cberp_invoices.invoice_number');
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);
        // $this->db->where('cberp_delivery_notes.invoice_number!=', 'Paid');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();

    }

    public function invoice_credit_note_master_details_by_id($invoice_retutn_number)
    { 
        $this->db->select('cberp_stock_returns.invoice_retutn_number as returnid,cberp_stock_returns.transaction_number as transaction_number,cberp_stock_returns.invoice_retutn_number as tid,cberp_stock_returns.invoice_retutn_number,cberp_stock_returns.total AS returnamount, cberp_stock_returns.created_date, cberp_invoices.invoice_number,cberp_invoices.invoice_number as invoicenumber, cberp_invoices.grand_total AS invoiceamount, cberp_employees.name AS employee,cberp_customers.*,cberp_customers.customer_id as cid');
        $this->db->from('cberp_stock_returns');
        $this->db->join('cberp_invoices', 'cberp_invoices.invoice_number = cberp_stock_returns.invoice_number');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_stock_returns.created_by');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_stock_returns.customer_id');
        $this->db->where('cberp_stock_returns.invoice_retutn_number', $invoice_retutn_number);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    public function invoice_transaction_details_by_id($invoice_number)
    {
        $this->db->select('cberp_transactions.acid, cberp_transactions.debit, cberp_transactions.credit');
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_invoices.transaction_number');
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);
        $query = $this->db->get();    
        return $query->result_array();
    }


    public function check_delivered_and_return_qty_equal($invoice_number) {
        $this->db->select("
            CASE 
                WHEN COUNT(*) = SUM(cberp_invoice_items.quantity = cberp_invoice_items.return_quantity) 
                THEN 1 
                ELSE 0 
            END AS all_equal", false);
        $this->db->from('cberp_invoice_items');
        $this->db->where('cberp_invoice_items.invoice_number', $invoice_number);        
        $query = $this->db->get();
        return $query->row()->all_equal;
    }

    public function gethistory($tid)
    {
        $this->db->select('cberp_invoice_log.*,cberp_employees.name');
        $this->db->from('cberp_invoice_log');  
        $this->db->join('cberp_employees','cberp_invoice_log.performed_by=cberp_employees.id');
        $this->db->where('cberp_invoice_log.invoice_id',$tid);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function receipthistory($id)
    {
        $this->db->select('purchase_receipt_log.*,cberp_employees.name');
        $this->db->from('purchase_receipt_log');  
        $this->db->join('cberp_employees','purchase_receipt_log.performed_by=cberp_employees.id');
        $this->db->where('purchase_receipt_log.reciept_id',$id);
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
      public function customerBySalesorderid($salesorder_number)
      {
          $this->db->select('cberp_customers.*,cberp_country.name as countryname');
          $this->db->from('cberp_customers');        
          $this->db->join('cberp_sales_orders', 'cberp_sales_orders.customer_id = cberp_customers.customer_id', 'left');
          $this->db->join('cberp_country', 'cberp_country.id = cberp_customers.country');
          $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_number);
          $query = $this->db->get();
          return $query->row_array();
      }
      public function customerByDeliverynoteid($delivery_note_number)
      {
          $this->db->select('cberp_customers.*,cberp_country.name as countryname');
          $this->db->from('cberp_customers');        
          $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.customer_id = cberp_customers.customer_id', 'left');
          $this->db->join('cberp_country', 'cberp_country.id = cberp_customers.country');
          $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
          $query = $this->db->get();
          return $query->row_array();
      }

      public function salesorder_products($salesorder_number)
      {
          $this->db->select('cberp_sales_orders_items.*,cberp_sales_orders_items.lowest_price as product_cost,cberp_product_description.product_name,cberp_products.product_code,cberp_products.onhand_quantity as onhandqty,cberp_products.unit, cberp_product_pricing.minimum_price as minprice, cberp_products.maximum_discount_rate as maximumdiscount,cberp_products.income_account_number,cberp_products.expense_account_number');
          $this->db->from('cberp_sales_orders_items');
          $this->db->join('cberp_products', 'cberp_products.product_code = cberp_sales_orders_items.product_code');
          $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
          $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code');
          $this->db->where('cberp_sales_orders_items.salesorder_number', $salesorder_number);
          $query = $this->db->get();
          return $query->result_array(); 
      }


      public function calculate_average_cost($product_code,$new_qty,$new_cost)
      {
         //reference
         //https://docs.google.com/spreadsheets/d/1l-E_1PBbAksV02H_S9ouUaEt19E2Oh3QtQjksnUDU1Y/edit?pli=1&gid=0#gid=0
          $this->db->select('cberp_products.product_cost AS cost, cberp_products.onhand_quantity');
          $this->db->from('cberp_products');
          $this->db->where('cberp_products.average_price_table_entry', 'Yes');
          $this->db->where('cberp_products.product_code', $product_code);
          $query = $this->db->get();
         $result = $query->row_array();
         $previous_product_value = 0;
         $previous_qty = 0;
         $cost=0;
          if($result)
          {
            $cost = $result['cost'];
            $onhand_qty = $result['onhand_quantity'];
            $previous_product_value = $cost*$onhand_qty;
            $previous_qty = $onhand_qty;
          }
                  
        
          $new_product_value = $new_cost*$new_qty;

          $inventory_product_value = $new_product_value + $previous_product_value;

          $onhand_quantity = $previous_qty + $new_qty;
          $average_cost = $inventory_product_value/$onhand_quantity;
          $average_cost = round($average_cost, 2);

          $inventory_product_value = $average_cost * $onhand_quantity;


          $average_cost_data = array(
            'product_code'             => $product_code,
            'product_cost'           => $new_cost,
            'transaction_date_time'  => date("Y-m-d H:i:s"),
            'transaction_quantity'   => $new_qty,
            'product_average_cost'   => $average_cost,
            'product_inventory_value'=> $inventory_product_value,
            'onhand_quantity'        => $previous_qty + $new_qty,
            'transaction_type'       => get_costing_transation_type("Purchase"),
            'added_by'               => $this->session->userdata('id')
          );

          $this->db->insert('cberp_average_cost',$average_cost_data);
          
          $productcost = [
            'average_price_table_entry' => 'Yes',
            'product_cost' => $new_cost,
            'weighted_average_cost' => $average_cost,
            'updated_by' => $this->session->userdata('id'),
            'updated_date' => date("Y-m-d H:i:s")
         ];
        $this->db->where('product_code', $product_code);
        $this->db->update('cberp_products', $productcost);
        $this->product_cost_update($product_code,$average_cost,$cost);
      
      }

    public function product_cost_update($product_code,$cost,$old_cost)
    {

        $this->db->select('*');
        $this->db->from('cberp_product_min_price');
        $query = $this->db->get();       
        $pricepercentages  = $query->row_array();
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
        ];
        $this->db->where('product_code', $product_code);
        $this->db->update('cberp_product_pricing',$data);
        $mainproduct = [
            'product_price'=>$selling_price,
            'product_cost'=>$cost
        ];
        $this->db->where('product_code', $product_code);
        $this->db->update('cberp_products',$mainproduct);
        $changedFields = json_encode([
            [
                'fieldlabel' => $this->input->post('productname')."(".$this->input->post('product_code').")",
                'field_name' => "Cost Update", 
                'oldValue' => $old_cost,
                'newValue' => $cost
            ]
        ]);
        
        detailed_log_history('Purchasereceipt',$this->input->post('receipt_id'),'Cost Updated', $changedFields);

    }

    // public function get_filter_count(){
    //     $today = date('Y-m-d');
    //     $ranges = getCommonDateRanges($today);
    //      $startMonth    = $ranges['month'];
    //     $startWeek     = $ranges['week'];
    //     $startQuarter  = $ranges['quarter'];
    //     $startYear     = $ranges['year'];
       
       
    //     $query = $this->db->query("SELECT 
       
    //     -- SUM(CASE WHEN invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
    //     -- SUM(CASE WHEN invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
    //     -- SUM(CASE WHEN invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
    //     -- SUM(CASE WHEN invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
    //     -- SUM(CASE WHEN DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_count,    

    //     SUM(CASE WHEN invoicedate BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
    //     SUM(CASE WHEN invoicedate BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
    //     SUM(CASE WHEN invoicedate BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
    //     SUM(CASE WHEN invoicedate BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
    //     SUM(CASE WHEN DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_count

    //     SUM(CASE WHEN status = 'due' AND invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
    //     SUM(CASE WHEN status = 'due' AND invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
    //     SUM(CASE WHEN status = 'due' AND invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
    //     SUM(CASE WHEN status = 'due' AND invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
    //     SUM(CASE WHEN status = 'due' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,    

    //     SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
    //     SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
    //     SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
    //     SUM(CASE WHEN status = 'draft' AND invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
    //     SUM(CASE WHEN status = 'draft' AND DATE(invoicedate) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,
    

    //     SUM(CASE WHEN invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 YEAR) AND '$today' THEN total ELSE 0 END) AS yearly_total,
    //     SUM(CASE WHEN invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 QUARTER) AND '$today' THEN total ELSE 0 END) AS quarterly_total,
    //     SUM(CASE WHEN invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 MONTH) AND '$today' THEN total ELSE 0 END) AS monthly_total,
    //     SUM(CASE WHEN invoicedate BETWEEN DATE_SUB('$today', INTERVAL 1 WEEK) AND '$today' THEN total ELSE 0 END) AS weekly_total,
    //     SUM(CASE WHEN DATE(invoicedate) = '$today' THEN total ELSE 0 END) AS daily_total
    // FROM cberp_invoices
    // ");
     
    //     return $query->row();
    // }
      
    public function get_filter_count($ranges)
    {
        $today = date('Y-m-d');
        $startMonth    = $ranges['month'];
        $startWeek     = $ranges['week'];
        $startQuarter  = $ranges['quarter'];
        $startYear     = $ranges['year'];

       $query = $this->db->query("
            SELECT 
                -- grand_total invoice counts
                SUM(CASE WHEN DATE(invoice_date) BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN DATE(invoice_date) BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN DATE(invoice_date) BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN DATE(invoice_date) BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(invoice_date) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- 'due' status invoice counts
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND DATE(invoice_date) BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND DATE(invoice_date) BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND DATE(invoice_date) BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND DATE(invoice_date) BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN (status = 'due' || status = 'partial' || status = 'paid') AND DATE(invoice_date) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

                -- 'draft' status invoice counts
                SUM(CASE WHEN status = 'draft' AND DATE(invoice_date) BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN status = 'draft' AND DATE(invoice_date) BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN status = 'draft' AND DATE(invoice_date) BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN status = 'draft' AND DATE(invoice_date) BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN status = 'draft' AND DATE(invoice_date) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- grand_totals (sum of 'grand_total' column)
                SUM(CASE WHEN DATE(invoice_date) BETWEEN '$startYear' AND '$today' THEN grand_total ELSE 0 END) AS yearly_grand_total,
                SUM(CASE WHEN DATE(invoice_date) BETWEEN '$startQuarter' AND '$today' THEN grand_total ELSE 0 END) AS quarterly_grand_total,
                SUM(CASE WHEN DATE(invoice_date) BETWEEN '$startMonth' AND '$today' THEN grand_total ELSE 0 END) AS monthly_grand_total,
                SUM(CASE WHEN DATE(invoice_date) BETWEEN '$startWeek' AND '$today' THEN grand_total ELSE 0 END) AS weekly_grand_total,
                SUM(CASE WHEN DATE(invoice_date) = '$today' THEN grand_total ELSE 0 END) AS daily_grand_total
            FROM cberp_invoices
        ");
      
        return $query->row();
    }


    public function purchase_order_items_update($purchase_number, $product_code, $product_qty_recieved)
    {
        $this->db->select('received_quantity, quantity');
        $this->db->where('product_code', $product_code);
        $this->db->where('purchase_number', $purchase_number);
        $query = $this->db->get('cberp_purchase_order_items');

        if ($query->num_rows() > 0) {
            $row = $query->row_array();

            $received_quantity = (int)$row['received_quantity'];
            $qty = (int)$row['quantity'];

            $onhandqty = $received_quantity;
            $remainingqty = $onhandqty + $product_qty_recieved;


            $update_data = [
                'received_quantity' => $remainingqty
            ];

            if ($remainingqty == $qty) {
                $update_data['product_status'] = '1';
            }
            $this->db->where('product_code', $product_code);
            $this->db->where('purchase_number', $purchase_number);
            $this->db->update('cberp_purchase_order_items', $update_data);
        }
    }

    public function update_purchase_order_status($purchase_number)
    {
        $this->db->where('purchase_number', $purchase_number);
        $total_items = $this->db->count_all_results('cberp_purchase_order_items');

        $this->db->where('purchase_number', $purchase_number);
        $this->db->where('product_status', '1');
        $status_items = $this->db->count_all_results('cberp_purchase_order_items');

        if ($total_items > 0 && $total_items == $status_items) {
            $this->db->where('purchase_number', $purchase_number);
            $this->db->update('cberp_purchase_orders', ['receipt_status' => '1']);
        }
        else{
            $this->db->where('purchase_number', $purchase_number);
            $this->db->update('cberp_purchase_orders', ['receipt_status' => '2']);
        }
    }


    public function payment_receipt_number($invoice_number)
    {
        $this->db->select('cberp_invoice_payments.receipt_number, cberp_invoices.invoice_number');
        $this->db->from('cberp_invoices');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_invoices.invoice_number');
        $this->db->join('cberp_invoice_payments', 'cberp_invoice_payments.transaction_number = cberp_payment_transaction_link.transaction_number');
        $this->db->where('cberp_invoices.invoice_number', $invoice_number);
        $query = $this->db->get();   
        return $query->result_array();
    }
    public function payment_receipt_details($invoice_number,$receipt_number)
    {
        $this->db->select('cberp_invoice_payments_details.*,cberp_invoice_payments.created_date');
        $this->db->from('cberp_invoice_payments');
        $this->db->join('cberp_invoice_payments_details', 'cberp_invoice_payments_details.receipt_number = cberp_invoice_payments.receipt_number');
        $this->db->where('cberp_invoice_payments_details.invoice_number', $invoice_number);
        $this->db->where('cberp_invoice_payments_details.receipt_number', $receipt_number);
        $query = $this->db->get();   
        // die($this->db->last_query());
        return $query->row_array();
    }

}