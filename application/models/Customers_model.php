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

class Customers_model extends CI_Model
{

    var $table = 'cberp_customers';
    var $column_order = array('cberp_customers.customer_id',null, 'cberp_customers.name', 'cberp_customers.address', 'cberp_customers.email', 'cberp_customers.phone','cberp_customers.expiry_date','cberp_employees.name',null,'cberp_customers.status', null);

    var $column_search = array('cberp_customers.name', 'cberp_customers.phone', 'cberp_customers.address', 'cberp_customers.city', 'cberp_customers.email', 'cberp_customers.document_id');

    var $trans_column_order = array('date', 'debit', 'credit', 'account', null);
    var $trans_column_search = array('id', 'date');
    var $inv_column_order = array(null, 'invoice_number', 'name', 'invoice_date', 'total', 'status', null);
    var $inv_column_search = array('invoice_number', 'name', 'invoice_date', 'total');
    var $order = array('cberp_customers.customer_id' => 'desc');

    var $transorder = array('cberp_transactions.id' => 'desc');

    var $inv_order = array('cberp_invoices.invoice_date' => 'desc');
    var $qto_order = array('cberp_quotes.created_date' => 'desc');
    var $note_order = array('cberp_notes.id' => 'desc');
    var $notecolumn_order = array(null, 'title', 'cdate', null);
    var $notecolumn_search = array('id', 'title', 'cdate');
    var $pcolumn_order = array('cberp_projects.status', 'cberp_projects.name', 'cberp_projects.edate', 'cberp_projects.worth', null);
    var $pcolumn_search = array('cberp_projects.name', 'cberp_projects.edate', 'cberp_projects.status');
    var $ptcolumn_order = array('status', 'name', 'duedate', 'start', null, null);
    var $ptcolumn_search = array('name', 'edate', 'status');
    var $porder = array('cberp_customers.customer_id' => 'desc');


    private function _get_datatables_query($id = '')
    {
        $due = $this->input->post('due');

        $filter_credit_rang_from =   !empty($this->input->post('filter_credit_rang_from')) ? $this->input->post('filter_credit_rang_from') : 0; ;
        $filter_credit_rang_to = !empty($this->input->post('filter_credit_rang_to')) ? $this->input->post('filter_credit_rang_to') : 0;       

        $filter_registration_expired_from = !empty($this->input->post('filter_registration_expired_from')) ? date('Y-m-d',strtotime($this->input->post('filter_registration_expired_from'))) : "";

        $filter_registration_expired_to = !empty($this->input->post('filter_registration_expired_to')) ? date('Y-m-d',strtotime($this->input->post('filter_registration_expired_to'))) : ""; 
        
        $filterstatus = !empty($this->input->post('filterstatus')) ?$this->input->post('filterstatus') : "";
        $filter_salesman = !empty($this->input->post('filter_salesman')) ?$this->input->post('filter_salesman') : "";

        if ($due) {

            $this->db->select('cberp_customers.*,SUM(cberp_invoices.total) AS total,SUM(cberp_invoices.pamnt) AS pamnt,cberp_employees.name as salesman');
            $this->db->from('cberp_invoices');
            $this->db->where('cberp_invoices.status!=', 'paid');
            $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_invoices.customer_id', 'left');
            if ($id != '') {
                $this->db->where('cberp_customers.customer_group_id', $id);
            }
            $this->db->group_by('cberp_invoices.csd');
            $this->db->order_by('total', 'desc');

        } else {
            $this->db->select('cberp_customers.*,cberp_employees.name as salesman');
            $this->db->from($this->table);
                // $this->db->order_by('cberp_customers.customer_id', 'desc');
            // if ($this->aauth->get_user()->loc) {
            //     $this->db->where('loc', $this->aauth->get_user()->loc);
            // } elseif (!BDATA) {
            //     $this->db->where('loc', 0);
            // }
            if ($id != '') {
                $this->db->where('customer_group_id', $id);
            }

        }
        $this->db->join('cberp_employees', 'cberp_customers.salesman_id = cberp_employees.id', 'left');
        if($filter_credit_rang_to>0)
        {
            $this->db->where("cberp_customers.credit_limit BETWEEN $filter_credit_rang_from AND $filter_credit_rang_to");

        }
        if($filterstatus)
        {
            $this->db->where("cberp_customers.status",$filterstatus);
        }
        if(!empty($filter_registration_expired_to) && !empty($filter_registration_expired_from)){
            $this->db->where("cberp_customers.expiry_date BETWEEN '$filter_registration_expired_from' AND '$filter_registration_expired_to'");
        }

        if($filter_salesman)
        {
            $this->db->where_in('cberp_customers.salesman_id',$filter_salesman);
        }
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        // if ($this->input->post('order')) {
        //     $this->db->order_by($this->column_order[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        // } else if (isset($this->order)) {
        //     $order = $this->order;
        //     $this->db->order_by(key($order), $order[key($order)]);
        // }
        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        else{
            $order = array('cberp_customers.customer_id' => 'DESC');
        }
    }

