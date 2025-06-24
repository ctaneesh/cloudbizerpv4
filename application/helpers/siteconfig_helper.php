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


if (!defined('BASEPATH')) exit('No direct script access allowed');


function dateformat($input)
{
    $ci =& get_instance();
    $date = new DateTime($input ?? '');
    $date = $date->format($ci->config->item('dformat'));
    return $date;
}

function assets_url($input = '')
{
    return base_url($input);
}

function dateformat_time($input)
{
    $ci =& get_instance();
    $date = new DateTime($input ?? '');
    $date = $date->format($ci->config->item('dformat') . ' H:i:s');
    return $date;
}

function dateformat_ymd($input)
{
    $date = new DateTime($input ?? '');
    $date = $date->format('Y-m-d');
    return $date;
}
function datefordatabase($input)
{
    $date = new DateTime($input ?? '');
    $date = $date->format('Y-m-d H:i:s');
    return $date;
}

function timefordatabase($input)
{

    $time = new DateTime($input ?? '');
    $time = $time->format('H:i:s');
    return $time;
}

function user_role($id)
{
    $ci =& get_instance();
    $ci->db->select('role_name');
    $ci->db->from('cberp_roles');
    $ci->db->where('id', $id);
    $query = $ci->db->get();
    return ($query->num_rows() > 0) ? $query->row()->role_name : "";
    // switch ($id) {
    //     case 6:
    //         return $ci->lang->line('Sales Man');
    //         break;
    //     case 5:
    //         return $ci->lang->line('Business Owner');
    //         break;
    //     case 4:
    //         return $ci->lang->line('Business Manager');
    //         break;
    //     case 3:
    //         return $ci->lang->line('Sales Manager');
    //         break;
    //     case 2:
    //         return $ci->lang->line('Sales Person');
    //         break;
    //     case 1:
    //         return $ci->lang->line('Inventory Manager');
    //         break;
    //     case -1:
    //         return $ci->lang->line('Project Manager');
    //         break;
    // }
}

function amountFormat($number)
{
    $ci =& get_instance();
    $query = $ci->db->query("SELECT currency FROM cberp_system WHERE id=1 LIMIT 1");
    $row = $query->row_array();
    $currency = $row['currency'];
    //get data from database
    $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
    $row = $query2->row_array();
    //Format money as per country
    if ($row['method'] == 'l') {
        return $currency . ' ' . @number_format((float)$number, $row['url'], $row['key1'], $row['key2']);
    } else {
        return @number_format((float)$number, $row['url'], $row['key1'], $row['key2']) . ' ' . $currency;
    }

}

function prefix($number)
{
    $ci =& get_instance();
    $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=51 LIMIT 1");
    $row = $query2->row_array();
    //Format money as per country
    switch ($number) {
        case 1:
            return $row['name'];
            break;
        case 2:
            return $row['key1'];
            break;
        case 3:
            return $row['key2'];
            break;
        case 4:
            return $row['url'];
            break;
        case 5:
            return $row['method'];
            break;
        case 6:
            return $row['other'];
            break;
        case 7:
            $query2 = $ci->db->query("SELECT other FROM univarsal_api WHERE id=52 LIMIT 1");
            $row = $query2->row_array();
            return $row['other'];
            break;
    }
}

function user_premission($input1, $input2)
{
    if (hash_equals($input1, $input2)) {
        return true;
    } else {
        return false;
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

    return @number_format((float)$number, $row['url'], $row['key1'], $row['key2']);

}

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

function numberClean($number)
{
    $ci =& get_instance();
    $ci->load->database();
    $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
    $row = $query2->row_array();
    $number = str_replace($row['key2'], "", $number ?? '');
    $number = str_replace($row['key1'], ".", $number);
    return (float)$number;
}

function removeCommaAndGetNumber($number) {
    if (strpos($number, ',') !== false) {
        $number = str_replace(',', '', $number);
    }
    return floatval($number);
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
            return $currency . ' ' . @number_format((float)$number, $row['url'], $row['key1'], $row['key2']);
        } else {
            return @number_format((float)$number, $row['url'], $row['key1'], $row['key2']) . ' ' . $currency;
        }
    }

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

function edit_amountExchange_s($number, $id = 0, $loc = 0)
{
    $ci =& get_instance();
    $ci->load->database();
    if ($loc > 0) {
        $query = $ci->db->query("SELECT cur FROM cberp_locations WHERE id='$loc' LIMIT 1");
        $row = $query->row_array();
        $id = $row['cur'];
    }
    if ($id > 0) {
        $query = $ci->db->query("SELECT * FROM cberp_currencies WHERE id='$id' LIMIT 1");
        $row = $query->row_array();
        $rate = $row['rate'];
        $decimal_after = $row['decim'];
        $dec_point = $row['dpoint'];
        $number = str_replace($decimal_after, "", $number);
        $number = str_replace($dec_point, ".", $number);
        $totalamount = $rate * (float)$number;
        $totalamount = number_format($totalamount, $decimal_after, $dec_point, '');
        return $totalamount;
    } else {
        $query = $ci->db->query("SELECT currency FROM cberp_system WHERE id=1 LIMIT 1");
        $row = $query->row_array();
        $currency = $row['currency'];
        //get data from database
        $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
        $row = $query2->row_array();
       // $number = str_replace($row['key2'], "", $number);
        //$number = str_replace($row['key1'], ".", $number);
        $number = number_format((float)$number, $row['url'], $row['key1'], '');
        return $number;
    }

}

function rev_amountExchange_s($number, $id = 0, $loc = 0)
{
    $ci =& get_instance();
    $ci->load->database();
    $query2 = $ci->db->query("SELECT other FROM univarsal_api WHERE id=5 LIMIT 1");
    $row = $query2->row_array();
    $revers = $row['other'];

    if ($loc) {
        $query = $ci->db->query("SELECT cur FROM cberp_locations WHERE id='$loc' LIMIT 1");
        $row = $query->row_array();
        $lcid = $row['cur'];
        if ($lcid > 0) {
            $query = $ci->db->query("SELECT * FROM cberp_currencies WHERE id='$lcid' LIMIT 1");
            $row = $query->row_array();
			if($row['id']){
                $rate = $row['rate'];
                $number = str_replace($row['thous'], "", $number);
                $number = str_replace($row['dpoint'], ".", $number);                
                if($number > 0)
                {   
                    $number = (float)$number / $rate;
                }                
			}
			else {
        $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
        $row = $query2->row_array();
        $number = str_replace($row['key2'], "", $number);
        $number = str_replace($row['key1'], ".", $number);

    }
        } elseif ($id) {
            $query = $ci->db->query("SELECT * FROM cberp_currencies WHERE id='$id' LIMIT 1");
            $row = $query->row_array();
            $rate = $row['rate'];
            $number = str_replace($row['thous'], "", $number);
            $number = str_replace($row['dpoint'], ".", $number);
            if($number > 0)
            {
                $number = (float)$number / $rate;
            }
            
        }
		else {
        $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
        $row = $query2->row_array();
        $number = str_replace($row['key2'], "", $number);
        $number = str_replace($row['key1'], ".", $number);

    }
    } elseif ($id) {
        $query = $ci->db->query("SELECT * FROM cberp_currencies WHERE id='$id' LIMIT 1");
        $row = $query->row_array();
        $rate = $row['rate'];
        $number = str_replace($row['thous'], "", $number);
        $number = str_replace($row['dpoint'], ".", $number);
        if ((!$revers) && ($number >0) && ($rate>0)) {

            $number = (float)$number / $rate;
        }
    } else {
        $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
        $row = $query2->row_array();
        $number = str_replace($row['key2'], "", $number ?? '');
        $number = str_replace($row['key1'], ".", $number);

    }

    return (float)$number;
}

function rev_amountExchange($number, $id = 0)
{
    $ci =& get_instance();
    $query = $ci->db->query("SELECT other FROM univarsal_api WHERE id='5' LIMIT 1");
    $row = $query->row_array();
    $reverse = $row['other'];
    if ($reverse && $id > 0) {
        $query = $ci->db->query("SELECT rate FROM cberp_currencies WHERE id='$id' LIMIT 1");
        $row = $query->row_array();
        $rate = $row['rate'];
        if($number >0)
        {
            $totalamount = $number / $rate;
        }
        
        return $totalamount;
    } else {
        return $number;
    }
}

function array_compare()
{
    $criteriaNames = func_get_args();
    $compare = function ($first, $second) use ($criteriaNames) {
        while (!empty($criteriaNames)) {
            $criterion = array_shift($criteriaNames);
            $sortOrder = 1;
            if (is_array($criterion)) {
                $sortOrder = $criterion[1] == SORT_DESC ? -1 : 1;
                $criterion = $criterion[0];
            }
            if ($first[$criterion] < $second[$criterion]) {
                return -1 * $sortOrder;
            } else if ($first[$criterion] > $second[$criterion]) {
                return 1 * $sortOrder;
            }
        }
        return 0;
    };

    return $compare;
}

function locations()
{
    $ci =& get_instance();
    $ci->load->database();
    $query2 = $ci->db->query("SELECT * FROM cberp_locations");
    return $query2->result_array();
}

function location($number = 0)
{
    $ci =& get_instance();
    $ci->load->database();
    if ($number > 0) {
        $query2 = $ci->db->query("SELECT * FROM cberp_locations WHERE id=$number");
        return $query2->row_array();
    } else {
        $query2 = $ci->db->query("SELECT cname,address,city,region,country,postbox,phone,email,taxid,logo,foundation FROM cberp_system WHERE id=1 LIMIT 1");
        return $query2->row_array();
    }
}

