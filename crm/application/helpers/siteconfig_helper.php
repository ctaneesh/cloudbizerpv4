<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function sitezconfig($input)
{
    //get main CodeIgniter object
    $ci =& get_instance();
    $ci->load->database();

    //get data from database
    $query = $ci->db->query("SELECT * FROM cberp_system WHERE id=1 LIMIT 1");
    $row = $query->row_array();
    if (@$row["$input"]) {
        return $row["$input"];
    } else {
        return NULL;
    }
}

function set_default_timezone_set()
{
    $ci =& get_instance();
    $ci->load->database();
    $query = $ci->db->query("SELECT zone FROM cberp_system LIMIT 1");

    if ($query->num_rows() > 0) {
        $row = $query->row_array();
        $timezone = $row['zone'];

        if (!empty($timezone)) {
            date_default_timezone_set($timezone);
        }
    } else {
        // Fallback to UTC if no timezone found
        date_default_timezone_set('UTC');
    }
}


function assets_url($input='')
{
    return base_url($input);
}

function dateformat($input)
{
    $ci =& get_instance();
    $date = new DateTime($input);
    $date = $date->format($ci->config->item('dformat'));
    return $date;
}
function dateformat_time($input)
{
    $ci =& get_instance();
    $date = new DateTime($input ?? '');
    $date = $date->format($ci->config->item('dformat') . ' H:i:s');
    return $date;
}
function datefordatabase($input)
{
    $ci =& get_instance();
    $date = new DateTime($input);
    $date = $date->format('Y-m-d H:i:s');
    return $date;
}

function user_role($id = 5)
{ $ci =& get_instance();
    switch ($id) {
        case 5:
            return $ci->lang->line('Business Owner');
            break;
        case 4:
            return $ci->lang->line('Business Manager');
            break;
        case 3:
            return $ci->lang->line('Sales Manager');
            break;
        case 2:
            return $ci->lang->line('Sales Person');
            break;
        case 1:
            return $ci->lang->line('Inventory Manager');
            break;
        case -1:
            return $ci->lang->line('Project Manager');
            break;

    }
}

function amountFormat($number)
{
    $ci =& get_instance();
    $ci->load->database();

    $query = $ci->db->query("SELECT currency FROM cberp_system WHERE id=1 LIMIT 1");
    $row = $query->row_array();
    $currency = $row['currency'];

    //get data from database
    $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
    $row = $query2->row_array();
    //Format money as per country

    if ($row['method'] == 'l') {
        return $currency . ' ' . @number_format($number, $row['url'], $row['key1'], $row['key2']);
    } else {
        return @number_format($number, $row['url'], $row['key1'], $row['key2']) . ' ' . $currency;
    }

}

function appset()
{
    $ci =& get_instance();
    $ci->load->database();
    $query = $ci->db->query("SELECT * FROM cberp_system WHERE id=1 LIMIT 1");
    $row = $query->row_array();
    $this->config->set_item('ctitle', $row["cname"]);
    $this->config->set_item('address', $row["address"]);
    $this->config->set_item('city', $row["city"]);
    $this->config->set_item('region', $row["region"]);
    $this->config->set_item('country', $row["country"]);
    $this->config->set_item('phone', $row["phone"]);
    $this->config->set_item('email', $row["email"]);
    $this->config->set_item('tax', $row["tax"]);
    $this->config->set_item('taxno', $row["tax_id"]);
    $this->config->set_item('currency', $row["currency"]);
    $this->config->set_item('format_curr', $row["currency_format"]);
    $this->config->set_item('prefix', $row["prefix"]);
    // $this->config->set_item('date_f',$row["dfomat"]);
    $this->config->set_item('tzone', $row["zone"]);
    $this->config->set_item('logo', $row["logo"]);


    switch ($row['dformat']) {
        case 1:
            $this->config->set_item('date', date("d-m-Y"));
            $this->config->set_item('dformat', "d-m-Y");
            $this->config->set_item('dformat2', "dd-mm-yy");
            break;
        case 2:
            $this->config->set_item('date', date("Y-m-d"));
            $this->config->set_item('dformat', "Y-m-d");
            $this->config->set_item('dformat2', "yy-mm-dd");
            break;
        case 3:
            $this->config->set_item('date', date("Y-m-d"));
            $this->config->set_item('dformat', "Y-m-d");
            $this->config->set_item('dformat2', "yy-mm-dd");
            break;
    }


}

