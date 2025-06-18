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

defined('BASEPATH') or exit('No direct script access allowed');

class Menus extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('menus_model', 'menus');
        $this->load->model('employee_model', 'employee');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        if ($this->aauth->get_user()->roleid < 4) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }


    }

    public function index()
    {
        $data['permissions'] = load_permissions('User Permissions','Permission Actions','Manage Menus');
        $head['title']   = "Menu Management";
        $head['usernm']  = $this->aauth->get_user()->username;        
        $data['modules'] = get_modules();
        $data['menus']   = get_menus();
        $data['details'] = $this->menus->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('user_permissions/menulist', $data);
        $this->load->view('fixed/footer');
    }

    public function addeditaction()
    {

         $main_menu = $this->input->post('main_menu', true);
         $submenu1 = $this->input->post('submenu1', true);
         $submenu2 = $this->input->post('submenu2', true);

         $menu_detail = $this->input->post('menu_detail', true);
         $function = $this->input->post('function', true);
         $status = $this->input->post('status', true);
         $menu_id = $this->input->post('menu_id', true);
         $menu_id_for_table = ($menu_id) ? $menu_id : get_latest_unique_number('menu_id','cberp_menu_details','id',100);
         $masterdata = [
             'menu_id' => $menu_id_for_table,
             'main_menu' => $main_menu,
             'submenu1' => ($submenu1) ? $submenu1 : NULL,
             'submenu2' => ($submenu2) ? $submenu2 : (NULL),
             'menu_detail' => ($menu_detail) ? $menu_detail : (NULL),
             'function' => ($function) ? $function : (NULL),
             'status' => $status
         ];
        
         if($menu_id)
         {
            $masterdata['updated_by'] =  $this->session->userdata('id');
            $masterdata['updated_dt'] = date('Y-m-d H:i:s');
            $this->db->update('cberp_menu_details',$masterdata,['menu_id' => $menu_id]);
         } 
         else{
            $masterdata['created_by'] =  $this->session->userdata('id');
            $masterdata['created_dt'] = date('Y-m-d H:i:s');
            $this->db->insert('cberp_menu_details',$masterdata);
         } 
        
         echo json_encode(array('status' => 'Success'));
    }
    public function create_coa_account(){
        $head['title'] = "New Chart of Account";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $data['accountheaders'] = $this->menus->load_coa_account_headers();
        $data['accounttypes'] = $this->menus->load_coa_account_types();
        $child = [];
        foreach($data['accounttypes'] as $row){
            $child[$row['coa_header_id']][] = $row;
        }      
        $data['child'] = $child;
    
        $data['details'] = $this->menus->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('coa/create_coa_account', $data);
        $this->load->view('fixed/footer');
        
    }

    public function menu_link_for_user_roles()
    {
        $all_menus = $this->employee->load_all_menus();
        $menus_with_modules = $this->employee->load_all_menus_with_modules();
        $merged_array =[];
        $menu_array =[];
        $menu_array_without_function =[];
        foreach($menus_with_modules as $row)
        {
            $merged_array[$row['module_number']][$row['menu_number']] = $row;
            foreach($all_menus as $item)
            {
                if($item['parent_menu_id'] == $row['menu_number'] || $item['menu_number'] == $row['menu_number'])
                {
                    if($item['function_name'])
                    {
                        
                        $menu_array_without_function[$row['menu_number']][] = $item;
                    }
                    else{
                        $menu_array[$row['menu_number']][] = $item;
                    }
                    // $menu_array[$row['menu_number']][] = $item;
                }
                    
            }
        }
        $data['roles'] = get_roles();
        $data['modules'] = get_modules();
        $data['menus_with_modules'] = $merged_array;
        $data['all_menus'] = $menu_array;
        $data['all_menuswithout_function'] = $menu_array_without_function;
        // echo "<pre>"; print_r($menu_array);  die();
            
        $this->load->view('fixed/header', $head);
        $this->load->view('user_permissions/user_role_menu_link', $data);
        $this->load->view('fixed/footer');
    }

    // public function load_menus_by_mainmenu()
    // {
     
 
    //     $results = $this->menus->load_menus_by_parameter_with_value('main_menu',$this->input->post('main_menu'),'submenu1');
    //     $optionvals = "";
    //     if($results)
    //     {
    //         foreach ($results as $option) {
    //             $value = htmlspecialchars($option['submenu1']);
    //             $label = htmlspecialchars($option['submenu1']);
    //             $optionvals .= '<option value="' . $value . '">' . $label . '</option>';
    //         }
    //     }
    //     echo json_encode(array('status' => 'Success', 'data' => $optionvals,));
    // }
    public function load_menus_by_mainmenu()
    {
     
 
        $results = $this->menus->load_menus_by_parameter_with_value('main_menu',$this->input->post('main_menu'),'submenu1');        
        echo json_encode(array('status' => 'Success', 'data' => $results));
    }

    public function load_menu_by_menuid()
    {     
        $menu_details = $this->menus->load_menu_by_menuid($this->input->post('menu_id')); 
        echo json_encode(array('status' => 'Success', 'data' => $menu_details));
    }

    public function deleteaction()
    {     
         $this->menus->delete_action($this->input->post('menu_id')); 
        echo json_encode(array('status' => 'Success'));
    }

}