function active($input1)
{

    $t_file = APPPATH . 'config' . DIRECTORY_SEPARATOR . 'lic.php';
    if (is_writeable($t_file)) {
        file_put_contents($t_file, $input1);
        $lc = file_get_contents($t_file);
        if (empty($lc)) {
            echo json_encode(array('status' => 'WError', 'message' => 'Server write permissions denied'));
        } else {
            if ($input1 == 2) {
                echo json_encode(array('status' => 'Error', 'message' => 'License error!'));
            } else {
                echo json_encode(array('status' => 'Success', 'message' => 'License updated!'));
            }
        }
    } else {
        echo json_encode(array('status' => 'WError', 'message' => 'Server write permissions denied!'));
    }

}

function currency($loc = 0, $id = 0)
{
    $ci =& get_instance();
    $ci->load->database();
    if ($loc > 0 && $id == 0) {
        $query = $ci->db->query("SELECT cur FROM cberp_locations WHERE id='$loc' LIMIT 1");
        $row = $query->row_array();
        $id = $row['cur'];
    }

    //erp2024 10-04-2025 fetch currency from cberp_system
    $query = $ci->db->query("SELECT currency FROM cberp_system WHERE id=1 LIMIT 1");
    $row = $query->row_array();
    $currency = $row['currency'];
    // if ($id > 0) {
    //     $query = $ci->db->query("SELECT * FROM cberp_currencies WHERE id='$id' LIMIT 1");
    //     $row = $query->row_array();
    //     $currency = $row['symbol'];
    // } else {
    //     $query = $ci->db->query("SELECT currency FROM cberp_system WHERE id=1 LIMIT 1");
    //     $row = $query->row_array();
    //     $currency = $row['currency'];
    // }
    return $currency;
}

function plugins_checker()
{
    $path = FCPATH . 'application/plugins';
    $plugins = array_diff(scandir($path), array('.', '..'));
    foreach ($plugins as $row) {
        $url = file_get_contents($path . '/' . $row);
        $plug = json_decode($url, true);
        echo '    <li><a class="dropdown-item"
                                                           href="' . base_url() . $plug['path'] . '"><i
                                                                    class="ft-chevron-right"></i> ' . $plug['name'] . '
                                                        </a></li>';
    }
}

function custom_plugins_checker($name='sms')
{
    $path = FCPATH . 'application'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$name;
      if(file_exists($path)) {


          $plugins = array_diff(scandir($path), array('.', '..'));
          foreach ($plugins as $row) {
              $url = file_get_contents($path . '/' . $row);
              $plug = json_decode($url, true);
              echo '    <li><a class="dropdown-item"
                                                           href="' . base_url() . $plug['path'] . '"><i
                                                                    class="ft-chevron-right"></i> ' . $plug['name'] . '
                                                        </a></li>';
          }
      }
}

function datatable_lang()
{
   $ci =& get_instance();
   $result='';
   $lang= $ci->config->item('mylang');
   $dfile=FCPATH . 'application/language/'.$lang.'/datatable.php';
   if(file_exists($dfile)) $result=include_once($dfile);
    echo $result;
}

function accounting($loc = 0)
{
    $ci =& get_instance();
    $ci->load->database();
    if ($loc > 0) {
        $query = $ci->db->query("SELECT cur FROM cberp_locations WHERE id='$loc' LIMIT 1");
        $row = $query->row_array();
        $id = $row['cur'];
        if ($id > 0) {
            $query = $ci->db->query("SELECT * FROM cberp_currencies WHERE id='$id' LIMIT 1");
            $row = $query->row_array();

            $thosand = $row['thous'];
            $dec_point = $row['dpoint'];
            $decimal_after = $row['decim'];
        }
    } else {
        $query2 = $ci->db->query("SELECT * FROM univarsal_api WHERE id=4 LIMIT 1");
        $row = $query2->row_array();

        $thosand = $row['key2'];
        $dec_point = $row['key1'];
        $decimal_after = $row['url'];
    }

    echo " <script type='text/javascript'>accounting.settings = {number: {precision :$decimal_after,thousand: '$thosand',decimal : '$dec_point'}};
var two_fixed=$decimal_after; </script>";

}

function configured_data()
{
    $ci =& get_instance();
    $query = $ci->db->query("SELECT currency as config_currency,currency_format as config_currecny_format,tax as config_tax,taxid as config_taxid,dformat as config_dformat,lang as config_lang,zone as timezone, prefix as invoiceprefix FROM cberp_system"); 
    $row = $query->row_array();
    if(!empty($row)){
        $ci->session->set_userdata('configurations', $row);
    }

    $ci =& get_instance();
    $query1 = $ci->db->query("SELECT method as shipping_charge_return FROM univarsal_api WHERE id = 61"); 
    $row1 = $query1->row_array();
    if(!empty($row1)){
        $ci->session->set_userdata('shipping_charge_return', $row1['shipping_charge_return']);
    }
    
}

if (!function_exists('dd')) {
    function dd($var)
    {
        print_r($var);
        exit;
    }
 }

 function warehouse_list(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT store_id,store_name FROM cberp_store");
    $result = $query->result_array();
    return $result;
 }
 function warehouse_list_with_type(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT store_id,store_name FROM cberp_store order by warehouse_type ASC");
    $result = $query->result_array();
    return $result;
 }
 function warehouse_list_byid($id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT id,title FROM cberp_store WHERE id = '$id'");
    $result = $query->result_array();
    return $result;
 }
 function employee_list(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT id,name,expense_claim_approver FROM cberp_employees order by name ASC");
    $result = $query->result_array();
    return $result;
 }

 function supplier_list(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT supplier_id,name FROM cberp_suppliers order by name ASC");
    $result = $query->result_array();
    return $result;
 }
 function customer_list(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT customer_id,name FROM cberp_customers WHERE status='Enable' order by name ASC");
    $result = $query->result_array();
    return $result;
 }
 function min_max_amount($table, $field){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT MIN($field) as minimum,MAX($field) as maximum FROM $table");
    $result = $query->row_array();
    // die($ci->db->last_query());
    return $result;
 }

 function get_currency_by_id($currency){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT id,rate FROM cberp_currencies WHERE symbol='$currency' OR code='$currency' LIMIT 1");
    $result = $query->row_array();
    return $result;
 }
 function get_account_details($type){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT * FROM cberp_accounts WHERE account_type='$type' LIMIT 1");
    $result = $query->row_array();
    return $result;
 }

 //function for find default sales discount account and more
 function get_account_details_for_invoicing($type,$holder){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT * FROM cberp_accounts WHERE account_type='$type' AND holder = '$holder' LIMIT 1");
    $result = $query->row_array();
    return $result;
 }
 function bank_account_list(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT * FROM cberp_bank_ac WHERE enable='Yes'");
    $result = $query->result_array();
    return $result;
 }
 function bank_account_list_with_balance(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT * FROM cberp_bank_ac 
    INNER JOIN cberp_accounts ON cberp_accounts.acn = cberp_bank_ac.code 
    WHERE cberp_bank_ac.enable='Yes' AND cberp_accounts.lastbal !=0");
    $result = $query->result_array();
    return $result;
 }
 function bank_account_list_by_id($id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT * FROM cberp_bank_ac WHERE enable='Yes' AND id = '$id'");
    $result = $query->row_array();
    return $result;
 }
 function default_bank_account(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT code,acn,id FROM cberp_bank_ac WHERE enable='Yes'  AND defaultaccount = 'Yes'");
    $result = $query->row_array();
    return $result;
 }

 function default_sales_account($header){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT cberp_accounts.acn,cberp_accounts.holder,cberp_accounts.id FROM cberp_accounts
    INNER JOIN cberp_coa_types ON cberp_coa_types.coa_type_id = cberp_accounts.account_type_id
    INNER JOIN cberp_coa_headers ON cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id
    WHERE cberp_coa_headers.coa_header = '$header' AND cberp_accounts.holder ='Sales'");
    $result = $query->row_array();
    return $result;
 }
 function coa_account_list_by_header($header){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT cberp_accounts.acn,cberp_accounts.holder,cberp_accounts.id FROM cberp_accounts
    INNER JOIN cberp_coa_types ON cberp_coa_types.coa_type_id = cberp_accounts.account_type_id
    INNER JOIN cberp_coa_headers ON cberp_coa_headers.coa_header_id = cberp_coa_types.coa_header_id
    WHERE cberp_coa_headers.coa_header = '$header'");
    $result = $query->result_array();
    return $result;
 }

 function get_transnumber()
 {
    $ci =& get_instance();
    $ci->db->select('id');
    $ci->db->from('cberp_bank_transactions');
    $ci->db->order_by('id', 'DESC');
    $ci->db->limit(1);
     $query =  $ci->db->get();
     if ($query->num_rows() > 0) {
        $latestransid = $query->row()->id+1;
        $transaction_number = (strlen($latestransid) < 4) ? str_pad($latestransid, 4, '0', STR_PAD_LEFT) : $latestransid;
        return("TRA-".$transaction_number);
     } else {
        return("TRA-0001");
     }
 }


function get_latest_trans_number()
{
    $ci =& get_instance();
    $prefix_data = get_prefix();
    $prefix= $prefix_data['transaction_prefix'];
    $ci->db->select('transaction_number');
    $ci->db->from('cberp_transactions');
    $ci->db->like('transaction_number', $prefix, 'after'); 
    $ci->db->order_by('id', 'DESC');
    $ci->db->limit(1);

    $query = $ci->db->get();

    if ($query->num_rows() > 0) {
        $latest_transaction_number = $query->row()->transaction_number;
        $number = intval(substr($latest_transaction_number, strlen($prefix)));
        $next_number = $number + 1;
        $new_number = str_pad($next_number, 4, '0', STR_PAD_LEFT);
        $transaction_number = $prefix . $new_number;
    } else {
        $transaction_number = $prefix . '0001';
    }

    return $transaction_number;
}