    function get_datatables($id = '')
    {
        $this->_get_datatables_query($id);
        // if ($this->aauth->get_user()->loc) {
        //    // $this->db->where('loc', $this->aauth->get_user()->loc);
        // }
        if ($this->input->post('length') != -1)
        {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered($id = '')
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        if ($id != '') {
            $this->db->where('cberp_customers.customer_group_id', $id);
        }
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_customers.loc', $this->aauth->get_user()->loc);
        // }
        return $query->num_rows($id = '');
    }

    public function count_all($id = '')
    {
        $this->_get_datatables_query();
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_customers.loc', $this->aauth->get_user()->loc);
        // }
        if ($id != '') {
            $this->db->where('cberp_customers.customer_group_id', $id);
        }
        $query = $this->db->get();
        return $query->num_rows($id = '');
    }

    public function details($custid,$loc=true)
    {
        $this->db->select('cberp_customers.*,users.lang,cberp_country.name as countryname');
        $this->db->from($this->table);
        $this->db->join('users', 'users.customer_id=cberp_customers.customer_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id=cberp_customers.country', 'left');
        $this->db->where('cberp_customers.customer_id', $custid);
        // if($loc) {
        //     if ($this->aauth->get_user()->loc) {
        //         $this->db->where('cberp_customers.loc', $this->aauth->get_user()->loc);
        //     } elseif (!BDATA) {
        //         $this->db->where('cberp_customers.loc', 0);
        //     }
        // }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function customer_details_by_id($customer_id)
    {
        $this->db->select('cberp_customers.*,cberp_customers.customer_id as customerid,cberp_customer_shipping.*,cberp_customer_billing.*,users.lang,cberp_country.name as countryname');
        $this->db->from($this->table);
        $this->db->join('users', 'users.cid=cberp_customers.customer_id', 'left');
        $this->db->join('cberp_customer_shipping', 'cberp_customers.customer_id=cberp_customer_shipping.customer_id', 'left');
        $this->db->join('cberp_customer_billing', 'cberp_customers.customer_id=cberp_customer_billing.customer_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id=cberp_customers.country', 'left');
        $this->db->where('cberp_customers.customer_id', $customer_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function money_details($custid)
    {

        $this->db->select('SUM(debit) AS debit,SUM(credit) AS credit');
        $this->db->from('cberp_transactions');
        $this->db->where('payerid', $custid);
        $this->db->where('ext', 0);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function due_details($custid)
    {

        $this->db->select('SUM(total) AS total,SUM(payment_recieved_amount) AS pamnt,SUM(total_discount) AS discount');
        $this->db->from('cberp_invoices');
        $this->db->where('customer_id', $custid);
        $query = $this->db->get();
        return $query->row_array();
    }

    //erp2024 newly added fields 03-06-2024
 
    // public function  add($name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox, $language = '', $create_login = true, $password = '', $document_id = '', $custom = '', $discount = 0, $registration_number='', $expiry_date='', $computer_card_number='', $sponser_id='',  $credit_limit, $credit_period='', $computer_card_image='', $sponser_image='', $profile_pic='', $contact_person='', $land_line='', $contact_phone1='', $contact_phone2='', $contact_email1='', $contact_email2='', $contact_designation='',$status='',$salesman_id='')
    public function  add($master_data, $billing_data, $shipping_data, $login_data, $customerid)
    {
        $this->db->select('email');
        $this->db->from('cberp_customers');
        $this->db->where('email', $email);
        $query = $this->db->get();
        $valid = $query->row_array();
        // die($this->db->last_query());
        if (empty($valid['email'])) {


            if ($discount) {
                $this->db->select('disc_rate');
                $this->db->from('cberp_cust_group');
                $this->db->where('id', $customergroup);
                $query = $this->db->get();
                $result = $query->row_array();
                $discount = $result['disc_rate'];
            }



            if ($this->aauth->get_user()->loc) {
                $master_data['loc'] = $this->aauth->get_user()->loc;
            }
            if($customerid)
            {
                $this->db->update('cberp_customers', $master_data,['customer_id'=>$customerid]);
                $shipping_data['customer_id'] = $customerid;
                $billing_data['customer_id'] = $customerid;
                $this->db->update('cberp_customer_shipping', $shipping_data,['customer_id'=>$customerid]); 
                $this->db->update('cberp_customer_billing', $billing_data,['customer_id'=>$customerid]); 
            }
            else{
                $this->db->insert('cberp_customers', $master_data);
                $customer_id = $this->db->insert_id();
                $shipping_data['customer_id'] = $customer_id;
                $billing_data['customer_id'] = $customer_id;
                $this->db->insert('cberp_customer_shipping', $shipping_data); 
                $this->db->insert('cberp_customer_billing', $billing_data); 
                 $p_string = '';
                $temp_password = '';                
                if ($login_data['create_login']==1) {

                    if ($login_data['password']) {
                        $temp_password = $login_data['password'];
                    } else {
                        $temp_password = rand(200000, 999999);
                    }

                    $pass = password_hash($temp_password, PASSWORD_DEFAULT);
                    $data = array(
                        'user_id' => 1,
                        'status' => 'active',
                        'is_deleted' => 0,
                        'name' => $master_data['company'],
                        'password' => $pass,
                        'email' => $master_data['email'],
                        'user_type' => 'Member',
                        'cid' => $customer_id,
                        'lang' => $master_data['language']
                    );

                    $this->db->insert('users', $data);
                    $p_string = ' Temporary Password is ' . $temp_password . ' ';
                }
                log_table_data('cberp_customers','cberp_customers_log', 'id' ,'customer_id','Create',$cid);
                $this->aauth->applog("[Client Added] $name ID " . $cid, $this->aauth->get_user()->username);
            }
            if ($customer_id) {
                //erp2024 for add new popup
                return json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') . $p_string . '&nbsp;<a href="' . base_url('customers/view?id=' . $cid) . '" class="btn btn-info btn-sm"><span class="icon-eye"></span>' . $this->lang->line('View') . '</a>', 'cid' => $cid, 'pass' => $temp_password, 'discount' => $discount,'cname'=>$name,'cadd2'=>$address,'ph'=>$phone,'email'=>$email,'credit_period'=>$credit_period,'credit_limit'=>$credit_limit,'avalable_credit_limit'=>$credit_limit));
                $this->custom->save_fields_data($cid, 1);
                $this->db->select('other');
                $this->db->from('univarsal_api');
                $this->db->where('id', 64);
                $query = $this->db->get();
                $othe = $query->row_array();

                if ($othe['other']) {
                    $auto_mail = $this->send_mail_auto($email, $name, $temp_password);
                    $this->load->model('communication_model');
                    $attachmenttrue = false;
                    $attachment = '';
                    $this->communication_model->send_corn_email($email, $name, $auto_mail['subject'], $auto_mail['message'], $attachmenttrue, $attachment);
                }
            } else {
                return json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            return json_encode(array('status' => 'Error', 'message' => 'Email is already in use'));
        }

    }
    //erp2024 newly added fields 03-06-2024


    //erp2024 modified edit function 03-06-2024
    // public function edit($id, $name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox, $document_id = '', $custom = '', $language = '', $discount = 0)
    // public function edit($id, $name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox, $document_id = '', $custom = '', $language = '', $discount = 0,$registration_number= '', $expiry_date= '', $computer_card_number= '', $sponser_id= '',  $credit_limit= '', $credit_period= '', $computer_card_image= '', $sponser_image= '', $contact_person= '', $land_line= '', $contact_phone1= '', $contact_phone2= '', $contact_email1= '', $contact_email2= '', $contact_designation='',$status,$salesman_id)
    // {
    //     $data = array(
    //         'name' => $name,
    //         'company' => $company,
    //         'phone' => $phone,
    //         'email' => $email,
    //         'address' => $address,
    //         'city' => $city,
    //         'region' => $region,
    //         'country' => $country,
    //         'postbox' => $postbox,
    //         'customer_group_id' => $customergroup,
    //         'tax_id' => $tax_id,
    //         'shipping_name' => $shipping_name,
    //         'shipping_phone' => $shipping_phone,
    //         'shipping_email' => $shipping_email,
    //         'shipping_address_1' => $shipping_address_1,
    //         'shipping_city' => $shipping_city,
    //         'shipping_region' => $shipping_region,
    //         'shipping_country' => $shipping_country,
    //         'shipping_postbox' => $shipping_postbox,
    //         'document_id' => $document_id,
    //         'custom1' => $custom,
    //         'discount' => $discount,
    //         // erp2024 new fields 03-06-2024 
    //         'registration_number' => $registration_number,
    //         'expiry_date' => $expiry_date,
    //         'computer_card_number' => $computer_card_number,            
    //         'sponser_id' => $sponser_id,
    //         'credit_limit' => $credit_limit,
    //         'credit_period' => $credit_period,
    //         'contact_person' => $contact_person,
    //         'land_line' => $land_line,
    //         'contact_phone1' => $contact_phone1,
    //         'contact_phone2' => $contact_phone2,
    //         'contact_email1' => $contact_email1,
    //         'contact_email2' => $contact_email2,
    //         'contact_designation' => $contact_designation,
    //         // erp2024 new fields 03-06-2024
    //         'status' => $status,
    //         'salesman_id' => $salesman_id
    //     );

    //     if(!empty($computer_card_image)){
    //         $data['computer_card_image'] = $computer_card_image;
    //     }
    //     if(!empty($sponser_image)){
    //         $data['sponser_image'] = $sponser_image;
    //     }
    //     $this->db->set($data);
    //     $this->db->where('customer_id', $id);
    //     // if ($this->aauth->get_user()->loc) {
    //     //     $this->db->where('loc', $this->aauth->get_user()->loc);
    //     // } elseif (!BDATA) {
    //     //     $this->db->where('loc', 0);
    //     // }
    //     if ($this->db->update('cberp_customers')) {
    //         $data = array(
    //             'name' => $name,
    //             'email' => $email,
    //             'lang' => $language
    //         );
    //         $this->db->set($data);
    //         $this->db->where('customer_id', $id);
    //         $this->db->update('users');

            
    //         $this->aauth->applog("[Client Updated] $name ID " . $id, $this->aauth->get_user()->username);            
    //         $this->custom->edit_save_fields_data($id, 1);
    //         return json_encode(array('status' => 'Success', 'message' => $this->lang->line('UPDATED') .'&nbsp;<a href="' . base_url('customers/view?id=' . $id) . '" class="btn btn-info btn-sm"><span class="icon-eye"></span>' . $this->lang->line('View') . '</a>'));
    //     } else {
    //         return json_encode(array('status' => 'Error', 'message' =>$this->lang->line('ERROR')));
    //     }

    // }

    public function changepassword($id, $password)
    {
        $pass = password_hash($password, PASSWORD_DEFAULT);
        $data = array(
            'password' => $pass
        );


        $this->db->set($data);
        $this->db->where('cid', $id);

        if ($this->db->update('users')) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function editpicture($id, $pic)
    {
        $this->db->select('picture');
        $this->db->from($this->table);
        $this->db->where('id', $id);

        $query = $this->db->get();
        $result = $query->row_array();


        $data = array(
            'picture' => $pic
        );


        $this->db->set($data);
        $this->db->where('id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        if ($this->db->update('cberp_customers') AND $result['picture'] != 'example.png') {

            unlink(FCPATH . 'userfiles/customers/' . $result['picture']);
            unlink(FCPATH . 'userfiles/customers/thumbnail/' . $result['picture']);
        }


    }

    public function group_list()
    {
        $whr = "";
        // if ($this->aauth->get_user()->loc) {
        //     $whr = "WHERE (cberp_customers.loc=" . $this->aauth->get_user()->loc . " ) ";
        //     if (BDATA) $whr = "WHERE (cberp_customers.loc=" . $this->aauth->get_user()->loc . " OR cberp_customers.loc=0 ) ";
        // } elseif (!BDATA) {
        //     $whr = "WHERE  cberp_customers.loc=0  ";
        // }

        $query = $this->db->query("SELECT c.*,p.pc FROM cberp_cust_group AS c LEFT JOIN ( SELECT customer_group_id,COUNT(customer_group_id) AS pc FROM cberp_customers $whr GROUP BY customer_group_id) AS p ON p.customer_group_id=c.id");
        return $query->result_array();
    }

    public function delete($id)
    {


        // if ($this->aauth->get_user()->loc) {
        //     $this->db->delete('cberp_customers', array('id' => $id, 'loc' => $this->aauth->get_user()->loc));

        // } 
        // elseif (!BDATA) {
        //     $this->db->delete('cberp_customers', array('id' => $id, 'loc' => 0));
        // } else {
        //     $this->db->delete('cberp_customers', array('id' => $id));
        // }

        $this->db->delete('cberp_customers', array('id' => $id));

        if ($this->db->affected_rows()) {
            $this->aauth->applog("[Client Deleted]  ID " . $id, $this->aauth->get_user()->username);
            $this->db->delete('users', array('customer_id' => $id));
            $this->custom->del_fields($id, 1);
            $this->db->delete('cberp_notes', array('fid' => $id, 'rid' => 1));
            //docs
            $this->db->select('filename');
            $this->db->from('cberp_documents');
            $this->db->where('id', $id);
            $query = $this->db->get();
            $result = $query->row_array();
            if ($this->db->delete('cberp_documents', array('fid' => $id, 'rid' => 1))) {
                @unlink(FCPATH . 'userfiles/documents/' . $result['filename']);
                $this->aauth->applog("[Client Doc Deleted]  DocId $id CID " . $id, $this->aauth->get_user()->username);
                //docs

            }
            return true;
        }

    }


    //transtables

    function trans_table($id)
    {
        $this->_get_trans_table_query($id);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }


    private function _get_trans_table_query($id)
    {

        $this->db->from('cberp_transactions');
        $this->db->where('payerid', $id);
        $this->db->where('ext', 0);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        $i = 0;
        foreach ($this->trans_column_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->trans_column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) // here order processing
        {
            $this->db->order_by($this->trans_column_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->transorder)) {
            $transorder = $this->transorder;
            $this->db->order_by(key($transorder), $transorder[key($transorder)]);
        }
    }

    function trans_count_filtered($id = '')
    {
        $this->_get_trans_table_query($id);
        $query = $this->db->get();
        if ($id != '') {
            $this->db->where('payerid', $id);
        }
        return $query->num_rows($id = '');
    }

    public function trans_count_all($id = '')
    {
        $this->_get_trans_table_query($id);
        $query = $this->db->get();
        if ($id != '') {
            $this->db->where('payerid', $id);
        }
    }

    private function _inv_datatables_query($id, $tyd = 0)
    {
        $this->db->select('cberp_invoices.*');
        $this->db->from('cberp_invoices');
        $this->db->where('cberp_invoices.customer_id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_invoices.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_invoices.loc', 0); tid
        // }

        if ($tyd) $this->db->where('cberp_invoices.i_class>', 1);
        $this->db->join('cberp_customers', 'cberp_invoices.customer_id=cberp_customers.customer_id', 'left');

        $i = 0;

        foreach ($this->inv_column_search as $item) // loop column
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

                if (count($this->inv_column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->inv_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->inv_order)) {
            $order = $this->inv_order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function inv_datatables($id, $tyd = 0)
    {
        $this->_inv_datatables_query($id, $tyd);

        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function inv_count_filtered($id)
    {
        $this->_inv_datatables_query($id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function inv_count_all($id)
    {
        $this->db->from('cberp_invoices');
        $this->db->where('customer_id', $id);
        return $this->db->count_all_results();
    }


    private function _qto_datatables_query($id, $tyd = 0)
    {
        $this->db->select('cberp_quotes.*');
        $this->db->from('cberp_quotes');
        $this->db->where('cberp_quotes.customer_id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_quotes.loc', 0);
        // }
        $this->db->join('cberp_customers', 'cberp_quotes.customer_id=cberp_customers.customer_id', 'left');

        $i = 0;

        foreach ($this->inv_column_search as $item) // loop column
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

                if (count($this->inv_column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->qto_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->qto_order)) {
            $order = $this->qto_order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        else{
            $this->db->order_by('cberp_quotes.quote_number' , 'desc');
        }
    }

    function qto_datatables($id, $tyd = 0)
    {
        $this->_qto_datatables_query($id);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function qto_count_filtered($id)
    {
        $this->_qto_datatables_query($id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function qto_count_all($id)
    {
        $this->db->from('cberp_quotes');
        $this->db->where('customer_id', $id);
        return $this->db->count_all_results();
    }

    public function group_info($id)
    {

        $this->db->from('cberp_cust_group');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function activity($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_metadata');
        // $this->db->where('type', 21);
        $this->db->where('rid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function recharge($id, $amount)
    {

        $this->db->set('balance', "balance+$amount", FALSE);
        $this->db->where('id', $id);

        $this->db->update('cberp_customers');

        $data = array(
            'type' => 21,
            'rid' => $id,
            'col1' => $amount,
            'col2' => date('Y-m-d H:i:s') . ' Account Recharge by ' . $this->aauth->get_user()->username
        );


        if ($this->db->insert('cberp_metadata', $data)) {
            $this->aauth->applog("[Client Wallet Recharge] Amt-$amount ID " . $id, $this->aauth->get_user()->username);
            return true;
        } else {
            return false;
        }

    }

    private function _project_datatables_query($cday = '')
    {
        $this->db->select("cberp_projects.*,cberp_customers.name AS customer");
        $this->db->from('cberp_projects');
        $this->db->join('cberp_customers', 'cberp_projects.cid = cberp_customers.customer_id', 'left');
        $this->db->where('cberp_projects.cid=', $cday);
        $i = 0;

        foreach ($this->pcolumn_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->pcolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) {
            $this->db->order_by($this->column_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->porder)) {
            $order = $this->porder;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function project_datatables($cday = '')
    {


        $this->_project_datatables_query($cday);

        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function project_count_filtered($cday = '')
    {
        $this->_project_datatables_query($cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function project_count_all($cday = '')
    {
        $this->_project_datatables_query($cday);
        $query = $this->db->get();
        return $query->num_rows();
    }

    //notes

    private function _notes_datatables_query($id)
    {

        $this->db->from('cberp_notes');
        $this->db->where('fid', $id);
        $this->db->where('ntype', 1);
        $i = 0;

        foreach ($this->notecolumn_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) {
            $this->db->order_by($this->notecolumn_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->note_order)) {
            $order = $this->note_order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function notes_datatables($id)
    {
        $this->_notes_datatables_query($id);
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function notes_count_filtered($id)
    {
        $this->_notes_datatables_query($id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function notes_count_all($id)
    {
        $this->_notes_datatables_query($id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function editnote($id, $title, $content, $cid)
    {

        $data = array('title' => $title, 'content' => $content, 'last_edit' => date('Y-m-d H:i:s'));


        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->where('fid', $cid);


        if ($this->db->update('cberp_notes')) {
            $this->aauth->applog("[Client Note Edited]  NoteId $id CID " . $cid, $this->aauth->get_user()->username);
            return true;
        } else {
            return false;
        }

    }

    public function note_v($id, $cid)
    {
        $this->db->select('*');
        $this->db->from('cberp_notes');
        $this->db->where('id', $id);
        $this->db->where('fid', $cid);
        $query = $this->db->get();
        return $query->row_array();
    }

    function addnote($title, $content, $cid)
    {
        $this->aauth->applog("[Client Note Added]  NoteId $title CID " . $cid, $this->aauth->get_user()->username);
        $data = array('title' => $title, 'content' => $content, 'cdate' => date('Y-m-d'), 'last_edit' => date('Y-m-d H:i:s'), 'cid' => $this->aauth->get_user()->id, 'fid' => $cid, 'rid' => 1, 'ntype' => 1);
        return $this->db->insert('cberp_notes', $data);

    }

    function deletenote($id, $cid)
    {
        $this->aauth->applog("[Client Note Deleted]  NoteId $id CID " . $cid, $this->aauth->get_user()->username);
        return $this->db->delete('cberp_notes', array('id' => $id, 'fid' => $cid, 'rid' => 1));

    }

    //documents list

    var $doccolumn_order = array(null, 'title', 'cdate', null);
    var $doccolumn_search = array('title', 'cdate');

    public function documentlist($cid)
    {
        $this->db->select('*');
        $this->db->from('cberp_documents');
        $this->db->where('fid', $cid);
        $this->db->where('rid', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    function adddocument($title, $filename, $cid)
    {
        $this->aauth->applog("[Client Doc Added]  DocId $title CID " . $cid, $this->aauth->get_user()->username);
        $data = array('title' => $title, 'filename' => $filename, 'cdate' => date('Y-m-d'), 'cid' => $this->aauth->get_user()->id, 'fid' => $cid, 'rid' => 1);
        return $this->db->insert('cberp_documents', $data);

    }

    function deletedocument($id, $cid)
    {
        $this->db->select('filename');
        $this->db->from('cberp_documents');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $this->db->trans_start();
        if ($this->db->delete('cberp_documents', array('id' => $id, 'fid' => $cid, 'rid' => 1))) {
            if (@unlink(FCPATH . 'userfiles/documents/' . $result['filename'])) {
                $this->aauth->applog("[Client Doc Deleted]  DocId $id CID " . $cid, $this->aauth->get_user()->username);
                $this->db->trans_complete();
                return true;
            } else {
                $this->db->trans_rollback();
                return false;
            }

        } else {
            return false;
        }
    }


    function document_datatables($cid)
    {
        $this->document_datatables_query($cid);
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    private function document_datatables_query($cid)
    {

        $this->db->from('cberp_documents');
        $this->db->where('fid', $cid);
        $this->db->where('rid', 1);
        $i = 0;

        foreach ($this->doccolumn_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->doccolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) {
            $this->db->order_by($this->doccolumn_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function document_count_filtered($cid)
    {
        $this->document_datatables_query($cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function document_count_all($cid)
    {
        $this->document_datatables_query($cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function send_mail_auto($email, $name, $password)
    {
        $this->load->library('parser');
        $this->load->model('templates_model', 'templates');
        $template = $this->templates->template_info(16);

        $data = array(
            'Company' => $this->config->item('ctitle'),
            'NAME' => $name
        );
        $subject = $this->parser->parse_string($template['key1'], $data, TRUE);

        $data = array(
            'Company' => $this->config->item('ctitle'),
            'NAME' => $name,
            'EMAIL' => $email,
            'URL' => base_url() . 'crm',
            'PASSWORD' => $password,
            'CompanyDetails' => '<h6><strong>' . $this->config->item('ctitle') . ',</strong></h6>
            <address>' . $this->config->item('address') . '<br>' . $this->config->item('address2') . '</address>
             ' . $this->lang->line('Phone') . ' : ' . $this->config->item('phone') . '<br>  ' . $this->lang->line('Email') . ' : ' . $this->config->item('email'),


        );
        $message = $this->parser->parse_string($template['other'], $data, TRUE);


        return array('subject' => $subject, 'message' => $message);
    }


    public function recipients($ids)
    {

        $this->db->select('id,name,email,phone');
        $this->db->from('cberp_customers');
        $this->db->where_in('id', $ids);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function sales_due($sdate, $edate, $csd, $trans_type, $pay = true, $amount = 0, $acc = 0, $pay_method = '',$note='')
    {
        if ($pay) {
            $this->db->select_sum('total');
            $this->db->select_sum('pamnt');
            $this->db->from('cberp_invoices');
            $this->db->where('DATE(invoicedate) >=', $sdate);
            $this->db->where('DATE(invoicedate) <=', $edate);
            $this->db->where('csd', $csd);
            $this->db->where('status', $trans_type);
            // if ($this->aauth->get_user()->loc) {
            //     $this->db->where('loc', $this->aauth->get_user()->loc);
            // } elseif (!BDATA) {
            //     $this->db->where('loc', 0);
            // }

            $query = $this->db->get();
            $result = $query->row_array();
            return $result;
        } else {
            if ($amount) {
                $this->db->select('id,tid,total,pamnt');
                $this->db->from('cberp_invoices');
                $this->db->where('DATE(invoicedate) >=', $sdate);
                $this->db->where('DATE(invoicedate) <=', $edate);
                $this->db->where('csd', $csd);
                $this->db->where('status', $trans_type);
                // if ($this->aauth->get_user()->loc) {
                //     $this->db->where('loc', $this->aauth->get_user()->loc);
                // } elseif (!BDATA) {
                //     $this->db->where('loc', 0);
                // }

                $query = $this->db->get();
                $result = $query->result_array();
                $amount_custom = $amount;

                foreach ($result as $row) {
                    $note.=' #'.$row['tid'];
                    $due = $row['total'] - $row['pamnt'];
                    if ($amount_custom >= $due) {
                        $this->db->set('status', 'paid');
                        $this->db->set('pamnt', "pamnt+$due", FALSE);
                        $amount_custom = $amount_custom - $due;
                    } elseif ($amount_custom > 0 AND $amount_custom < $due) {
                        $this->db->set('status', 'partial');
                        $this->db->set('pamnt', "pamnt+$amount_custom", FALSE);
                        $amount_custom = 0;
                    }

                    $this->db->set('pmethod', $pay_method);
                    $this->db->where('id', $row['id']);
                    $this->db->update('cberp_invoices');

                    if ($amount_custom == 0) break;

                }
                  $this->db->select('id,holder');
                    $this->db->from('cberp_accounts');
                    $this->db->where('id', $acc);
                    $query = $this->db->get();
                    $account = $query->row_array();

                                    $data = array(
                        'acid' => $account['id'],
                        'account' => $account['holder'],
                        'type' => 'Income',
                        'cat' => 'Sales',
                        'credit' => $amount,
                        'payer' => $this->lang->line('Bulk Payment Invoices'),
                        'payerid' => $csd,
                        'method' => $pay_method,
                        'date' => date('Y-m-d'),
                        'eid' => $this->aauth->get_user()->id,
                        'tid' => 0,
                        'note' => $note,
                        'loc' => $this->aauth->get_user()->loc
                    );

                    $this->db->insert('cberp_transactions', $data);
                    $tttid = $this->db->insert_id();
		            $this->db->set('lastbal', "lastbal+$amount", FALSE);
                    $this->db->where('id', $account['id']);
                    $this->db->update('cberp_accounts');

            }

        }
    }
    public function customerByTid($id)
    {

        $this->db->select('cberp_customers.*');
        $this->db->from('cberp_customers');        
        $this->db->join('cberp_sales_orders', 'cberp_sales_orders.csd = cberp_customers.customer_id', 'left');
        $this->db->where('cberp_sales_orders.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    
    public function customer_salesman($customer_id){
        $this->db->select('cberp_custom_data.data as salesman');
        $this->db->from('cberp_customers');
        $this->db->join('cberp_custom_data', 'cberp_custom_data.rid = cberp_customers.customer_id');
        $this->db->where('cberp_customers.customer_id', $customer_id);
        $this->db->where('cberp_custom_data.module', 1);
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        } else { 
            $row = "";
        }
        return $row;
    }    
    public function customer_details_byid($custid)
    {
        $this->db->select('cberp_customers.address');
        $this->db->from('cberp_customers');
        $this->db->where('cberp_customers.customer_id', $custid);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row_array();
    }
    public function customer_credit_limit($custid)
    {
        $this->db->select('cberp_customers.credit_limit,cberp_customers.avalable_credit_limit');
        $this->db->from('cberp_customers');
        $this->db->where('cberp_customers.customer_id', $custid);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function saleman_list()
    {
        $this->db->select('cberp_employees.id,cberp_employees.name');
        $this->db->from('cberp_employees');
        $this->db->join('cberp_users', 'cberp_users.id = cberp_employees.id');
        $this->db->where('cberp_users.roleid', '6');
        $this->db->order_by('name','ASC');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }
    public function min_max_creditlimit()
    {
        $this->db->select('MIN(credit_limit) AS minimum, MAX(credit_limit) AS maximum');
        $query = $this->db->get('cberp_customers');
        return $query->row_array();
    }
    public function gethistory($custid)
    {
        $this->db->select('cberp_customers_log.*,cberp_employees.name');
        $this->db->from('cberp_customers_log');  
        $this->db->join('cberp_employees','cberp_customers_log.performed_by=cberp_employees.id');
        $this->db->where('cberp_customers_log.customer_id',$custid);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function set_permissions()
    {
        $this->db->select('cberp_customers_log.*,cberp_employees.name');
        $this->db->from('cberp_customers_log');  
        $this->db->join('cberp_employees','cberp_customers_log.performed_by=cberp_employees.id');
        $this->db->where('cberp_customers_log.customer_id',$custid);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function load_permissions($main_menu,$submenu1,$submenu2,$menu_detail="")
    {
        $this->db->select('function');
        $this->db->from('cberp_menu_details');  
        $this->db->join('cberp_user_menu_links','cberp_user_menu_links.menu_link_id=cberp_menu_details.menu_id');
        $this->db->where('main_menu',$main_menu);
        $this->db->where('submenu1',$submenu1);
        $this->db->where('submenu2',$submenu2);
        if($menu_detail)
        {
            $this->db->where('menu_detail',$menu_detail);
        }
        $this->db->where('user_id',$this->session->userdata('id'));
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


     public function pos_add($name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox, $language = '', $create_login = true, $password = '', $document_id = '', $custom = '', $discount = 0)
    {
        $this->db->select('email');
        $this->db->from('cberp_customers');
        $this->db->where('email', $email);
        $query = $this->db->get();
        $valid = $query->row_array();
        if (!$valid['email']) {


            if (!$discount) {
                $this->db->select('disc_rate');
                $this->db->from('cberp_cust_group');
                $this->db->where('id', $customergroup);
                $query = $this->db->get();
                $result = $query->row_array();
                $discount = $result['disc_rate'];
            }


            $data = array(
                'name' => $name,
                'company' => $company,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'city' => $city,
                'region' => $region,
                'country' => $country,
                'postbox' => $postbox,
                'customer_group_id' => $customergroup,
                'tax_id' => $tax_id,
                'shipping_name' => $shipping_name,
                'shipping_phone' => $shipping_phone,
                'shipping_email' => $shipping_email,
                'shipping_address_1' => $shipping_address_1,
                'shipping_city' => $shipping_city,
                'shipping_region' => $shipping_region,
                'shipping_country' => $shipping_country,
                'shipping_postbox' => $shipping_postbox,
                'document_id' => $document_id,
                'custom1' => $custom,
                'discount' => $discount
            );


            if ($this->aauth->get_user()->loc) {
                $data['loc'] = $this->aauth->get_user()->loc;
            }
 
            if ($this->db->insert('cberp_customers', $data)) {
                $cid = $this->db->insert_id();
                $p_string = '';
                $temp_password = '';
                if ($create_login) {

                    if ($password) {
                        $temp_password = $password;
                    } else {
                        $temp_password = rand(200000, 999999);
                    }

                    $pass = password_hash($temp_password, PASSWORD_DEFAULT);
                    $data = array(
                        'user_id' => 1,
                        'status' => 'active',
                        'is_deleted' => 0,
                        'name' => $name,
                        'password' => $pass,
                        'email' => $email,
                        'user_type' => 'Member',
                        'cid' => $cid,
                        'lang' => $language
                    );

                    $this->db->insert('users', $data);
                    $p_string = ' Temporary Password is ' . $temp_password . ' ';
                }
                $this->aauth->applog("[Client Added] $name ID " . $cid, $this->aauth->get_user()->username);
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') . $p_string . '&nbsp;<a href="' . base_url('customers/view?id=' . $cid) . '" class="btn btn-info btn-sm"><span class="icon-eye"></span>' . $this->lang->line('View') . '</a>', 'cid' => $cid, 'pass' => $temp_password, 'discount' => amountFormat_general($discount)));

                $this->custom->save_fields_data($cid, 1);

                $this->db->select('other');
                $this->db->from('univarsal_api');
                $this->db->where('id', 64);
                $query = $this->db->get();
                $othe = $query->row_array();

                if ($othe['other']) {
                    $auto_mail = $this->send_mail_auto($email, $name, $temp_password);
                    $this->load->model('communication_model');
                    $attachmenttrue = false;
                    $attachment = '';
                    $this->communication_model->send_corn_email($email, $name, $auto_mail['subject'], $auto_mail['message'], $attachmenttrue, $attachment);
                }

            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                'Duplicate Email'));
        }

    }


    public function pos_edit($id, $name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox, $document_id = '', $custom = '', $language = '', $discount = 0)
    {
        $data = array(
            'name' => $name,
            'company' => $company,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'city' => $city,
            'region' => $region,
            'country' => $country,
            'postbox' => $postbox,
            'customer_group_id' => $customergroup,
            'tax_id' => $tax_id,
            'shipping_name' => $shipping_name,
            'shipping_phone' => $shipping_phone,
            'shipping_email' => $shipping_email,
            'shipping_address_1' => $shipping_address_1,
            'shipping_city' => $shipping_city,
            'shipping_region' => $shipping_region,
            'shipping_country' => $shipping_country,
            'shipping_postbox' => $shipping_postbox,
            'document_id' => $document_id,
            'custom1' => $custom,
            'discount' => $discount
        );


        $this->db->set($data);
        $this->db->where('id', $id);
        if ($this->aauth->get_user()->loc) {
            $this->db->where('loc', $this->aauth->get_user()->loc);
        } elseif (!BDATA) {
            $this->db->where('loc', 0);
        }

        if ($this->db->update('cberp_customers')) {
            $data = array(
                'name' => $name,
                'email' => $email,
                'lang' => $language
            );
            $this->db->set($data);
            $this->db->where('cid', $id);
            $this->db->update('users');
            $this->aauth->applog("[Client Updated] $name ID " . $id, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));

            $this->custom->edit_save_fields_data($id, 1);
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

 
}
