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

class Roles extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('roles_model', 'roles');
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
        $head['title']   = "User Roles";
        $head['usernm']  = $this->aauth->get_user()->username;  
        $data['details'] = $this->roles->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('roles/rolselist', $data);
        $this->load->view('fixed/footer');
    }

    public function addeditaction()
    {

         $role_name = $this->input->post('role_name', true);
         $status = $this->input->post('status', true);
         $role_id = $this->input->post('role_id', true);
         $role_number = ($role_id > 0) ? $role_id : get_latest_unique_number('role_id','cberp_roles','id',1000);
         $masterdata = [
             'role_id' => $role_number,
             'role_name' => $role_name,
             'status' => $status
         ];

         ($role_id > 0) ? $this->db->update('cberp_roles',$masterdata,['role_id'=> $role_number]) :  $this->db->insert('cberp_roles',$masterdata);

         echo json_encode(array('status' => 'Success'));
    }
  

    public function set_user_role_permissions()
    {
       
        $data['role_id'] = $this->input->get('role', true);
        $data['roles'] = get_roles();
        
        $data['modules'] = get_modules();
        $menuDetails = get_all_active_menus();
        // Initialize arrays
        $mainarray = [];
        $subarray = [];
        $lastarray = [];
    
        // Process the data
        // Process the data
    foreach ($menuDetails as $row) {
        $main_menu = $row['main_menu'];
        $submenu1 = $row['submenu1'];
        $submenu2 = $row['submenu2'];
        $menu_detail = $row['menu_detail'];
        $function = $row['function'];

        // $mainarray['main_menu']['submenu1']: submenu2 and menu_detail are null
        if (empty($submenu2) && empty($menu_detail)) {
            if (!isset($mainarray[$main_menu])) {
                $mainarray[$main_menu] = [];
            }
            if (!isset($mainarray[$main_menu][$submenu1])) {
                $mainarray[$main_menu][$submenu1] = [];
            }
            $mainarray[$main_menu][$submenu1][] = $function;
        }

        // $subarray['main_menu']['submenu1']['submenu2']: submenu2 is not null
        if (!empty($submenu2) && empty($menu_detail)) {
            if (!isset($subarray[$main_menu])) {
                $subarray[$main_menu] = [];
            }
            if (!isset($subarray[$main_menu][$submenu1])) {
                $subarray[$main_menu][$submenu1] = [];
            }
            if (!isset($subarray[$main_menu][$submenu1][$submenu2])) {
                $subarray[$main_menu][$submenu1][$submenu2] = [];
            }
            $subarray[$main_menu][$submenu1][$submenu2][] = $function;
        }

        // $lastarray['main_menu']['submenu1']['submenu2']['menu_detail']: all are present
        if (!empty($submenu2) && !empty($menu_detail)) {
            if (!isset($lastarray[$main_menu])) {
                $lastarray[$main_menu] = [];
            }
            if (!isset($lastarray[$main_menu][$submenu1])) {
                $lastarray[$main_menu][$submenu1] = [];
            }
            if (!isset($lastarray[$main_menu][$submenu1][$submenu2])) {
                $lastarray[$main_menu][$submenu1][$submenu2] = [];
            }
            if (!isset($lastarray[$main_menu][$submenu1][$submenu2][$menu_detail])) {
                $lastarray[$main_menu][$submenu1][$submenu2][$menu_detail] = [];
            }
            $lastarray[$main_menu][$submenu1][$submenu2][$menu_detail][] = $function;
        }
    }
        // echo "<pre>"; print_r($mainarray);  die();
        $data['mainarray'] = $mainarray;
        $data['subarray'] = $subarray;
        $data['lastarray'] = $lastarray;    
        $this->load->view('fixed/header', $head);
        $this->load->view('roles/role_permissions', $data);
        $this->load->view('fixed/footer');
    }
    public function prepare_menu_arrays()
    {
        // Load the database
        $this->load->database();
    
        // Fetch all records from the table
        $query = $this->db->get('cberp_menu_details');
        $menuDetails = $query->result_array();
    
        // Initialize arrays
        $mainarray = [];
        $subarray = [];
        $lastarray = [];
    
        // Process the data
        foreach ($menuDetails as $row) {
            $main_menu = $row['main_menu'];
            $submenu1 = $row['submenu1'];
            $submenu2 = $row['submenu2'];
            $menu_detail = $row['menu_detail'];
            $function = $row['function'];
            $menu_id = $row['menu_id'];
    
            // $mainarray['main_menu']['submenu1']: submenu2 and menu_detail are null
            if (empty($submenu2) && empty($menu_detail)) {
                if (!isset($mainarray[$main_menu])) {
                    $mainarray[$main_menu] = [];
                }
                if (!isset($mainarray[$main_menu][$submenu1])) {
                    $mainarray[$main_menu][$submenu1] = ['menu_id' => $menu_id, 'functions' => []];
                }
                $mainarray[$main_menu][$submenu1]['functions'][] = $function;
            }
    
            // $subarray['main_menu']['submenu1']['submenu2']: submenu2 is not null
            if (!empty($submenu2) && empty($menu_detail)) {
                if (!isset($subarray[$main_menu])) {
                    $subarray[$main_menu] = [];
                }
                if (!isset($subarray[$main_menu][$submenu1])) {
                    $subarray[$main_menu][$submenu1] = [];
                }
                if (!isset($subarray[$main_menu][$submenu1][$submenu2])) {
                    $subarray[$main_menu][$submenu1][$submenu2] = ['functions' => [], 'menu_id' => []];
                }
                $subarray[$main_menu][$submenu1][$submenu2]['functions'][] = $function;
                $subarray[$main_menu][$submenu1][$submenu2]['menu_id'][] = $menu_id;
            }
    
            // $lastarray['main_menu']['submenu1']['submenu2']['menu_detail']: all are present
            if (!empty($submenu2) && !empty($menu_detail)) {
                if (!isset($lastarray[$main_menu])) {
                    $lastarray[$main_menu] = [];
                }
                if (!isset($lastarray[$main_menu][$submenu1])) {
                    $lastarray[$main_menu][$submenu1] = [];
                }
                if (!isset($lastarray[$main_menu][$submenu1][$submenu2])) {
                    $lastarray[$main_menu][$submenu1][$submenu2] = [];
                }
                if (!isset($lastarray[$main_menu][$submenu1][$submenu2][$menu_detail])) {
                    $lastarray[$main_menu][$submenu1][$submenu2][$menu_detail] = ['functions' => [], 'menu_id' => []];
                }
                $lastarray[$main_menu][$submenu1][$submenu2][$menu_detail]['functions'][] = $function;
                $lastarray[$main_menu][$submenu1][$submenu2][$menu_detail]['menu_id'][] = $menu_id;
            }
        }
    
        // Print the hierarchical structure
        echo "<pre>";
      
  
            echo "<h3>Menu Hierarchy</h3>";
        
            // Loop through main menus
            foreach ($mainarray as $main_menu => $submenu1Array) {
                echo "<strong>$main_menu</strong><br>";
        
                // Loop through submenu1
                foreach ($submenu1Array as $submenu1 => $submenuData) {
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;$submenu1 (Menu ID: {$submenuData['menu_id']})<br>";
        
                    // Print functions under submenu1 with format: functionname - menuid
                    foreach ($submenuData['functions'] as $function) {
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$function - {$submenuData['menu_id']}<br>";
                    }
        
                    // Check for submenu2 in subarray
                    if (isset($subarray[$main_menu][$submenu1])) {
                        foreach ($subarray[$main_menu][$submenu1] as $submenu2 => $submenuData) {
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$submenu2</strong><br>";
        
                            // Print functions under submenu2 with format: functionname - menuid
                            foreach ($submenuData['functions'] as $index => $subFunction) {
                                $menu_id = $submenuData['menu_id'][$index];
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$subFunction - $menu_id<br>";
                            }
        
                            // Check for menu_detail in lastarray
                            if (isset($lastarray[$main_menu][$submenu1][$submenu2])) {
                                foreach ($lastarray[$main_menu][$submenu1][$submenu2] as $menu_detail => $menuData) {
                                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$menu_detail</strong><br>";
        
                                    // Print functions under menu_detail with format: functionname - menuid
                                    foreach ($menuData['functions'] as $index => $detailFunction) {
                                        $menu_id = $menuData['menu_id'][$index];
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$detailFunction - $menu_id<br>";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    
    
   
    




    public function load_roles_by_id()
    {
        $roledetails = $this->roles->load_roles_by_id($this->input->post('role_id')); 
        echo json_encode(array('status' => 'Success', 'data' => $roledetails));
    }




}