function get_latest_journal_number()
{
    $ci =& get_instance();
    // $prefix_data = get_prefix();
    // $prefix= $prefix_data['transaction_prefix'];
    $prefix= 'TCODE/MJE/';
    $ci->db->select('journal_number');
    $ci->db->from('cberp_manual_journals');
    $ci->db->like('journal_number', $prefix, 'after'); 
    $ci->db->order_by('id', 'DESC');
    $ci->db->limit(1);

    $query = $ci->db->get();

    if ($query->num_rows() > 0) {
        $latest_journal_number = $query->row()->journal_number;
        $number = intval(substr($latest_journal_number, strlen($prefix)));
        $next_number = $number + 1;
        $new_number = str_pad($next_number, 4, '0', STR_PAD_LEFT);
        $journal_number = $prefix . $new_number;
    } else {
        $journal_number = $prefix . '0001';
    }

    return $journal_number;
}


 function get_banktrans_link_number()
 {
    $ci =& get_instance();
    $ci->db->select('transaction_link_id');
    $ci->db->from('cberp_payment_transaction_link');
    $ci->db->order_by('transaction_link_id', 'DESC');
    $ci->db->limit(1);
     $query =  $ci->db->get();
     if ($query->num_rows() > 0) {
        $latestransid = $query->row()->transaction_link_id+1;
        return($latestransid);
     } else {
        return(110001);
     }
 }

 function get_latest_expense_claim_number()
{
    $ci =& get_instance();
    // $prefix_data = get_prefix();
    // $prefix= $prefix_data['transaction_prefix'];
    $prefix= 'TCODE/EC/';
    $ci->db->select('claim_number');
    $ci->db->from('cberp_expense_claims');
    $ci->db->like('claim_number', $prefix, 'after'); 
    $ci->db->order_by('id', 'DESC');
    $ci->db->limit(1);

    $query = $ci->db->get();

    if ($query->num_rows() > 0) {
        $latest_transaction_number = $query->row()->claim_number;
        $number = intval(substr($latest_transaction_number, strlen($prefix)));
        $next_number = $number + 1;
        $new_number = str_pad($next_number, 4, '0', STR_PAD_LEFT);
        $transaction_number = $prefix . $new_number;
    } else {
        $transaction_number = $prefix . '0001';
    }

    return $transaction_number;
}
 function get_banktrans_reference_number()
 {
    $ci =& get_instance();
    $ci->db->select('trans_ref_number');
    $ci->db->from('cberp_bank_transactions');
    $ci->db->order_by('trans_ref_number', 'DESC');
    $ci->db->limit(1);
     $query =  $ci->db->get();
     if ($query->num_rows() > 0) {
        $latestransid = $query->row()->trans_ref_number+1;
        return($latestransid);
     } else {
        return(110001);
     }
 }
 function coa_account_against_productid($productid){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT cberp_product_ai.income_account_number,cberp_product_ai.expense_account_number FROM cberp_product_ai
    WHERE cberp_product_ai.product_id = '$productid'");
    $result = $query->row_array();
    return $result;
 }
 function default_receivable_account(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT cberp_accounts.acn,cberp_accounts.holder,cberp_accounts.id FROM cberp_accounts
    INNER JOIN cberp_coa_types ON cberp_coa_types.coa_type_id = cberp_accounts.account_type_id
    WHERE cberp_accounts.holder = 'Accounts Receivable' AND cberp_coa_types.typename='Current Asset'  AND default_flg = '1'");
    $result = $query->row_array();
    return $result;
 }
 function default_invoice_return_account(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT cberp_accounts.acn,cberp_accounts.holder,cberp_accounts.id FROM cberp_accounts
    INNER JOIN cberp_coa_types ON cberp_coa_types.coa_type_id = cberp_accounts.account_type_id
    WHERE cberp_accounts.holder = 'General Expenses' AND cberp_coa_types.typename='Expense'  AND default_flg = '1'");
    $result = $query->row_array();
    return $result;
 }
 function deliverynote_save_and_new_action(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT cberp_accounts.acn,cberp_accounts.holder,cberp_accounts.id FROM cberp_accounts
    INNER JOIN cberp_coa_types ON cberp_coa_types.coa_type_id = cberp_accounts.account_type_id
    WHERE cberp_accounts.holder = 'Accruals' AND cberp_coa_types.typename='Current Liability'  AND default_flg = '1'");
    $result = $query->row_array();
    return $result;
 }
 function default_payable_account(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT cberp_accounts.acn,cberp_accounts.holder,cberp_accounts.id FROM cberp_accounts
    INNER JOIN cberp_coa_types ON cberp_coa_types.coa_type_id = cberp_accounts.account_type_id
    WHERE cberp_accounts.holder = 'Accounts Payable' AND cberp_coa_types.typename='Current Liability' AND default_flg = '1'");
    $result = $query->row_array();
    return $result;
 }
 
 function get_payment_trans_number($invoiceid)
 {
    $ci =& get_instance();
    $ci->db->select_min('payment_transaction_number');
    $ci->db->from('cberp_invoices');
    $ci->db->where('id', $invoiceid);
    $query =  $ci->db->get();
     if ($query->num_rows() > 0) {
        $payment_transaction_number = $query->row()->payment_transaction_number;
        return($payment_transaction_number);
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

 //newly added erp2024 19-11-2024
 function insert_transaction($type, $cat, $amount, $coa_account_id, $transaction_number, $invoice_number,$payerid="") {
    $ci =& get_instance();
    $data = [
        'acid' => $coa_account_id,
        'type' => 'Asset',
        'cat' => $cat,
        ($type === 'credit' ? 'credit' : 'debit') => $amount,
        'eid' => $ci->session->userdata('id'),
        'date' => date('Y-m-d'),
        'transaction_number' => $transaction_number,
        'invoice_number' => $invoice_number,
        'payerid' => ($payerid) ? $payerid : 0
    ];
    $ci->db->insert('cberp_transactions', $data);
}
function insert_return_transaction($type, $cat, $amount, $coa_account_id, $transaction_number,$payerid="") {
    $ci =& get_instance();
    $data = [
        'acid' => $coa_account_id,
        'type' => 'Asset',
        'cat' => $cat,
        ($type === 'credit' ? 'credit' : 'debit') => $amount,
        'eid' => $ci->session->userdata('id'),
        'date' => date('Y-m-d'),
        'transaction_number' => $transaction_number,
        'payerid' => ($payerid) ? $payerid : 0
    ];
    $ci->db->insert('cberp_transactions', $data);
}

function update_account_balance($account_id, $amount, $operation = 'add') {
    $ci =& get_instance();
    $ci->db->set('lastbal', "lastbal " . ($operation === 'add' ? '+' : '-') . " " . $amount, FALSE);
    $ci->db->where('acn', $account_id);
    $ci->db->update('cberp_accounts');
}

function get_customer_credit_limit($customer_id)
{
    $ci =& get_instance();
    $ci->db->select('avalable_credit_limit,credit_limit');
    $ci->db->from('cberp_customers');
    $ci->db->where('customer_id', $customer_id);
    $query = $ci->db->get();
    return $query->row_array();
}
function update_customer_credit($cid, $amount,$avalable_credit_limit) {
    $ci =& get_instance();
    $new_credit_limit = $avalable_credit_limit + $amount;
    $ci->db->set('avalable_credit_limit', $new_credit_limit, FALSE);
    $ci->db->where('customer_id', $cid);
    $ci->db->update('cberp_customers');
    
}
function get_customer_details($customer_id)
{
    $ci =& get_instance();
    $ci->db->select('*');
    $ci->db->from('cberp_customers');
    $ci->db->where('customer_id', $customer_id);
    $query = $ci->db->get();
    return $query->result_array();
}
function reset_customer_credit($cid, $amount,$avalable_credit_limit) {
    $ci =& get_instance();
    $new_credit_limit = $avalable_credit_limit - $amount;

    $ci->db->set('avalable_credit_limit', $new_credit_limit, FALSE);
    $ci->db->where('customer_id', $cid);
    $ci->db->update('cberp_customers');
}

function insert_bank_transaction($type, $amount, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_transaction_number) {
    $ci =& get_instance();
    $data = [
        'trans_type' => $type,
        'trans_amount' => $amount,
        'trans_date' => date('Y-m-d H:i:s'),
        'trans_number' => $bank_transaction_number,
        'trans_customer_id' => $cid,
        'trans_payment_method' => $pmethod,
        'trans_account_id' => $bank_account_id,
        'trans_chart_of_account_id' => $coa_account_id,
        'from_trans_number' => $transaction_number,
        'trans_ref_number' => get_banktrans_reference_number(),
        'transfered_by'=> $ci->session->userdata('id')
    ];
    $ci->db->insert('cberp_bank_transactions', $data);
}
function update_bank_transaction($type, $amount, $cid, $pmethod, $bank_account_id, $coa_account_id, $transaction_number, $bank_transaction_number,$reference_number) {
    $ci =& get_instance();
    $data = [
        'trans_type' => $type,
        'trans_amount' => $amount,
        'trans_date' => date('Y-m-d H:i:s'),
        'trans_number' => $bank_transaction_number,
        'trans_customer_id' => $cid,
        'trans_payment_method' => $pmethod,
        'trans_account_id' => $bank_account_id,
        'trans_chart_of_account_id' => $coa_account_id,
        'from_trans_number' => $transaction_number,
        'trans_ref_number' => $reference_number,
        'transfered_by'=> $ci->session->userdata('id')
    ];
    $ci->db->update('cberp_bank_transactions', $data,['trans_ref_number'=>$reference_number]);
}
function insert_payment_transaction_link($invoice_number, $transaction_number, $bank_tansaction_number) {
    $ci =& get_instance();
    $banktranslink_data = [                            
        'trans_type' => 'Invoice',
        'trans_type_number' => $invoice_number,
        'transaction_number'=>$transaction_number,
        'bank_transaction_number'=>$bank_tansaction_number,
        'created_dt' => date('Y-m-d H:i:s'),
        'created_by'=> $ci->session->userdata('id')
    ];
    $ci->db->insert('cberp_payment_transaction_link', $banktranslink_data);
}
function insert_return_payment_transaction_link($trans_type,$invoice_number, $transaction_number, $bank_tansaction_number) {
    $ci =& get_instance();
    $banktranslink_data = [                            
        'trans_type' => $trans_type,
        'trans_type_number' => $invoice_number,
        'transaction_number'=>$transaction_number,
        'bank_transaction_number'=>$bank_tansaction_number,
        'created_dt' => date('Y-m-d H:i:s'),
        'created_by'=> $ci->session->userdata('id')
    ];
    $ci->db->insert('cberp_payment_transaction_link', $banktranslink_data);
}
function update_invoice($invoice_number, $pmethod, $paid_amount, $payment_status) {
    $ci =& get_instance();
    // $ci->db->set('payment_method', $pmethod);
    // $ci->db->set('payment_recieved_date', date('Y-m-d'));
    $ci->db->set('paid_amount', $paid_amount);
    $ci->db->set('status', $payment_status);
    $ci->db->where('invoice_number', $invoice_number);
    $ci->db->update('cberp_invoices');
}
//newly added erp2024 19-11-2024 end

//erp2024 27-11-2024
function employee_list_with_roles(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT cberp_employees.id,cberp_employees.name,cberp_users.roleid FROM cberp_employees
                            JOIN cberp_users ON cberp_users.id = cberp_employees.id
                            ORDER BY cberp_employees.name ASC"
                            );
    $result = $query->result_array();
    return $result;
 }

 function income_or__expense_category_by_type($type)
 {
     $ci =& get_instance();
     $ci->db->select('cberp_bank_transcategory.transcat_id,cberp_bank_transcategory.transcat_name');
     $ci->db->from('cberp_bank_transcategory');
     $ci->db->join('cberp_bank_transtype', 'cberp_bank_transtype.transtype_id = cberp_bank_transcategory.transtype_id', 'inner');
     $ci->db->where('cberp_bank_transtype.transtype_name', $type);   
     $ci->db->where('cberp_bank_transtype.status', 'Active');   
     $ci->db->where('cberp_bank_transcategory.status', 'Active');   
     $ci->db->order_by('cberp_bank_transcategory.transcat_name', 'ASC');   
     $query = $ci->db->get();
     $result = $query->result_array();
     return $result;
 }

 function order_discount_percentage($orderamount, $total)
 {
    $percentage = 0;
    if($orderamount > 0)
    {
        $percentage = ($orderamount / $total) * 100;
        $percentage = round($percentage, 3);
    }
    return $percentage;
 }

 function convert_order_discount_percentage_to_amount($orderamount, $percentage)
 {
    $result =0;
    if($orderamount > 0 && $percentage > 0)
    {
        $result = ($orderamount * $percentage) / 100;
        $result = round($result, 3);
    }
    return $result;
 }

 
 function order_discount_percentage_for_single_product($orderamount, $totalqty)
 {

    $percentage = 0;
    if ($orderamount > 0) { 
        $percentage = ($totalqty / $orderamount)*100; 
        $percentage = round($percentage, 3);
    }
    return $percentage;
 }

 function find_discount_perecntage_from_amount($totalAmount, $discountAmount) {
    if ($totalAmount > 0) { // Prevent division by zero
        return round(($discountAmount / $totalAmount) * 100, 3); 
    }
    return 0;
}

 //load all kind of default accounts
 function default_chart_of_account($type){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT `$type` FROM cberp_default_double_entry_accounts LIMIT 1");
    
    if ($query->num_rows() > 0) {
        $result = $query->row_array();
        return $result[$type];
    }
    else{
        return 0;
    }
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

function master_table_log($table,$master_id,$action_performed)
{
    $ci =& get_instance();
    $customerenqmain_log = array(
        'master_id' => $master_id,
        'action_performed' => $action_performed,
        'ip_address' => getUserIpAddress(),
        'updated_dt' => date('Y-m-d H:i:s'),
        'updated_by'=> $ci->session->userdata('id')
    );    
    $ci->db->insert($table,$customerenqmain_log);
    // die($ci->db->last_query());
}

function history_table_log($table,$master_key,$master_id,$action_performed)
{
    $ci =& get_instance();
    $customerenqmain_log = array(
        $master_key => $master_id,
        'action_performed' => $action_performed,
        'ip_address' => getUserIpAddress(),
        'performed_dt' => date('Y-m-d H:i:s'),
        'performed_by'=> $ci->session->userdata('id')
    );    
    $ci->db->insert($table,$customerenqmain_log);
    // die($ci->db->last_query());
}


function log_table_data($source_table, $log_table, $id_field, $log_id_field, $action_performed, $id) {
    $ci =& get_instance(); // Get CodeIgniter instance

    $userid = $ci->session->userdata('id');
    $dt = date('Y-m-d H:i:s');
    $ip_address = getUserIpAddress();

    // Prepare base SQL query
    $sql = "INSERT INTO $log_table (";

    // Get columns from the source table
    $columns = $ci->db->list_fields($source_table);

    // Build column list for the log table
    $log_columns = [];
    foreach ($columns as $column) {
        if ($column == $id_field) {
            // Map the primary key from source to log ID field
            $log_columns[] = "$log_id_field"; // Just use the field name in the log table
        } else {
            $log_columns[] = $column;
        }
    }

    // Append fixed log fields dynamically
    $log_columns[] = 'action_performed';
    $log_columns[] = 'ip_address';
    $log_columns[] = 'performed_dt';
    $log_columns[] = 'performed_by';

    // Finalize SQL query for column names
    $sql .= implode(', ', $log_columns) . ") SELECT ";

    // Build the SELECT part dynamically
    $select_columns = [];
    foreach ($columns as $column) {
        if ($column == $id_field) {
            // Map the primary key from source to log ID field
            $select_columns[] = "$source_table.$id_field AS $log_id_field"; // Alias only in SELECT part
        } else {
            $select_columns[] = "$source_table.$column";
        }
    }

    // Append fixed fields to SELECT part
    $select_columns[] = "'$action_performed' AS action_performed";
    $select_columns[] = "'$ip_address' AS ip_address";
    $select_columns[] = "'$dt' AS performed_dt";
    $select_columns[] = "'$userid' AS performed_by";

    // Finalize the SQL query with WHERE condition
    $sql .= implode(', ', $select_columns) . " FROM $source_table WHERE $source_table.$id_field = ?";

    // Execute query using parameterized query to prevent SQL injection
    $ci->db->query($sql, array($id));

}

function log_table_items_data($source_table, $log_table, $id_field, $log_id_field, $action_performed, $fieldname, $fieldval) {
    $ci =& get_instance(); // Get CodeIgniter instance

    // Get session user ID, IP address, and timestamp
    $userid = $ci->session->userdata('id');
    $dt = date('Y-m-d H:i:s');
    $ip_address = getUserIpAddress();

    // Add WHERE condition for the additional key
    $ci->db->where($fieldname, $fieldval);
    $rows_data = $ci->db->get($source_table)->result_array();

    if (empty($rows_data)) {
        // If no data is found, handle the error
        die("No records found in $source_table with $fieldname = $fieldval");
    }

    // Loop through each row and insert it into the log table
    foreach ($rows_data as $row_data) {
        // Prepare base SQL query
        $sql = "INSERT INTO $log_table (";

        // Get column names from the row keys
        $columns = array_keys($row_data);

        // Build column list for the log table
        $log_columns = [];
        foreach ($columns as $column) {
            if ($column == $id_field) {
                // Map the primary key from source to log ID field
                $log_columns[] = "$log_id_field"; // Just use the field name in the log table
            } else {
                $log_columns[] = $column;
            }
        }

        // Append fixed log fields dynamically
        $log_columns[] = 'action_performed';
        $log_columns[] = 'ip_address';
        $log_columns[] = 'updated_dt';
        $log_columns[] = 'updated_by';

        // Finalize SQL query for column names
        $sql .= implode(', ', $log_columns) . ") VALUES (";

        // Build the VALUES part dynamically
        $values = [];
        foreach ($columns as $column) {
            if ($column == $id_field) {
                // Map the primary key from source to log ID field
                $values[] = $ci->db->escape($row_data[$id_field]);
            } else {
                $values[] = $ci->db->escape($row_data[$column]);
            }
        }

        // Append fixed fields to VALUES part
        $values[] = $ci->db->escape($action_performed);
        $values[] = $ci->db->escape($ip_address);
        $values[] = $ci->db->escape($dt);
        $values[] = $ci->db->escape($userid);

        // Finalize the SQL query
        $sql .= implode(', ', $values) . ")";

        // Execute the query
        $ci->db->query($sql);

        // For debugging purposes (optional)
        // die($ci->db->last_query());
    }

    function generate_pdf_html1($display_fields, $output_data, $title)
    {
        // $ci =& get_instance();
        // // Start building the HTML content
        $html = '<h2 style="text-align: center;">' . htmlspecialchars($title) . '</h2>';
        $html .= '<table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse;">';

        // Table Header
        $html .= '<thead><tr>';
        foreach ($display_fields as $field) {
            $html .= '<th style="background-color: #f2f2f2; text-align: left; padding: 5px;">' . htmlspecialchars($field) . '</th>';
        }
        $html .= '</tr></thead>';

        // Table Body
        $html .= '<tbody>';
        foreach ($output_data as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td style="padding: 5px;">' . htmlspecialchars($cell) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';

        // Close the table
        $html .= '</table>';

        return $html;

    }
}


function generate_pdf_html($display_fields, $output_data, $title)
{
    // Start HTML structure
    $html = '<html>';
    $html .= '<head>';
    $html .= '<meta charset="UTF-8">';
    $html .= '<style>';
    // Add some basic styling to the table for better presentation
    $html .= 'table { width: 100%; border-collapse: collapse; }';
    $html .= 'th, td { padding: 8px; border: 1px solid #ddd; }';
    $html .= 'th { background-color: #f2f2f2; text-align: left; }';
    $html .= '</style>';
    $html .= '</head>';
    
    // Start Body
    $html .= '<body>';
    
    // Add Title
    $html .= '<h2 style="text-align: center;">' . htmlspecialchars($title) . '</h2>';

    // Start Table
    $html .= '<table>';
    $html .= '<thead><tr>';
    foreach ($display_fields as $field) {
        $html .= '<th>' . htmlspecialchars($field) . '</th>';
    }
    $html .= '</tr></thead>';

    // Table Body
    $html .= '<tbody>';
    foreach ($output_data as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) {
            $html .= '<td>' . htmlspecialchars($cell) . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</tbody>';

    // Close Table
    $html .= '</table>';

    // Close Body and HTML
    $html .= '</body>';
    $html .= '</html>';

    return $html;
}

function history_table_with_foreginkey_log($table,$master_key,$master_id,$foreginkey,$foreginkeyval,$action_performed)
{
    $ci =& get_instance();
    $customerenqmain_log = array(
        $master_key => $master_id,
        $foreginkey => $foreginkeyval,
        'action_performed' => $action_performed,
        'ip_address' => getUserIpAddress(),
        'performed_dt' => date('Y-m-d H:i:s'),
        'performed_by'=> $ci->session->userdata('id')
    );    
    $ci->db->insert($table,$customerenqmain_log);
}
function get_latest_reconciliation_number()
{
    $ci =& get_instance();
    // $prefix_data = get_prefix();
    $prefix= 'TCODE/RECON/';
    $ci->db->select('id');
    $ci->db->from('cberp_reconciliations');
    $ci->db->order_by('id', 'DESC');
    $query = $ci->db->get();

    if ($query->num_rows() > 0) {
        $latest_transaction_number = $query->row()->id;
        $number = $latest_transaction_number;
        // $number = intval(substr($latest_transaction_number, strlen($prefix)));
        $next_number = $number + 1;
        $new_number = str_pad($next_number, 4, '0', STR_PAD_LEFT);
        $transaction_number = $prefix . $new_number;
    } else {
        $transaction_number = $prefix . '0001';
    }

    return $transaction_number;
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



function detailed_log_history($pagename,$item_no,$action_nane,$changedFields)
{
    $ci =& get_instance();
    $changedFields = json_decode($changedFields, true); 

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
                'changed_by' => $ci->session->userdata('id'),
                'ip_address' => getUserIpAddress(),
                'action' => $action_nane
            ];
        }
        $ci->db->insert_batch('cberp_master_log', $data_history);
       
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
            'changed_by' => $ci->session->userdata('id'),
            'ip_address' => getUserIpAddress(),
            'action' => $action_nane
        ];
        $ci->db->insert_batch('cberp_master_log', $data_history);
    }
    return $sequence_number;

}

function get_roles(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT cberp_roles.role_id,cberp_roles.role_name FROM cberp_roles
            WHERE cberp_roles.status ='Active'
            ORDER BY cberp_roles.id ASC"
        );
    $result = $query->result_array();
    return $result;
 }

