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

class Supplier_model extends CI_Model
{

    var $table = 'cberp_suppliers';
    var $column_order = array(null, 'name', 'address', 'email', 'phone', null);
    var $column_search = array('name', 'phone', 'address', 'city', 'email');
    var $trans_column_order = array('date', 'debit', 'credit', 'account', null);
    var $trans_column_search = array('id', 'date');
    var $inv_column_order = array(null, 'purchase_number', 'name', 'invoicedate', 'total', 'status', null);
    var $inv_column_search = array('purchase_number', 'name', 'invoicedate', 'total');
    var $order = array('supplier_id' => 'desc');
    var $inv_order = array('cberp_purchase_orders.purchase_number' => 'desc');

    public function supplier_lists()
    {
        $this->db->select('cberp_suppliers.supplier_id,cberp_suppliers.name');
        $this->db->from('cberp_suppliers');
        $query = $this->db->get();
        return $query->result_array();
    }


    private function _get_datatables_query($id = '')
    {

        $this->db->from($this->table);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
        // if ($id != '') {
        //     $this->db->where('gid', $id);
        // }
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
        $search = $this->input->post('order');
        if ($search) // here order processing
        {
            $this->db->order_by($this->column_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($id = '')
    {
        $this->_get_datatables_query($id);
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($id = '')
    {
        $this->_get_datatables_query();
        $query = $this->db->get();

        return $query->num_rows($id = '');
    }

    public function count_all($id = '')
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows($id = '');
    }

    public function details($supplier_id)
    {
        $this->db->select('cberp_suppliers.*,cberp_suppliers.supplier_id as supplierid,cberp_supplier_shipping.*,cberp_supplier_billing.*,cberp_country.name as countryname');
        $this->db->from($this->table);
        $this->db->join('cberp_supplier_shipping', 'cberp_suppliers.supplier_id=cberp_supplier_shipping.supplier_id');
        $this->db->join('cberp_supplier_billing', 'cberp_suppliers.supplier_id=cberp_supplier_billing.supplier_id');
        $this->db->join('cberp_country', 'cberp_country.id=cberp_suppliers.country', 'left');
        $this->db->where('cberp_suppliers.supplier_id', $supplier_id);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function money_details($custid)
    {

        $this->db->select('SUM(debit) AS debit,SUM(credit) AS credit');
        $this->db->from('cberp_transactions');
        $this->db->where('payerid', $custid);
        $this->db->where('ext', 1);
        $query = $this->db->get();
        return $query->row_array();
    }


    //erp2024 modified 03-06-2024 removed
    public function add($name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $tax_id, $contact_person, $land_line, $contact_phone1, $contact_phone2, $contact_email1, $contact_email2, $contact_designation, $website_url, $account_number, $account_holder, $bank_country, $bank_location)
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
            'tax_id' => $tax_id,
            // erp2024 new fields 03-06-2024
            'contact_person' => $contact_person,
            'land_line' => $land_line,
            'contact_phone1' => $contact_phone1,
            'contact_phone2' => $contact_phone2,
            'contact_email1' => $contact_email1,
            'contact_email2' => $contact_email2,
            'contact_designation' => $contact_designation,
            'website_url' => $website_url,
            'account_number' => $account_number,
            'account_holder' => $account_holder,
            'bank_country' => $bank_country,
            'bank_location' => $bank_location,
            // erp2024 new fields 03-06-2024

        );

        if ($this->aauth->get_user()->loc) {
            $data['loc'] = $this->aauth->get_user()->loc;
        }


        if ($this->db->insert('cberp_suppliers', $data)) {
            $cid = $this->db->insert_id();           
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED') . ' <a href="' . base_url('supplier/view?id=' . $cid) . '" class="btn btn-info btn-sm"><span class="icon-eye"></span> ' . $this->lang->line('View') . '</a>', 'cid' => $cid));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function edit($id, $name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $tax_id, $contact_person, $land_line, $contact_phone1, $contact_phone2, $contact_email1, $contact_email2, $contact_designation, $website_url, $account_number, $account_holder, $bank_country, $bank_location)
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
            'tax_id' => $tax_id,
            // erp2024 new fields 03-06-2024
            'contact_person' => $contact_person,
            'land_line' => $land_line,
            'contact_phone1' => $contact_phone1,
            'contact_phone2' => $contact_phone2,
            'contact_email1' => $contact_email1,
            'contact_email2' => $contact_email2,
            'contact_designation' => $contact_designation,
            'website_url' => $website_url,
            'account_number' => $account_number,
            'account_holder' => $account_holder,
            'bank_country' => $bank_country,
            'bank_location' => $bank_location,
            // erp2024 new fields 03-06-2024

        );


        $this->db->set($data);
        $this->db->where('supplier_id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }

        if ($this->db->update('cberp_suppliers')) {
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED') . ' <a href="' . base_url('supplier/view?id=' . $id) . '" class="btn btn-info btn-sm"><span class="icon-eye"></span> ' . $this->lang->line('View') . '</a>'));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }
    //erp2024 modified 03-06-2024 removed add,edit

    //erp2024 add_new insted of add 10-06-2024 starts lang
    public function  add_new($name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $language = '', $create_login = true, $password = '', $document_id = '', $custom = '', $discount = 0, $registration_number='', $expiry_date='', $computer_card_number='', $sponser_id='',  $credit_limit='', $credit_period='', $computer_card_image='', $sponser_image='', $profile_pic='', $contact_person='', $land_line='', $contact_phone1='', $contact_phone2='', $contact_email1='', $contact_email2='', $contact_designation='', $account_number='',  $account_holder='', $bank_country='', $bank_location='', $bank_name='', $supplierid)
    {
        


            // if (!$discount) {
            //     $this->db->select('disc_rate');
            //     $this->db->from('cberp_cust_group');
            //     $this->db->where('id', $customergroup);
            //     $query = $this->db->get();
            //     $result = $query->row_array();
            //     $discount = $result['disc_rate'];
            // }


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
                'tax_id' => $tax_id,
                'document_id' => $document_id,
                // 'custom1' => $custom,
                'discount' =>$discount,
                // erp2024 new fields 03-06-2024
                'registration_number' => $registration_number,
                'expiry_date' => $expiry_date,
                'computer_card_number' => $computer_card_number,
                'computer_card_image' => $computer_card_image,
                'sponser_id' => $sponser_id,
                'sponser_image' => $sponser_image,
                'credit_limit' => $credit_limit,
                'picture' => $profile_pic,
                'credit_period' => $credit_period,
                'contact_person' => $contact_person,
                'land_line' => $land_line,
                'contact_phone1' => $contact_phone1,
                'contact_phone2' => $contact_phone2,
                'contact_email1' => $contact_email1,
                'contact_email2' => $contact_email2,
                'contact_designation' => $contact_designation,
                'account_number' => $account_number,
                'account_holder' => $account_holder,
                'bank_country' => $bank_country,
                'bank_location' => $bank_location,
                'bank_name' => $bank_name,
                'created_date' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('id')
                
                // erp2024 new fields 03-06-2024
            );

            if($supplierid)
            {
                $this->db->update('cberp_suppliers', $data,['supplier_id'=>$supplierid]);
                return $supplierid;
            }
            else{
                $this->db->insert('cberp_suppliers', $data);
                return $this->db->insert_id();
            }
            
               
       

    }

    public function edit_supplier($id, $name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox, $document_id = '', $custom = '', $language = '', $discount = 0,$registration_number= '', $expiry_date= '', $computer_card_number= '', $sponser_id= '',  $credit_limit= '', $credit_period= '', $computer_card_image= '', $sponser_image= '', $contact_person= '', $land_line= '', $contact_phone1= '', $contact_phone2= '', $contact_email1= '', $contact_email2= '', $contact_designation='', $account_number='',  $account_holder='', $bank_country='', $bank_location='', $bank_name='')
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
            'discount' => $discount,
            // erp2024 new fields 10-06-2024 
            'registration_number' => $registration_number,
            'expiry_date' => $expiry_date,
            'computer_card_number' => $computer_card_number,            
            'sponser_id' => $sponser_id,
            'credit_limit' => $credit_limit,
            'credit_period' => $credit_period,
            'contact_person' => $contact_person,
            'land_line' => $land_line,
            'contact_phone1' => $contact_phone1,
            'contact_phone2' => $contact_phone2,
            'contact_email1' => $contact_email1,
            'contact_email2' => $contact_email2,
            'contact_designation' => $contact_designation,
            'account_number' => $account_number,
            'account_holder' => $account_holder,
            'bank_country' => $bank_country,
            'bank_location' => $bank_location,
            'bank_name' => $bank_name,
            'updated_date' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('id')
            // erp2024 new fields 10-06-2024
        );
        if(!empty($computer_card_image)){
            $data['computer_card_image'] = $computer_card_image;
        }
        if(!empty($sponser_image)){
            $data['sponser_image'] = $sponser_image;
        }
        $this->db->set($data);
        $this->db->where('supplier_id', $id);
        $this->db->update('cberp_suppliers');
        if ($this->db->update('cberp_suppliers')) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('UPDATED') . ' <a href="' . base_url('supplier/view?supplier_id=' . $id) . '" class="btn btn-info btn-sm"><span class="icon-eye"></span> ' . $this->lang->line('View') . '</a>'));
        } else {
            return json_encode(array('status' => 'Error', 'message' =>$this->lang->line('ERROR')));
        }



    }
    //erp2024 add_new insted of add 10-06-2024 ends

    public function editpicture($id, $pic)
    {
        $this->db->select('picture');
        $this->db->from($this->table);
        $this->db->where('supplier_id', $id);

        $query = $this->db->get();
        $result = $query->row_array();


        $data = array(
            'picture' => $pic
        );


        $this->db->set($data);
        $this->db->where('supplier_id', $id);
        if ($this->db->update('cberp_suppliers')) {

            unlink(FCPATH . 'userfiles/supplier/' . $result['picture']);
            unlink(FCPATH . 'userfiles/supplier/thumbnail/' . $result['picture']);
        }


    }

    public function group_list()
    {
        $query = $this->db->query("SELECT c.*,p.pc FROM cberp_cust_group AS c LEFT JOIN ( SELECT gid,COUNT(gid) AS pc FROM cberp_suppliers GROUP BY gid) AS p ON p.gid=c.id");
        return $query->result_array();
    }

    public function delete($id)
    {

        return $this->db->delete('cberp_suppliers', array('supplier_id' => $id));
    }


    //transtables

    function trans_table($id)
    {
        $this->_get_trans_table_query($id);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    private function _get_trans_table_query($id)
    {

        $this->db->from('cberp_transactions');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }

        $this->db->where('payerid', $id);
        $this->db->where('ext', 1);

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
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function trans_count_filtered($id = '')
    {
        $this->_get_trans_table_query($id);
        $query = $this->db->get();
        if ($id != '') {
            $this->db->where('payerid', $id);
        }
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('loc', 0);
        // }
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

    private function _inv_datatables_query($id)
    {
        $this->db->select('cberp_purchase_orders.*');
        $this->db->from('cberp_purchase_orders');
        $this->db->where('cberp_purchase_orders.customer_id', $id);
        $this->db->join('cberp_suppliers', 'cberp_purchase_orders.customer_id=cberp_suppliers.supplier_id', 'left');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_purchase_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_purchase_orders.loc', 0);
        // }
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

    function inv_datatables($id)
    {
        $this->_inv_datatables_query($id);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function inv_count_filtered($id)
    {
        $this->_inv_datatables_query($id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_purchase_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_purchase_orders.loc', 0);
        // }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function inv_count_all($id)
    {
        $this->db->from('cberp_purchase_orders');
        $this->db->where('csd', $id);
        return $this->db->count_all_results();
    }

    public function group_info($id)
    {

        $this->db->from('cberp_cust_group');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function sales_due($sdate, $edate, $csd, $trans_type, $pay = true, $amount = 0, $acc = 0, $pay_method = '', $note = '')
    {
        if ($pay) {
            $this->db->select_sum('total');
            $this->db->select_sum('pamnt');
            $this->db->from('cberp_purchase_orders');
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
                $this->db->from('cberp_purchase_orders');
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
                    $note .= ' #' . $row['tid'];
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
                    $this->db->update('cberp_purchase_orders');

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
                    'debit' => $amount,
                    'payer' => $this->lang->line('Bulk Payment'),
                    'payerid' => $csd,
                    'method' => $pay_method,
                    'date' => date('Y-m-d'),
                    'eid' => $this->aauth->get_user()->id,
                    'tid' => 0,
                    'ext' => 1,
                    'note' => $note,
                    'loc' => $this->aauth->get_user()->loc
                );

                $this->db->insert('cberp_transactions', $data);
                $tttid = $this->db->insert_id();
                $this->db->set('lastbal', "lastbal-$amount", FALSE);
                $this->db->where('id', $account['id']);
                $this->db->update('cberp_accounts');

            }

        }
    }
    public function supplier_details_byid($suppid)
    {
        $this->db->select('cberp_suppliers.address');
        $this->db->from('cberp_suppliers');
        $this->db->where('cberp_suppliers.supplier_id', $suppid);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row_array();
    }
    public function gethistory($custid)
    {
        $this->db->select('cberp_supplier_log.*,cberp_employees.name');
        $this->db->from('cberp_supplier_log');  
        $this->db->join('cberp_employees',' cberp_supplier_log.performed_by=cberp_employees.id');
        $this->db->where('cberp_supplier_log.supplier_id',$custid);
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

}