function amountFormat_s($number)
{
    $ci =& get_instance();
    $ci->load->database();

    //get data from database
    $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
    $row = $query2->row_array();
    //Format money as per country

    if ($row['method'] == 'l') {
        return @number_format($number, $row['url'], $row['key1'], $row['key2']);
    } else {
        return @number_format($number, $row['url'], $row['key1'], $row['key2']);
    }

}
function amountExchange($number, $id = 0, $loc = 0)
{
    $ci =& get_instance();
    $ci->load->database();
    if ($loc > 0 && $id == 0) {
        $query = $ci->db->query("SELECT cur FROM cberp_locations WHERE id='$loc' LIMIT 1");
        $row = $query->row_array();
        $id = $row['cur'];
    }
    if ($id > 0) {
        $query = $ci->db->query("SELECT * FROM cberp_currencies WHERE id='$id' LIMIT 1");
        $row = $query->row_array();
        $currency = $row['symbol'];
        $rate = $row['rate'];
        $thosand = $row['thous'];
        $dec_point = $row['dpoint'];
        $decimal_after = $row['decim'];
        $totalamount = $rate * $number;
        //get data from database
        //Format money as per country
        if ($row['cpos'] == 0) {
            return $currency . ' ' . @number_format($totalamount, $decimal_after, $dec_point, $thosand);
        } else {
            return @number_format($totalamount, $decimal_after, $dec_point, $thosand) . ' ' . $currency;
        }
    } else {

        $query = $ci->db->query("SELECT currency FROM cberp_system WHERE id=1 LIMIT 1");
        $row = $query->row_array();
        $currency = $row['currency'];

        //get data from database
        $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
        $row = $query2->row_array();
        //Format money as per country
        if ($row['method'] == 'l') {
            return $currency . ' ' . @number_format($number, $row['url'], $row['key1'], $row['key2']);
        } else {
            return @number_format($number, $row['url'], $row['key1'], $row['key2']) . ' ' . $currency;
        }
    }

}



function location($number=0)
{
    $ci =& get_instance();
    $ci->load->database();

        if ($number > 0) {
            $query2 = $ci->db->query("SELECT * FROM cberp_locations WHERE id=$number");
            return $query2->row_array();
        } else {
            $query2 = $ci->db->query("SELECT cname,address,city,region,country,postbox,phone,email,tax_id,logo FROM cberp_system WHERE id=1 LIMIT 1");
            return $query2->row_array();
        }

}

//copied files
function amountFormat_general($number=0)
{
    $ci =& get_instance();
    $ci->load->database();
    //get data from database
    $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
    $row = $query2->row_array();
    //Format money as per country
    $number = @number_format((float)$number, $row['url'], $row['key1'], '');
    return $number;
}

function amountExchange_s($number, $id = 0, $loc = 0)
{
    $ci =& get_instance();
    $ci->load->database();
    if ($loc > 0 && $id == 0) {
        $query = $ci->db->query("SELECT cur FROM cberp_locations WHERE id='$loc' LIMIT 1");
        $row = $query->row_array();
        $id = $row['cur'];
    }
    if ($id > 0) {
        $query = $ci->db->query("SELECT * FROM cberp_currencies WHERE id='$id' LIMIT 1");
        $row = $query->row_array();
        $rate = $row['rate'];
        $dec_point = $row['dpoint'];
        $totalamount = $rate * $number;
		$decimal_after = $row['decim'];
        $totalamount = number_format($totalamount, $decimal_after, $dec_point, '');
        return $totalamount;
    } else {
        $query = $ci->db->query("SELECT currency FROM cberp_system WHERE id=1 LIMIT 1");
        $row = $query->row_array();
        $currency = $row['currency'];
        //get data from database
        $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
        $row = $query2->row_array();
        $number = number_format($number, $row['url'], $row['key1'], '');
        return $number;
    }

}

function get_prefix()
 {  
    $ci =& get_instance();
    $ci->db->select('name AS quote_prefix, key1 AS po_prefix, key2 AS subscription_prefix, url AS stockreturn_prefix, method AS transaction_prefix, url AS purchasereturn_prefix, other AS other_prefix, suffix AS receipt_prefix');
    $ci->db->from('univarsal_api');
    $ci->db->where('id', 51);
    $query = $ci->db->get();
    return $query->row_array();

 }
 function get_prefix_72()
 {  
    $ci =& get_instance();
    $ci->db->select('name AS deliverynote_prefix, key1 AS deliveryreturn_prefix,  url AS invoicereturn_prefix, method AS lead_prefix,  other AS salesorder_prefix,suffix');
    $ci->db->from('univarsal_api');
    $ci->db->where('id', 72);
    $query = $ci->db->get();
    return $query->row_array();
 }

 function default_validity()
 {
     $ci =& get_instance();   
     $ci->db->select('*');
     $ci->db->from('cberp_default_validity');  
     $query = $ci->db->get();
     $result = $query->row_array();
     return $result;
 }

 
function get_module_details_by_name()
{
    $ci =& get_instance();   
    $ci->db->select('module_number');
    $ci->db->from('cberp_module_groups'); 
    $ci->db->where('module_name', 'Stock');
    $query = $ci->db->get();
    $result = $query->row_array();
    return $result['module_number'];
}

