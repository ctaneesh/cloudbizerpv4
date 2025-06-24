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

class Invoice_creditnotes_model extends CI_Model
{
    
    var $table = 'cberp_stock_returns';
    var $column_order = array(null, 'cberp_stock_returns.invoice_retutn_number','cberp_invoices.invoice_number', 'cberp_customers.name', 'cberp_stock_returns.created_date','cberp_stock_returns.payment_status','cberp_stock_returns.total', null);
    var $column_search = array('cberp_stock_returns.invoice_retutn_number','cberp_invoices.invoice_number', 'cberp_customers.name', 'cberp_stock_returns.created_date','cberp_stock_returns.total','cberp_stock_returns.payment_status');
    var $order = array('cberp_stock_returns.return_date' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function lastinvoice()
    {
        $this->db->select('tid');
        $this->db->from($this->table);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $this->db->where('i_class', 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->tid;
        } else {
            return 1000;
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


    public function invoice_details($id, $eid = '',$p=true)
    {
        $this->db->select('cberp_invoices.*,SUM(cberp_invoices.shipping + cberp_invoices.ship_tax) AS shipping,cberp_customers.*,cberp_invoices.loc as loc,cberp_invoices.id AS iid,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms,cberp_customers.avalable_credit_limit,cberp_invoices.status as paymentstatus');
        $this->db->from($this->table);
        $this->db->where('cberp_invoices.id', $id);
        if ($eid) {
            $this->db->where('cberp_invoices.eid', $eid);
        }
        if($p) {


            // if ($this->aauth->get_user()->loc) { 
            //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
            // } elseif (!BDATA) {
            //     $this->db->where('cberp_invoices.loc', 0);
            // }
        }
        $this->db->join('cberp_customers', 'cberp_invoices.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_invoices.term', 'left');
        $query = $this->db->get();
        // die( $this->db->last_query());
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


    public function invoice_products($id)
    {

        $this->db->select('cberp_invoice_items.*,cberp_products.product_name,cberp_products.product_code,cberp_products.onhand_quantity as onhandqty,cberp_products.unit, cberp_product_ai.min_price as minprice,cberp_product_ai.max_disrate as maximumdiscount');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid');
        $this->db->join('cberp_product_ai', 'cberp_product_ai.product_id = cberp_products.pid', 'left');
        $this->db->where('cberp_invoice_items.tid', $id);
        $query = $this->db->get();
        // die($this->db->last_query());
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
        $this->db->select('cberp_stock_returns.invoice_retutn_number,cberp_stock_returns.discount,cberp_stock_returns.total,cberp_stock_returns.status,cberp_stock_returns.creditnote_status,cberp_stock_returns.created_date,cberp_customers.name,cberp_employees.name AS employee,cberp_invoices.invoice_number,cberp_invoices.invoice_number as invoiceid,cberp_stock_returns.customer_id,cberp_stock_returns.payment_status');
        $this->db->from('cberp_stock_returns');        
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_stock_returns.customer_id');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_stock_returns.created_by');
        $this->db->join('cberp_invoices', 'cberp_invoices.invoice_number = cberp_stock_returns.invoice_number');
        $this->db->where('cberp_stock_returns.invoice_number IS NOT NULL');


        if ($this->input->post('start_date') && $this->input->post('end_date'))
        {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(cberp_stock_returns.created_date) BETWEEN '$start_date' AND '$end_date'");
        }


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
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        // if ($opt) {
        //     $this->db->where('eid', $opt);
        // }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('cberp_stock_returns.invoice_retutn_number');
        $this->db->from('cberp_stock_returns');
        $this->db->where('cberp_stock_returns.invoice_number IS NOT NULL');
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

    public function get_enquiry_count(){
        $query = $this->db->query("
        SELECT 
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_count,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_count,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_count,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_count,
        SUM(CASE WHEN DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Assigned' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_assigned_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_open_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_open_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_open_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_open_count,
        SUM(CASE WHEN enquiry_status = 'Open' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_open_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE 0 END) AS yearly_closed_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN 1 ELSE 0 END) AS quarterly_closed_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE 0 END) AS monthly_closed_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE 0 END) AS weekly_closed_count,
        SUM(CASE WHEN enquiry_status = 'Closed' AND DATE(created_date) = CURDATE() THEN 1 ELSE 0 END) AS daily_closed_count,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN total ELSE 0 END) AS yearly_total,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 QUARTER AND CURDATE() THEN total ELSE 0 END) AS quarterly_total,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN total ELSE 0 END) AS monthly_total,
        SUM(CASE WHEN created_date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN total ELSE 0 END) AS weekly_total,
        SUM(CASE WHEN DATE(created_date) = CURDATE() THEN total ELSE 0 END) AS daily_total
    FROM 
        customer_leads
    ");

        return $query->row();
    }
    public function get_dynamic_count($table,$datefield,$amountfield,$condition){
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
    public function customer_enqnumber()
    {
        $this->db->select('MAX(id) + 1 as next_id');
        $this->db->from('customer_enquiry');
        $query = $this->db->get();
        $next_id = $query->row()->next_id;
        if (is_null($next_id) || $next_id == 0) {
            $next_id = 1;
        }
        return $next_id;
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

    public function create_customer($customer_data){
        $this->db->select('id');
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
        $this->db->select('cberp_invoices.id AS invoiceid,cberp_invoices.tid AS invoiceumber, cberp_invoices.invoicedate, cberp_invoices.invoiceduedate, cberp_invoices.subtotal, cberp_invoices.status');
        $this->db->from('cberp_invoices');
        $this->db->where('cberp_invoices.csd', $customerid); 
        $this->db->where('cberp_invoices.status', 'due');
        $query = $this->db->get();
        $result = $query->result_array(); 
        // die($this->db->last_query());
        return $result;
    }

    public function payment_method_details($invoiceid)
    {
        $this->db->select('*');
        $this->db->from('cberp_payments');
        $this->db->where('cberp_payments.invoice_id', $invoiceid); 
        $query = $this->db->get();         
        // die($this->db->last_query());
        return  $query->row_array();
    }
    public function payment_pending_invoice($customer_id)
    {
        $this->db->select('cberp_invoices.id');
        $this->db->from('cberp_invoices');
        $this->db->where('cberp_invoices.csd', $customer_id);
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
        $this->db->select('cberp_sales_orders.customer_reference_number,cberp_sales_orders.invoicedate as refdate,cberp_delivery_notes.delnote_number,cberp_delivery_notes.created_date');
        $this->db->from('cberp_sales_orders');  
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.salesorder_id = cberp_sales_orders.id');
        $this->db->where('cberp_delivery_notes.delevery_note_id', $id);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    // erp2024 23-10-2024 starts
    public function customerByInvoiceid($id)
    {
        $this->db->select('cberp_customers.*,cberp_country.name as countryname');
        $this->db->from('cberp_customers');        
        $this->db->join('cberp_invoices', 'cberp_invoices.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_customers.country');
        $this->db->where('cberp_invoices.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function invoice_already_exist_or_not($tid){
        $this->db->select('id');
        $this->db->from('cberp_invoices');
        $this->db->where("cberp_invoices.tid",$tid);
        $query2 = $this->db->get();
        // die($this->db->last_query());
        $result2 = $query2->row_array();
        if ($result2) {
            return $result2['id'];
        } else {
            return 0;
        }
    }

    // erp2024 23-10-2024 ends

    //erp2024  04-11-2024 starts
    public function invoice_by_id($id)
    {
        $this->db->select('cberp_invoices.*,cberp_invoices.id as invoiceid,cberp_invoices.store_id as warehouseid,cberp_invoices.delevery_note_id as delevery_note_id,cberp_invoices.refer as delnoterefer,cberp_invoices.invoicedate as delnoteinvoicedate, cberp_customers.*,cberp_invoices.status as notestatus,cberp_stock_returns.invoice_retutn_number as creditnote_number');
        $this->db->from('cberp_invoices');        
        $this->db->join('cberp_customers', 'cberp_invoices.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_stock_returns', 'cberp_stock_returns.invoice_id = cberp_invoices.id');
        $this->db->where('cberp_invoices.id', $id);
        $query = $this->db->get();
            //  echo $this->db->last_query(); die();
        return $query->row_array();
    }
    public function invoice_products_for_return($tid)
    {
        $this->db->select('cberp_invoice_items.*,cberp_invoice_items.discount_type as prd_discount_type, cberp_products.product_code AS prdcode, cberp_products.product_name AS prdname');
        $this->db->from('cberp_invoice_items');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid');
        $this->db->where('cberp_invoice_items.tid', $tid);
        // $this->db->group_by('cberp_invoice_items.tid');
        $query = $this->db->get();
        return $query->result_array();

    }
    public function product_qty_update_to_invoice_items_table($invoiceid, $product_id, $return_qty, $damaged_qty) {
        $this->db->select('return_qty,damaged_qty');
        $this->db->from('cberp_invoice_items');
        $this->db->where('cberp_invoice_items.tid', $invoiceid);
        $this->db->where('cberp_invoice_items.pid', $product_id);
        $query = $this->db->get();    
        // echo $this->db->last_query(); die();
        $result = $query->row_array();
        if ($result) { 
            $current_ret_qty = intval($result['return_qty']);
            $current_damage_qty = intval($result['damaged_qty']);
        } else {
            $current_ret_qty = 0; 
            $current_damage_qty = 0;
        }
        
        $returned_qty = intval($current_ret_qty) + intval($return_qty);
        $returned_damage_qty = intval($current_damage_qty) + intval($damaged_qty);
        
        $this->db->set('return_qty', $returned_qty);
        $this->db->set('damaged_qty', $returned_damage_qty);
        $this->db->set('creditnote_status', 'Pending');
        $this->db->where('cberp_invoice_items.tid', $invoiceid);
        $this->db->where('cberp_invoice_items.pid', $product_id);
        $this->db->update('cberp_invoice_items');

    }

    // erp2024 12-12-2024
    public function invoice_return_details($invoice_retutn_number)
    {
        $this->db->select('cberp_stock_returns.*,cberp_stock_returns.invoice_retutn_number as returnid, cberp_customers.*,cberp_employees.name as employee,cberp_invoices.invoice_number,cberp_invoices.invoice_number as invoiceid,cberp_invoices.store_id');
        $this->db->from('cberp_stock_returns');        
        $this->db->join('cberp_customers', 'cberp_stock_returns.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_stock_returns.created_by');
        $this->db->join('cberp_invoices', 'cberp_invoices.invoice_number = cberp_stock_returns.invoice_number');
        $this->db->where('cberp_stock_returns.invoice_retutn_number', $invoice_retutn_number);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function invoice_return_products($invoice_retutn_number)
    {
        $this->db->select('cberp_stock_returns_items.*,cberp_product_description.product_name,cberp_products.product_code,cberp_product_description.product_name AS prdname,cberp_products.product_code AS prdcode,cberp_products.onhand_quantity as onhandqty,cberp_products.unit, cberp_product_pricing.minimum_price as minprice,cberp_products.maximum_discount_rate as maximumdiscount,cberp_products.income_account_number,cberp_products.expense_account_number');
        $this->db->from('cberp_stock_returns_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_stock_returns_items.product_code');
        $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code', 'left');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_stock_returns_items.invoice_retutn_number', $invoice_retutn_number);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }

    public function get_journals_for_invoice_return($invoice_retutn_number)
    {
        $this->db->select('cberp_transactions.transaction_number, cberp_transactions.debit, cberp_transactions.credit, cberp_transactions.date, 
        cberp_transactions.acid, cberp_employees.name AS employee, cberp_accounts.holder, cberp_accounts.acn');
        $this->db->from('cberp_stock_returns');
        $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_stock_returns.transaction_number');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_transactions.eid', 'left');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_transactions.acid');
        $this->db->where('cberp_stock_returns.invoice_retutn_number', $invoice_retutn_number);

        $query = $this->db->get();
        $result = $query->result_array(); // Fetch all rows as an array

        return $result; // Return the query result

    }
    public function get_bank_transaction_for_invoice_return($invoice_retutn_number)
    {
        $this->db->select('cberp_bank_transactions.trans_date, cberp_bank_transactions.trans_payment_method, cberp_bank_transactions.trans_amount, cberp_bank_transactions.trans_ref_number, cberp_bank_transactions.trans_account_id, cberp_bank_transactions.trans_number as banktransaction_number,cberp_bank_transactions.from_trans_number as transcation_number,cberp_bank_transactions.trans_ref_number as reference_number,cberp_bank_transactions.trans_chart_of_account_id');
        $this->db->from('cberp_stock_returns');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_stock_returns.invoice_retutn_number');
        $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number');
        $this->db->where('cberp_stock_returns.invoice_retutn_number', $invoice_retutn_number);
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->row_array(); 
        return $result; // Return the query result

    }

    public function product_qty_update_to_invoice_items_table_edit($invoice_number, $product_code, $return_qty, $damaged_qty,$returnamt,$return_qty_old, $damaged_qty_old,$returnamt_old) {
        $this->db->select('return_quantity,damaged_quantity');
        $this->db->from('cberp_invoice_items');
        $this->db->where('cberp_invoice_items.invoice_number', $invoice_number);
        $this->db->where('cberp_invoice_items.product_code', $product_code);
        $query = $this->db->get();    
        $result = $query->row_array();
        if ($result) { 
            $current_ret_qty = intval($result['return_quantity']);
            $current_damage_qty = intval($result['damaged_quantity']);
        } else {
            $current_ret_qty = 0; 
            $current_damage_qty = 0;
        }
        
        $returned_qty = intval($current_ret_qty) + intval($return_qty);
        $returned_qty = intval($returned_qty) - intval($return_qty_old);

        $returned_damage_qty = intval($current_damage_qty) + intval($damaged_qty);
        $returned_damage_qty = intval($returned_damage_qty) - intval($damaged_qty_old);

        $this->db->set('approved_return_amount', $returnamt);
        $this->db->set('return_quantity', abs($returned_qty));
        $this->db->set('damaged_quantity', abs($returned_damage_qty));
        $this->db->where('cberp_invoice_items.invoice_number', $invoice_number);
        $this->db->where('cberp_invoice_items.product_code', $product_code);
        $this->db->update('cberp_invoice_items');
        // die($this->db->last_query());

    }

    public function invoice_payments_received($invoice_retutn_number)
    {
        $this->db->select('
            cberp_stock_returns.invoice_retutn_number,
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
        $this->db->from('cberp_stock_returns');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_stock_returns.invoice_retutn_number');
        $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_bank_transactions.trans_customer_id', 'left');
        $this->db->join('cberp_accounts AS account_chart', 'account_chart.acn = cberp_bank_transactions.trans_chart_of_account_id');
        $this->db->join('cberp_accounts AS account_trans', 'account_trans.acn = cberp_bank_transactions.trans_account_id');
        $this->db->where('cberp_stock_returns.invoice_retutn_number', $invoice_retutn_number);
        $this->db->where('cberp_payment_transaction_link.trans_type', 'Invoice Return');

        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();

        return $result;
    }

    public function get_invoice_return_details_bank_trans_number($trans_ref_number)
    {
        $this->db->select('cberp_payment_transaction_link.transaction_number,cberp_payment_transaction_link.bank_transaction_number,cberp_bank_transactions.trans_account_id,cberp_bank_transactions.trans_chart_of_account_id,cberp_bank_transactions.trans_date,cberp_bank_transactions.trans_amount,cberp_bank_transactions.trans_ref_number,cberp_bank_transactions.trans_payment_method
        ');
        $this->db->from('cberp_bank_transactions');
        $this->db->join(
            'cberp_payment_transaction_link',
            'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number'
        );
        $this->db->join(
            'cberp_stock_returns',
            'cberp_stock_returns.invoice_retutn_number = cberp_payment_transaction_link.trans_type_number'
        );
        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function bank_transaction_ref_number($invoice_retutn_number)
    {
        $this->db->select('cberp_bank_transactions.trans_ref_number');
        $this->db->from('cberp_stock_returns');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_stock_returns.invoice_retutn_number', 'inner');
        $this->db->join('cberp_bank_transactions', 'cberp_bank_transactions.trans_number = cberp_payment_transaction_link.bank_transaction_number', 'inner');
        $this->db->where('cberp_stock_returns.invoice_retutn_number', $invoice_retutn_number);

        $query = $this->db->get();
        return $query->row_array();

    }
     //erp2024 09-01-2025 detailed history log starts

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
     //erp2024 09-01-2025 detailed history log ends


     public function product_qty_update_to_invoice_items_table_return($invoiceid, $product_id, $return_qty, $damaged_qty,$returnamt) {
        $this->db->select('return_qty,damaged_qty,qty');
        $this->db->from('cberp_invoice_items');
        $this->db->where('cberp_invoice_items.tid', $invoiceid);
        $this->db->where('cberp_invoice_items.pid', $product_id);
        $query = $this->db->get();    
        // echo $this->db->last_query(); die();
        $result = $query->row_array();
        if ($result) { 
            $current_ret_qty = intval($result['return_qty']);
            $current_damage_qty = intval($result['damaged_qty']);
            $qty = intval($result['qty']);
        } else {
            $current_ret_qty = 0; 
            $current_damage_qty = 0;
            $qty = 0;
        }
        
        $returned_qty = intval($current_ret_qty) - intval($return_qty);

        $qty = intval($qty) + intval($returned_qty);

        $returned_damage_qty = abs(intval($current_damage_qty) - intval($damaged_qty));

        // $this->db->set('qty', $qty); 
        $this->db->set('approved_return_amount', $returnamt);
        $this->db->set('return_qty', $returned_qty);
        $this->db->set('damaged_qty', $returned_damage_qty);
        $this->db->where('cberp_invoice_items.tid', $invoiceid);
        $this->db->where('cberp_invoice_items.pid', $product_id);
        $this->db->update('cberp_invoice_items');
        // die($this->db->last_query());

    }
    
    public function get_filter_count($ranges)
    {
        $today = date('Y-m-d');
        $startMonth   = $ranges['month'];
        $startWeek    = $ranges['week'];
        $startQuarter = $ranges['quarter'];
        $startYear    = $ranges['year'];
        
        $query = $this->db->query("
            SELECT 
                -- Total invoice counts
                SUM(CASE WHEN DATE(return_date) BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN DATE(return_date) BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN DATE(return_date) BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN DATE(return_date) BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(return_date) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- 'due' status
                SUM(CASE WHEN payment_status = 'due' AND DATE(return_date) BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN payment_status = 'due' AND DATE(return_date) BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN payment_status = 'due' AND DATE(return_date) BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN payment_status = 'due' AND DATE(return_date) BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN payment_status = 'due' AND DATE(return_date) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

                -- 'paid' status
                SUM(CASE WHEN payment_status = 'Paid' AND DATE(return_date) BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_paid_count,
                SUM(CASE WHEN payment_status = 'Paid' AND DATE(return_date) BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_paid_count,
                SUM(CASE WHEN payment_status = 'Paid' AND DATE(return_date) BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_paid_count,
                SUM(CASE WHEN payment_status = 'Paid' AND DATE(return_date) BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_paid_count,
                SUM(CASE WHEN payment_status = 'Paid' AND DATE(return_date) = '$today' THEN 1 ELSE 0 END) AS daily_paid_count,

                -- 'partial' status
                SUM(CASE WHEN payment_status = 'Partial' AND DATE(return_date) BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_partial_count,
                SUM(CASE WHEN payment_status = 'Partial' AND DATE(return_date) BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_partial_count,
                SUM(CASE WHEN payment_status = 'Partial' AND DATE(return_date) BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_partial_count,
                SUM(CASE WHEN payment_status = 'Partial' AND DATE(return_date) BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_partial_count,
                SUM(CASE WHEN payment_status = 'Partial' AND DATE(return_date) = '$today' THEN 1 ELSE 0 END) AS daily_partial_count,

                -- Totals
                SUM(CASE WHEN DATE(return_date) BETWEEN '$startYear' AND '$today' THEN total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN DATE(return_date) BETWEEN '$startQuarter' AND '$today' THEN total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN DATE(return_date) BETWEEN '$startMonth' AND '$today' THEN total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN DATE(return_date) BETWEEN '$startWeek' AND '$today' THEN total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(return_date) = '$today' THEN total ELSE 0 END) AS daily_total

            FROM cberp_stock_returns
            WHERE invoice_number IS NOT NULL
        ");

        return $query->row();
    }

    public function payment_receipt_number($invoice_reurn_number)
    {
        $this->db->select('cberp_invoice_return_payments.receipt_number, cberp_stock_returns.invoice_retutn_number');
        $this->db->from('cberp_stock_returns');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.trans_type_number = cberp_stock_returns.invoice_retutn_number');
        $this->db->join('cberp_invoice_return_payments', 'cberp_invoice_return_payments.transaction_number = cberp_payment_transaction_link.transaction_number');
        $this->db->where('cberp_stock_returns.invoice_retutn_number', $invoice_reurn_number);
        $query = $this->db->get();   
        return $query->result_array();
    }
    public function payment_receipt_details($invoice_reurn_number,$receipt_number)
    {
        $this->db->select('cberp_invoice_return_payments_details.*,cberp_invoice_return_payments.created_date');
        $this->db->from('cberp_invoice_return_payments');
        $this->db->join('cberp_invoice_return_payments_details', 'cberp_invoice_return_payments_details.receipt_number = cberp_invoice_return_payments.receipt_number');
        $this->db->where('cberp_invoice_return_payments_details.invoice_reurn_number', $invoice_reurn_number);
        $this->db->where('cberp_invoice_return_payments_details.receipt_number', $receipt_number);
        $query = $this->db->get();   
        return $query->row_array();
    }
      
}