function get_modules()
{
    $ci =& get_instance();   
    $ci->db->select('module_number,module_name');
    $ci->db->from('cberp_module_groups'); 
    $ci->db->where('status', 'Active');
    $ci->db->order_by('id', 'ASC');
    $query = $ci->db->get();
    return $query->result_array();
}
function get_menus()
{
    $ci =& get_instance();   
    $ci->db->select('menu_number,menu_name,menu_label');
    $ci->db->from('cberp_menus'); 
    $ci->db->where('status', 'Active');
    // $ci->db->order_by('id', 'ASC');
    $query = $ci->db->get();
    return $query->result_array();
}


function get_latest_unique_number($fieldname,$table,$primarykey,$defaultnumber)
{
    $ci =& get_instance();
    // $prefix_data = get_prefix();
    // $prefix= $prefix_data['transaction_prefix'];
    $ci->db->select("$fieldname as transaction_number");
    $ci->db->from($table);
    $ci->db->order_by($primarykey, 'DESC');
    $ci->db->limit(1);

    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $latest_transaction_number = $query->row()->transaction_number;
        $transaction_number = $latest_transaction_number + 1;
    } else {
        $transaction_number = $defaultnumber;
    }

    return $transaction_number;
}

function delete_product_log($table, $pagename, $item_no, $currentproducts,$sequence_number)
{
    $ci =& get_instance();
    $ci->db->select("$table.qty, $table.price, cberp_products.product_name, cberp_products.product_code, cberp_products.pid");
    $ci->db->from($table); // Use the table variable here
    $ci->db->join('cberp_products', "cberp_products.pid = $table.pid"); // Dynamic table reference
    $ci->db->where('tid', $item_no);
    $query = $ci->db->get();

    // Fetch results from the query
    $result = $query->result_array();

    // To store the PIDs present in $result but not in $currentproducts
    $notInCurrentProducts = [];
    $data_history = [];


    // Loop through $result and compare with $currentproducts
    foreach ($result as $row) {
        if (!in_array($row['pid'], $currentproducts)) {
            // If PID is not in $currentproducts, store it
            $notInCurrentProducts[] = $row;

            // Prepare product name for history
            $productname = $row['product_name'] . " (" . $row['product_code'] . ")";

            // Prepare history data
            $data_history[] = [
                'field_name' => $productname,
                'field_label' => "Deleted " . $productname,
                'item_no' => $item_no,
                'seqence_number' => $sequence_number,
                'log_from' => $pagename,
                'old_value' => $row['qty'],
                'new_value' => 0,
                'changed_date' => date('Y-m-d H:i:s'),
                'changed_by' => $ci->session->userdata('id'), // Use $this->session
                'ip_address' => getUserIpAddress(), // Ensure this function is defined
                'action' => "Deleted " . $productname,
            ];
        }
    }
    if($data_history)
    {
        $ci->db->insert_batch('cberp_master_log', $data_history);
    }

}