function linked_user_module_approvals_by_module_number($module_id,$user_id=""){
    $ci =& get_instance();
    $query = "SELECT  first_level_approval,second_level_approval,third_level_approval,user_id,module_id FROM cberp_menu_user_module_approval";
    $query .= " WHERE cberp_menu_user_module_approval.module_id = '$module_id'";
    if($user_id)
    {
        $query .= " AND cberp_menu_user_module_approval.user_id = '$user_id'";
    }
    $query1 = $ci->db->query($query);
    $result = $query1->result_array();
    return $result;
 }


 function send_message_to_users($users_list, $target_url, $message_caption, $message, $duedate)
{
    $ci =& get_instance();   
    $full_data = [];
    $i = 0;
    $flag = 0;
    $current_user_id = $ci->session->userdata('user_details')[0]->cid;
    $duedate = ($duedate) ? $duedate . " " . date('H:i:s') : date('Y-m-d H:i:s');

    if ($users_list) {
        foreach ($users_list as $user) {
            if ($user['user_id'] == $current_user_id) {
                $flag = 1;
            }
            $data = [
                'tdate' => date('Y-m-d'),
                'start' => date('Y-m-d'),
                'created_date_time' => date('Y-m-d H:i:s'),
                'name' => $message_caption,
                'status' => 'Due',
                'duedate' => $duedate,
                'eid' => $user['user_id'],
                'aid' => $current_user_id,
                'priority' => 'High',
                'related' => '0',
                'rid' => '0',
                'description' => $message,
                'target_url' => $target_url
            ];
            $full_data[$i++] = $data;
            $ci->db->insert('cberp_todolist', $data);
        }

        if ($flag == 0) {
            $data1 = [
                'tdate' => date('Y-m-d'),
                'start' => date('Y-m-d'),
                'created_date_time' => date('Y-m-d H:i:s'),
                'name' => $message_caption,
                'status' => 'Due',
                'duedate' => $duedate,
                'eid' => $current_user_id,
                'aid' => $current_user_id,
                'priority' => 'High',
                'related' => '0',
                'rid' => '0',
                'description' => $message,
                'target_url' => $target_url
            ];
            $full_data[$i] = $data1;
            $ci->db->insert('cberp_todolist', $data1);
        }
    }
}

    function detailed_log_history($pagename,$item_no,$action_nane,$changedFields)
    {
        $ci =& get_instance();
        // $changedFields = json_decode($changedFields, true); 

        $sequence_number = get_latest_sequence_number();
        if (!empty($changedFields)) {
            $data_history = [];
            foreach ($changedFields as $fieldId => $change) {
                $field_label = trim(str_replace('*','',$change['fieldlabel']));
                if($field_label=="Invoices Checkbox")
                {
                    continue;
                }
                $data_history[] = [
                    'field_name' => $fieldId,
                    'field_label' => $field_label,
                    'item_no' => $item_no,
                    'seqence_number' => $sequence_number,
                    'log_from' => $pagename,
                    'old_value' => $change['oldValue'],
                    'new_value' => $change['newValue'],
                    'changed_date' => date('Y-m-d H:i:s'),
                    'changed_by' => $ci->session->userdata('user_details')[0]->cid,
                    'ip_address' => getUserIpAddress(),
                    'action' => $action_nane
                ];
            }
            $ci->db->insert_batch('cberp_master_log', $data_history);
            // die( $ci->db->last_query());
        }
        else{
            $data_history[] = [
                'field_name' => $action_nane,
                'field_label' => $action_nane,
                'item_no' => $item_no,
                'seqence_number' => $sequence_number,
                'log_from' => $pagename,
                'old_value' => "",
                'new_value' => "",
                'changed_date' => date('Y-m-d H:i:s'),
                'changed_by' => $ci->session->userdata('user_details')[0]->cid,
                'ip_address' => getUserIpAddress(),
                'action' => $action_nane
            ];
            $ci->db->insert_batch('cberp_master_log', $data_history);
            // die( $ci->db->last_query());
        }
        return $sequence_number;

    }

    function getUserIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP address from shared internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP address passed from a proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // IP address from the remote address
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    
    
 
    function get_latest_sequence_number()
    {
        $ci =& get_instance();
        
        $ci->db->select('seqence_number');
        $ci->db->from('cberp_master_log'); 
        $ci->db->order_by('seqence_number', 'DESC');
        $ci->db->limit(1);
    
        $query = $ci->db->get();
    
        if ($query->num_rows() > 0) {
            $seqence_number = $query->row()->seqence_number+1;
        } else {
            $seqence_number = '1000';
        }
    
        return $seqence_number;
    }

    function check_permission() {
        $ci =& get_instance();   
        return  '<div class="no-permission"><h3>' . $ci->lang->line('Sorry') . '</h3></div>';   
    }