function get_all_active_menus()
{
    $ci =& get_instance();   
    $ci->db->select('cberp_menu_details.*');
    $ci->db->from('cberp_menu_details'); 
    // $ci->db->where('status', 'Active');
    $query = $ci->db->get();
    return $query->result_array();
}
function linked_menus_by_roles_id($role_id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT menu_link_id FROM cberp_role_menu_links WHERE role_id = '$role_id'");
    $result = $query->result_array();
    return $result;
 }
function linked_modules_by_roleid($role_id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT DISTINCT main_menu FROM cberp_role_menu_links JOIN cberp_menu_details ON cberp_menu_details.menu_id = cberp_role_menu_links.menu_link_id WHERE cberp_role_menu_links.role_id = '$role_id'");
    $result = $query->result_array();
    return $result;
 }
function linked_approvals_by_roleid($role_id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT DISTINCT main_menu FROM cberp_role_menu_links JOIN cberp_menu_details ON cberp_menu_details.menu_id = cberp_role_menu_links.menu_link_id WHERE cberp_role_menu_links.role_id = '$role_id'");
    $result = $query->result_array();
    return $result;
 }

 function linked_modules_by_user_id($user_id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT DISTINCT main_menu FROM cberp_user_menu_links JOIN cberp_menu_details ON cberp_menu_details.menu_id = cberp_user_menu_links.menu_link_id WHERE cberp_user_menu_links.user_id = '$user_id'");
    $result = $query->result_array();
    return $result;
 }


 //Employees and users are the same
function load_active_employees_or_users(){
    $ci =& get_instance();
    $ci->db->select("cberp_employees.name, cberp_employees.id");
    $ci->db->from('cberp_users'); 
    $ci->db->join('cberp_employees', "cberp_employees.id = cberp_users.id"); // Dynamic table reference
    $ci->db->where('banned', '0');
    $query = $ci->db->get();
    $result = $query->result_array();
    return $result;
 }

function linked_menus_by_user_id($user_id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT menu_link_id FROM cberp_user_menu_links WHERE user_id = '$user_id'");
    // die($ci->db->last_query());
    $result = $query->result_array();
    return $result;
 }

 function role_by_userid($user_id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT roleid FROM cberp_users WHERE id = '$user_id'");
    $result = $query->row_array();
    return $result['roleid'];
 }

 function load_assigned_permissions()
 {
    $ci =& get_instance();
     // Step 1: Fetch the initial set of results for user_id = 10
     $sql = "
         SELECT 
             `cberp_menu_details`.`menu_id`,
             `cberp_menu_details`.`main_menu`,
             `cberp_menu_details`.`submenu1`,
             `cberp_menu_details`.`submenu2`,
             `cberp_menu_details`.`menu_detail`,
             `cberp_menu_details`.`function`
         FROM 
             `cberp_user_menu_links`
         JOIN 
             `cberp_menu_details` 
         ON 
             `cberp_menu_details`.`menu_id` = `cberp_user_menu_links`.`menu_link_id`
         WHERE 
             `cberp_user_menu_links`.`user_id` = ?
         GROUP BY 
             CASE 
                 WHEN `cberp_menu_details`.`submenu2` IS NOT NULL THEN `cberp_menu_details`.`submenu2`
                 ELSE `cberp_menu_details`.`menu_id`
             END;
     ";

     // Execute the query for user_id = 10
    //  $query = $ci->db->query($sql, array(10));
     $query = $ci->db->query($sql, array($ci->session->userdata('id')));
     $result = $query->result_array();

     // Step 2: Extract menu_id values from the result
     $menu_ids = array_column($result, 'menu_id'); 
     $menu_ids = array_unique($menu_ids);
     $main_menu = array_column($result, 'main_menu');  
     $main_menu = array_unique($main_menu);
     $submenu1 = array_column($result, 'submenu1');  
     $submenu1 = array_unique($submenu1);
     $submenu2 = array_column($result, 'submenu2');  
     $submenu2 = array_unique($submenu2);

     // Step 3: Fetch the detailed records based on the extracted menu_ids
     if (!empty($menu_ids)) {
         // First query to find menu_id of main_menu where submenu1 and submenu2 are null
         $sql_main_menu = "
             SELECT 
                 `cberp_menu_details`.`menu_id`,
                 `cberp_menu_details`.`main_menu`,
                 `cberp_menu_details`.`submenu1`,
                 `cberp_menu_details`.`submenu2`
             FROM 
                 `cberp_menu_details`
             WHERE 
                 `cberp_menu_details`.`main_menu` IN ?
                 AND `cberp_menu_details`.`main_menu` IS NOT NULL
                 AND `cberp_menu_details`.`submenu1` IS NULL
                 AND `cberp_menu_details`.`submenu2` IS NULL
         ";

         // Second query to find menu_id of submenu1 where main_menu is not null, submenu1 is not null, and submenu2 is null
         $sql_submenu1 = "
             SELECT 
                 `cberp_menu_details`.`menu_id`,
                 `cberp_menu_details`.`main_menu`,
                 `cberp_menu_details`.`submenu1`,
                 `cberp_menu_details`.`submenu2`
             FROM 
                 `cberp_menu_details`
             WHERE 
                 `cberp_menu_details`.`submenu1` IN ?
                 AND `cberp_menu_details`.`main_menu` IS NOT NULL
                 AND `cberp_menu_details`.`submenu1` IS NOT NULL
                 AND `cberp_menu_details`.`submenu2` IS NULL
         ";

         // Third query to find menu_id of submenu1 where main_menu, submenu1, and submenu2 are not null
         $sql_submenu2 = "
             SELECT 
                 `cberp_menu_details`.`menu_id`,
                 `cberp_menu_details`.`main_menu`,
                 `cberp_menu_details`.`submenu1`,
                 `cberp_menu_details`.`submenu2`
             FROM 
                 `cberp_menu_details`
             WHERE 
                 `cberp_menu_details`.`submenu2` IN ?
                 AND `cberp_menu_details`.`main_menu` IS NOT NULL
                 AND `cberp_menu_details`.`submenu1` IS NOT NULL
                 AND `cberp_menu_details`.`submenu2` IS NOT NULL   
                 AND `cberp_menu_details`.`menu_detail` IS NULL   
                 AND `cberp_menu_details`.`function` IS NULL   
         ";

         // Execute all three queries with the menu_ids
         $query_main_menu = $ci->db->query($sql_main_menu, array($main_menu));
         $query_submenu1 = $ci->db->query($sql_submenu1, array($submenu1));  
        //  die($ci->db->last_query());
         $query_submenu2 = $ci->db->query($sql_submenu2, array($submenu2));
        
         // Combine all the results
         $main_menu_ids = $query_main_menu->result_array();
         $submenu1_ids = $query_submenu1->result_array();
         $submenu2_ids = $query_submenu2->result_array();

         // Combine all the results into one array
         $all_menu_ids = array_merge($main_menu_ids, $submenu1_ids, $submenu2_ids);
         $menuArray = [];
         // Step 4: Process the results by replacing spaces with underscores in main_menu, submenu1, and submenu2
         foreach ($all_menu_ids as &$row) {
             // Use a switch statement to determine which menu value is not empty
             switch (true) {
                 case !empty($row['submenu2']):
                     $menuArray[] = str_replace(' ', '_', $row['submenu2']) . '-' . $row['menu_id'];
                     break;
                 
                 case !empty($row['submenu1']):
                     $menuArray[] = str_replace(' ', '_', $row['submenu1']) . '-' . $row['menu_id'];
                     break;
                 
                 case !empty($row['main_menu']):
                     $menuArray[] = str_replace(' ', '_', $row['main_menu']) . '-' . $row['menu_id'];
                     break;
         
                 // Optionally, you can handle the case where no value is present
                 default:
                     // Do nothing or handle cases where all values are empty
                     break;
             }
         }            
         if(!empty($menuArray)){
            $ci->session->set_userdata('defined_permissions', $menuArray);
        }
        
     }
 }

 function load_permissions($main_menu,$submenu1,$submenu2="",$menu_detail="",$functionname="")
{
    $ci =& get_instance(); 
    $ci->db->select('function');
    $ci->db->from('cberp_menu_details');  
    $ci->db->join('cberp_user_menu_links','cberp_user_menu_links.menu_link_id=cberp_menu_details.menu_id');
    $ci->db->where('main_menu',$main_menu);
    $ci->db->where('submenu1',$submenu1);
    
    if($submenu2)
    {
        $ci->db->where('submenu2',$submenu2);
    }
    if($menu_detail)
    {
        $ci->db->where('menu_detail',$menu_detail);
    }
    if($functionname)
    {
        $ci->db->where('function',$functionname);
    }
    $ci->db->where('user_id',$ci->session->userdata('id'));
    $query = $ci->db->get();
    // die($ci->db->last_query());
    return $query->result_array();
}

function get_menu_ids_from_page($main_menu,$submenu1,$submenu2,$user_id)
{
    $ci =& get_instance();   
    $ci->db->select('menu_id');
    $ci->db->from('cberp_menu_details'); 
    $ci->db->join('cberp_user_menu_links','cberp_user_menu_links.menu_link_id=cberp_menu_details.menu_id');
    $ci->db->where('main_menu', $main_menu);
    $ci->db->where('submenu1', $submenu1);
    $ci->db->where('submenu2', $submenu2);
    $ci->db->where('user_id', $user_id);
    $query = $ci->db->get();
    // die($ci->db->last_query());
    return $query->result_array();
}
function get_menu_ids_from_role_page($main_menu,$submenu1,$submenu2,$role_id)
{
    $ci =& get_instance();   
    $ci->db->select('menu_id');
    $ci->db->from('cberp_menu_details'); 
    $ci->db->join('cberp_role_menu_links','cberp_role_menu_links.menu_link_id=cberp_menu_details.menu_id');
    $ci->db->where('main_menu', $main_menu);
    $ci->db->where('submenu1', $submenu1);
    $ci->db->where('submenu2', $submenu2);
    $ci->db->where('role_id', $role_id);
    $query = $ci->db->get();
    // die($ci->db->last_query());
    return $query->result_array();
}

function linked_user_module_approvals($user_id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT * FROM cberp_menu_user_module_approval WHERE cberp_menu_user_module_approval.user_id = '$user_id'");
    
    $result = $query->result_array();
    return $result;
 }
function linked_role_module_approvals($role_id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT * FROM cberp_menu_role_module_approval WHERE cberp_menu_role_module_approval.role_id = '$role_id'");
    $result = $query->result_array();
    return $result;
 }


 function upload_files($files, $function_type,$function_sequence_number) {
    $ci =& get_instance();
    $ci->load->library('upload');
    // Set upload configuration
    $config['upload_path'] = FCPATH . 'uploads/';
    $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
    $config['encrypt_name'] = TRUE;

    $uploaded_files = []; // To store the details of successfully uploaded files

    if (!empty($files['name'])) {
        foreach ($files['name'] as $key => $filename) {
            // Prepare the file for upload
            $_FILES['userfile']['name'] = $files['name'][$key];
            $_FILES['userfile']['type'] = $files['type'][$key];
            $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['userfile']['error'] = $files['error'][$key];
            $_FILES['userfile']['size'] = $files['size'][$key];

            $config['file_name'] = $files['name'][$key];
            $ci->upload->initialize($config);

            // Attempt to upload the file
            if ($ci->upload->do_upload('userfile')) {
                $uploaded_info = $ci->upload->data();
                $uploaded_data = [
                    'sent_received_id' => get_latest_sequence_number_for_file_upload(),
                    'function_type' => $function_type,
                    'function_sequence_number' => $function_sequence_number,
                    'file_name' => $uploaded_info['file_name'],
                    'actual_name' => $files['name'][$key],
                ];
                $ci->db->insert('cberp_sent_received_files', $uploaded_data);
                $uploaded_files[] = $uploaded_data;
            } else {
                log_message('error', 'File upload error: ' . $ci->upload->display_errors());
            }
        }
    }
}


function get_latest_sequence_number_for_file_upload()
{
    $ci =& get_instance();
   
    $ci->db->select('sent_received_id');
    $ci->db->from('cberp_sent_received_files'); 
    $ci->db->order_by('sent_received_id', 'DESC');
    $ci->db->limit(1);

    $query = $ci->db->get();

    if ($query->num_rows() > 0) {
        $seqence_number = $query->row()->sent_received_id+1;
    } else {
        $seqence_number = '1000';
    }

    return $seqence_number;
}
function country_list()
{
    $ci =& get_instance();
    $ci->db->select('*');
    $ci->db->from('cberp_country');
    $ci->db->where('status','1');
    $query = $ci->db->get();
    return $query->result_array();
}
function currency_list()
{
    $ci =& get_instance();
    $ci->db->select('*');
    $ci->db->from('cberp_currencies');
    $query = $ci->db->get();
    return $query->result_array();
}

function get_uploaded_images($page,$id){
    $ci =& get_instance();   
    $ci->db->select('*');
    $ci->db->from('cberp_sent_received_files'); 
    $ci->db->where('function_type', $page);
    $ci->db->where('function_sequence_number', $id);
    $query = $ci->db->get();
    return $query->result_array();
 }

 function record_exists_or_not($table,$fieldname,$fieldval){
    $ci =& get_instance();   
    $ci->db->select($fieldname);
    $ci->db->from($table); 
    $ci->db->where($fieldname, $fieldval);
    $query = $ci->db->get();
    $result = $query->row_array(); 
    if($result)
    {
        return true;
    }
    else{
        return false;
    }
 }

 
 function get_costing_transation_type($transaction_type_name)
 {
    $ci =& get_instance();
    $ci->db->select('transaction_type_id');
    $ci->db->from('cberp_cost_transaction_type');
    $ci->db->where('transaction_type_name', $transaction_type_name);
     $query =  $ci->db->get();
     if ($query->num_rows() > 0) {
        $latestransid = $query->row()->transaction_type_id;
        return($latestransid);
     } 
 }



//parameters are product_id,product_cost,transaction_quantity,transaction_type
 function insert_data_to_average_cost_table($product_code, $product_cost, $transaction_quantity, $transaction_type) {

    $ci =& get_instance();
    $ci->db->select('cberp_average_cost.product_average_cost,cberp_average_cost.product_cost, cberp_average_cost.id, cberp_products.onhand_quantity AS onhand');
    $ci->db->from('cberp_average_cost');
    $ci->db->join('cberp_products', 'cberp_products.product_code = cberp_average_cost.product_code');
    $ci->db->where('cberp_average_cost.product_average_cost IS NOT NULL');
    $ci->db->where('cberp_average_cost.product_code',$product_code);
    $ci->db->order_by('cberp_average_cost.id', 'DESC');
    $ci->db->limit(1);

    $query = $ci->db->get();
    $result = $query->row_array();
    $data = [];
    if($result)
    {
        $product_inventory_value = $result['product_average_cost'] * $result['onhand'];
        $data = [
            'product_code'             => $product_code,
            'product_cost'           => $result['product_cost'],
            'transaction_date_time'  => date("Y-m-d H:i:s"),
            'transaction_quantity'   => $transaction_quantity,
            'transaction_type'       => $transaction_type,
            'added_by'               => $ci->session->userdata('id'),
            'product_average_cost'   => $result['product_average_cost'],
            'product_inventory_value'=> $product_inventory_value,
            'onhand_quantity'        => $result['onhand'],
        ];
    }
    else{
        $ci->db->select('cberp_products.product_cost as product_average_cost, cberp_products.onhand_quantity AS onhand');
        $ci->db->from('cberp_products');
        $ci->db->where('cberp_products.product_code',$product_code);
        $ci->db->limit(1);
        $query = $ci->db->get();
        $result = $query->row_array();
        $product_inventory_value = $result['product_average_cost'] * $result['onhand'];
        $data = [
            'product_code'             => $product_code,
            'product_cost'           => $result['product_average_cost'],
            'transaction_date_time'  => date("Y-m-d H:i:s"),
            'transaction_quantity'   => $transaction_quantity,
            'transaction_type'       => $transaction_type,
            'added_by'               => $ci->session->userdata('id'),
            'product_average_cost'   => $result['product_average_cost'],
            'product_inventory_value'=> $product_inventory_value,
            'onhand_quantity'        => $result['onhand'],
        ];
    }
    $ci->db->insert('cberp_average_cost', $data);
}


function get_module_details_by_name($module_name)
{
    $ci =& get_instance();   
    $ci->db->select('module_number');
    $ci->db->from('cberp_module_groups'); 
    $ci->db->where('module_name', $module_name);
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

 function send_message_to_users($users_list,$target_url,$message_caption,$message,$duedate='')
 {
    $ci =& get_instance();   
    $full_data = [];
    $i=0;
    $data=[];
    $flag=0;
  
    if($users_list)
    {
        $duedate = ($duedate) ? $duedate." ".date('H:i:s') : date('Y-m-d H:i:s');
        foreach($users_list as $user)
        {
            if($user['user_id']==$ci->session->userdata('id'))
            {
                $flag =1;
            }
            $data =[
                'tdate'=> date('Y-m-d'),
                'start'=> date('Y-m-d'),
                'created_date_time'=> date('Y-m-d H:i:s'),
                'name'=> $message_caption,
                'status'=> 'Due',
                'duedate'=> $duedate,
                'aid' => $ci->session->userdata('id'),
                'eid' => $user['user_id'],
                'priority' => 'High',
                'related' => '0',
                'rid' => '0',
                'description' => $message,
                'target_url' => $target_url
            ];
            $full_data[$i] = $data;
            $i++;
            $ci->db->insert('cberp_todolist', $data);
            $taskid = $ci->db->insert_id();
            detailed_log_history('Tasks',$taskid,$message,'');	    
        }
        if($flag==0)
        {
            $data1 =[
                'tdate'=> date('Y-m-d'),
                'name'=> $message,
                'status'=> 'Due',
                'eid' => $ci->session->userdata('id'),
                'aid' => $user['user_id'],
                'priority' => 'High',
                'related' => '0',
                'rid' => '0',
                'description' => $message,
                'target_url' => $target_url
            ];
            $full_data[$i] = $data1;
            $ci->db->insert('cberp_todolist', $data1);
            $taskid = $ci->db->insert_id();
            detailed_log_history('Tasks',$taskid,$message,'');	 
        }
        // $invocieno = $ci->db->insert_id();    
        // $ci->db->insert_batch('cberp_todolist', $full_data);
        
        
    }
 }

function get_detailed_logs($id,$page)
{
    $ci =& get_instance();   
    $ci->db->select('cberp_master_log.*,cberp_employees.name,cberp_employees.picture');
    $ci->db->from('cberp_master_log');  
    $ci->db->join('cberp_employees','cberp_master_log.changed_by=cberp_employees.id','left');
    $ci->db->where('cberp_master_log.item_no',$id);
    $ci->db->where('cberp_master_log.log_from',$page);
    $ci->db->order_by('cberp_master_log.id', 'DESC');
    $query = $ci->db->get();
    // die($ci->db->last_query());
    return $query->result_array();
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
function employee_details_by_id($id){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT id,name FROM cberp_employees WHERE id = $id");
    $result = $query->row_array();
    return $result;
 }
 function tracking_details($field, $id)
 {
     $ci =& get_instance();
     
     // First query: Get tracking details
     $ci->db->select('cberp_transaction_tracking.*');
     $ci->db->from('cberp_transaction_tracking');
     $ci->db->where('cberp_transaction_tracking.' . $field, $id);
     $ci->db->order_by('id', 'DESC');
     $ci->db->limit(1);
     $query = $ci->db->get();
     $tracking = $query->row_array();
 
     // Get the quote_id from the first query result
     $quote_number = $tracking['quote_number'];
     $salesorder_number = $tracking['salesorder_number'];
 
     $ci->db->select('COUNT(DISTINCT salesorder_number) as sales_count');
     $ci->db->from('cberp_transaction_tracking');
     $ci->db->where('quote_number', $quote_number);
     $count_query = $ci->db->get();
     $sales_count = $count_query->row_array();

     //deliverynote count     

     $ci->db->select('COUNT(DISTINCT deliverynote_number) as delivery_count');
     $ci->db->from('cberp_transaction_tracking');
     $ci->db->where('salesorder_number', $salesorder_number);
     $delivery_count_query = $ci->db->get();
    //  die($ci->db->last_query());
     $delivery_count = $delivery_count_query->row_array(); 
     // Add the sales count to the tracking data
     $tracking['sales_count'] = $sales_count['sales_count'];
     $tracking['delivery_count'] = $delivery_count['delivery_count'];
 
     return $tracking;
 }
 


//  function insertion_to_tracking_table($insertField1, $insertValue1, $insertField2="", $insertValue2="", $searchField = "", $searchValue = "")
 function insertion_to_tracking_table($insertField1, $insertValue1, $searchField = "", $searchValue = "")
{
    $ci =& get_instance();   
    if($searchField) 
    {
        $ci->db->select('cberp_transaction_tracking.*');
        $ci->db->from('cberp_transaction_tracking');
        if (!empty($searchField) && !empty($searchValue)) {
            $ci->db->where('cberp_transaction_tracking.' . $searchField, $searchValue);
        }
        $ci->db->order_by('id', 'DESC');
        $ci->db->limit(1);
        $query = $ci->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $insertData = array();
            foreach ($row as $key => $value) {
                if ($key != 'id') {
                    $insertData[$key] = $value;
                }
            }
            $insertData[$insertField1] = $insertValue1;
            // if (!empty($insertField2)) {
            //     $insertData[$insertField2] = $insertValue2;
            // }            
            $ci->db->insert('cberp_transaction_tracking', $insertData);
        } 
    }
    else{
        $insertData = array(
            $insertField1 => $insertValue1
        );
        // if (!empty($insertField2)) {
        //     $insertData[$insertField2] = $insertValue2;
        // }
        $ci->db->insert('cberp_transaction_tracking', $insertData);
    }
}
 function insertion_to_tracking_table_sales_to_invoice($insertField1, $insertValue1, $insertField2="", $insertValue2="", $searchField = "", $searchValue = "")
{
    $ci =& get_instance();   
    if($searchField) 
    {
        $ci->db->select('lead_id,lead_number,quote_number,sales_number');
        $ci->db->from('cberp_transaction_tracking');
        if (!empty($searchField) && !empty($searchValue)) {
            $ci->db->where('cberp_transaction_tracking.' . $searchField, $searchValue);
        }
        $ci->db->order_by('id', 'DESC');
        $ci->db->limit(1);
        $query = $ci->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $insertData = array();
            foreach ($row as $key => $value) {
                if ($key != 'id') {
                    $insertData[$key] = $value;
                }
            }
            $insertData[$insertField1] = $insertValue1;
            if (!empty($insertField2)) {
                $insertData[$insertField2] = $insertValue2;
            }            
            $ci->db->insert('cberp_transaction_tracking', $insertData);
           
        } 
    }
    else{
        $insertData = [
            $insertField1 => $insertValue1,
        ];

        if (!empty($insertField2)) {
            $insertData[$insertField2] = $insertValue2;
        }
        $ci->db->insert('cberp_transaction_tracking', $insertData);
    }
}

function remove_after_last_dash($str) {
    $last_dash_pos = strrpos($str, '-');
    if ($last_dash_pos !== false) {
        return substr($str, 0, $last_dash_pos);
    }
    return $str;
}

function get_customer_details_by_id($customer_id)
{
    $ci =& get_instance();
    $ci->db->select('customer_id,name,credit_limit,credit_period,avalable_credit_limit');
    $ci->db->from('cberp_customers');
    $ci->db->where('customer_id', $customer_id);
    $query = $ci->db->get();
    return $query->row_array();
}

// /get_color_code()
function get_color_code($passed_date)
{
    $today = date('Y-m-d');    
    $ci =& get_instance();   
    $ci->db->select('due_date_1, due_date_color_1, due_date_2, due_date_color_2, due_date_3, due_date_color_3, due_date_expired_color, due_date_default_color');
    $ci->db->from('cberp_default_validity');  
    $query = $ci->db->get();
    $result = $query->row_array();
    
    if (!$result) {
        return null;
    }
    
    $due_date_1 = date('Y-m-d', strtotime($today . " +{$result['due_date_1']} days"));
    $due_date_2 = date('Y-m-d', strtotime($today . " +{$result['due_date_2']} days"));
    $due_date_3 = date('Y-m-d', strtotime($today . " +{$result['due_date_3']} days"));
    if ($passed_date < $today) {
        return $result['due_date_expired_color'];
    } 
    elseif ($passed_date <= $due_date_1) {
        return $result['due_date_color_1'];
    } elseif ($passed_date <= $due_date_2) {
        return $result['due_date_color_2'];
    } elseif ($passed_date <= $due_date_3) {
        return $result['due_date_color_3'];
    }
    else {
        return $result['due_date_default_color'];
    }
}

function check_permission($permissions) {
    $ci =& get_instance();   
    if (empty($permissions)) {
        return  '<div class="no-permission"><h3>' . $ci->lang->line('Sorry') . '</h3></div>';       
    }
    return true;
}

function print_settings_details($printing_type)
{
    $ci =& get_instance();  
    $ci->db->select('*');
    $ci->db->from('cberp_print_settings');
    $ci->db->where('printing_type',$printing_type);
    $query = $ci->db->get();
    return $query->row_array();
}

function convertToMillimeters($value, $unit = 'mm', $dpi = 96) {
    $unit = strtolower(trim($unit));    
    switch ($unit) {
        case 'mm':
            return $value;
        case 'cm':
            return $value * 10; // 1 cm = 10 mm
        case 'm':
            return $value * 1000; // 1 meter = 1000 mm
        case 'in':
        case 'inch':
        case 'inches':
            return $value * 25.4; // 1 inch = 25.4 mm
        case 'pt':
            return $value * 0.352778; // 1 point (pt) = 0.352778 mm
        case 'pc':
            return $value * 4.233333; // 1 pica (pc) = 4.233333 mm
        case 'px':
            return ($value / $dpi) * 25.4; // convert px -> inch -> mm
        default:
            throw new Exception("Unsupported unit type: $unit");
    }
}

function get_prefix_73()
{  
   $ci =& get_instance();
   $ci->db->select('name AS default_invoice_print,key1 AS invoicereceipt_prefix, key2 AS invoicereturnreceipt_prefix,  url AS purcahepayment_prefix');
   $ci->db->from('univarsal_api');
   $ci->db->where('id', 73);
   $query = $ci->db->get();
   return $query->row_array();

}

function getCommonDateRanges($today = null)
{
    // Use today's date if not provided
    $today = $today ? new DateTime($today) : new DateTime();

    // Start of the current month
    $month = $today->format('Y-m-01');

    // Start of the last 7 days (week-like)
    $dayOfMonth = (int)$today->format('d');
    if ($dayOfMonth <= 7) {
        // Not enough days in current month; fallback to current date
        $week = $today->format('Y-m-01');
    } else {
        // Subtract 7 days to get last week's start
        $weekStart = clone $today;
        $weekStart->modify('-6 days');
        $week = $weekStart->format('Y-m-d');
    }

    // Start of the current quarter (3 months ago from today)
    $quarterStart = clone $today;
    $quarterStart->modify('first day of -3 months');
    $quarter = $quarterStart->format('Y-m-01');

    // Start of the current year
    $year = $today->format('Y-01-01');

    return [
        'today'   => $today->format('Y-m-d'),
        'month'   => $month,
        'week'    => $week,
        'quarter' => $quarter,
        'year'    => $year,
    ];
}


function product_category_list(){
    $ci =& get_instance();
    $query = $ci->db->query("SELECT `cberp_product_category`.`category_id`,`cberp_product_category_description`.`name` FROM `cberp_product_category`
    JOIN `cberp_product_category_description` ON `cberp_product_category_description`.`category_id` = cberp_product_category.`category_id`
    GROUP BY cberp_product_category.`category_id`");
    $result = $query->result_array();
    return $result;
 }

 function product_files($files,$product_code) {
    $ci =& get_instance();
    $ci->load->library('upload/');
    // Set upload configuration
    $config['upload_path'] = FCPATH . 'userfiles/product/extraimages/';
    $config['allowed_types'] = 'pdf|jpg|jpeg|png|csv|xls|xlsx';
    $config['encrypt_name'] = TRUE;

    $uploaded_files = []; // To store the details of successfully uploaded files

    if (!empty($files['name'])) {
        foreach ($files['name'] as $key => $filename) {
            // Prepare the file for upload
            $_FILES['userfile']['name'] = $files['name'][$key];
            $_FILES['userfile']['type'] = $files['type'][$key];
            $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['userfile']['error'] = $files['error'][$key];
            $_FILES['userfile']['size'] = $files['size'][$key];

            $config['file_name'] = $files['name'][$key];
            $ci->upload->initialize($config);

            // Attempt to upload the file
            if ($ci->upload->do_upload('userfile')) {
                $uploaded_info = $ci->upload->data();
                $uploaded_data = [
                    'product_code' => $product_code,
                    'image' => $uploaded_info['file_name']
                ];
                $ci->db->insert('cberp_product_images', $uploaded_data);
                echo  $ci->db->last_query();
                $uploaded_files[] = $uploaded_data;
            } else {
                log_message('error', 'File upload error: ' . $ci->upload->display_errors());
            }
        }
    }
}

function get_product_files($product_code){
    $ci =& get_instance();   
    $ci->db->select('*');
    $ci->db->from('cberp_product_images'); 
    $ci->db->where('product_code', $product_code);
    $query = $ci->db->get();
    return $query->result_array();
}

function default_warehouse()
{   
    $ci =& get_instance();
    $ci->db->select('store_id,store_name');
    $ci->db->from('cberp_store');
    $ci->db->where('warehouse_type','Main');
    $ci->db->limit(1);
    $query = $ci->db->get();
    return $query->row_array();
}

function module_level_users($module_id){
    $ci =& get_instance();
    $query = "SELECT  first_level_approval,second_level_approval,third_level_approval,user_id,module_id,cberp_employees.name FROM cberp_menu_user_module_approval";
    $query .= " JOIN cberp_employees on cberp_employees.id = cberp_menu_user_module_approval.user_id";
    $query .= " WHERE cberp_menu_user_module_approval.module_id = '$module_id'";
    $query .= " AND (
                  first_level_approval != 'No' 
                  OR second_level_approval != 'No' 
                  OR third_level_approval != 'No'
              )";
    $query1 = $ci->db->query($query);
    $result = $query1->result_array();
    return $result;
}

function module_number_name($module_name) {
    $ci =& get_instance();
    $ci->db->select('module_number');
    $ci->db->from('cberp_modules');
    $ci->db->where('module_name', $module_name);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        return $query->row()->module_number;
    } else {
        return 0;
    }
}

function function_approved_levels($module_number,$function_number) 
{
    $ci =& get_instance();
    $ci->db->select('cberp_approval.approval_step');
    $ci->db->from('cberp_approval');
    $ci->db->where('module_number', $module_number);
    $ci->db->where('function_number', $function_number);
    $query = $ci->db->get();
    return $query->result_array();   
}

function getCurrentUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    $host = $_SERVER['HTTP_HOST'];      
    $requestUri = $_SERVER['REQUEST_URI']; 

    return $protocol . $host . $requestUri;
}