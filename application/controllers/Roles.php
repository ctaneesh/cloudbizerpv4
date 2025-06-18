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
        // if ($this->aauth->get_user()->roleid < 4) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }


    }

    public function index()
    {
        $data['permissions'] = load_permissions('User Permissions','Permission Actions','Roles');
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
   
        // $permissions = $this->roles->load_permissions();
        $data['role_id'] = $this->input->get('role', true);
        $data['linked_menus'] = linked_menus_by_roles_id($data['role_id']);
        $data['linked_modules_by_roleid'] = linked_modules_by_roleid($data['role_id']);
        $data['roles'] = get_roles();
        $data['modules'] = get_modules();
        $menuDetails = get_all_active_menus();         
        $data['linked_approvals'] = [];   
        if($data['role_id'])
        {  
            $data['linked_approvals'] = linked_role_module_approvals($data['role_id']);
        }
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
                  $mainarray[$main_menu][$submenu1] = ['menu_id' => [], 'functions' => []];
              }
              $mainarray[$main_menu][$submenu1]['menu_id'][] = $menu_id;
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

   

        $data['mainarray'] = $mainarray;
        $data['subarray'] = $subarray;
        $data['lastarray'] = $lastarray;    
        $this->load->view('fixed/header', $head);
        $this->load->view('roles/role_permissions', $data);
        $this->load->view('fixed/footer');
    }

   

    public function role_permission_addedit_action()
    {
        // Get the JSON input
        $inputData = json_decode(file_get_contents('php://input'), true);

        // Extract selectedMenus and role_id from the input data
        $selectedMenus = isset($inputData['selectedMenus']) ? $inputData['selectedMenus'] : null;
        $role_id = isset($inputData['role_id']) ? $inputData['role_id'] : null;
        $selectedApprovals = isset($inputData['selectedApprovals']) ? $inputData['selectedApprovals'] : null;
        if (empty($selectedMenus)) {
            $response = [
                'status' => 'error',
                'message' => 'No menus selected.'
            ];
        } else {
            $preparedArray = [];
            $permissionArray = [];
            foreach ($selectedMenus as $menu) {
                $preparedArray[] = [
                    'role_id' => $role_id,
                    'menu_link_id' => $menu['menu_id']
                ];
            }
            $approval_link_id = $this->roles->get_latest_role_approval_link_id();
            foreach ($selectedApprovals as $approval) {
                $permissionArray[] = [
                    'role_id' => $role_id,
                    'approval_link_id' => $approval_link_id+1,
                    'first_level_approval' => $approval['first_level_approval'],
                    'second_level_approval' => $approval['second_level_approval'],
                    'third_level_approval' => $approval['third_level_approval'],
                    'module_id' => $approval['module_id'],
                    'created_by' => $this->session->userdata('id'),
                    'created_date_time' => date('Y-m-d H:i:s'),
                ];
            }
            if($permissionArray)
            {
                // $this->roles->copy_from_user_module_approval_to_log($user_id);
                $this->db->delete('cberp_menu_role_module_approval',['role_id' => $role_id]);
                $this->db->insert_batch('cberp_menu_role_module_approval',$permissionArray);
            }
            if($preparedArray)
            {
                $this->db->delete('cberp_role_menu_links',['role_id' => $role_id]);
                $this->db->insert_batch('cberp_role_menu_links',$preparedArray);
           
            }
        }
        echo json_encode(array('status' => 'Success'));
    }

    public function set_permissions_for_the_user()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['user_id'] = $this->input->get('user', true);
        $data['linked_menus'] = [];
        $data['linked_modules_by_roleid'] =[];
        $data['linked_approvals'] = [];
        $data['role_id'] = "";
        $data['menus_approvels'] ="";
        $data['roles'] = get_roles();
        $data['modules'] = get_modules();
        $data['menu_log'] = 0;
        if($data['user_id'])
        {
            $data['linked_menus'] = linked_menus_by_user_id($data['user_id']);
            // $data['approval_levels'] = $this->roles->linked_menus_approvel_by_user_id($data['user_id']);
            $data['role_id'] = role_by_userid($data['user_id']);            
            $data['linked_modules_by_roleid'] = linked_modules_by_user_id($data['user_id']);
            $data['linked_approvals'] = linked_user_module_approvals($data['user_id']);
            $data['menu_log'] = $this->roles->check_prvious_data_in_menu_log($data['user_id']);
        }
        // echo "<pre>"; print_r($data['linked_approvals']); die();
        //if user is not linked with any menus
       
        if(empty($data['linked_menus']) && $data['role_id'])
        {
            $data['linked_menus'] = linked_menus_by_roles_id($data['role_id']);
            $data['linked_modules_by_roleid'] = linked_modules_by_roleid($data['role_id']);
            $data['linked_approvals'] = linked_role_module_approvals($data['role_id']);
        }
      
        $data['roles'] = get_roles();        
        $data['modules'] = get_modules();
        $menuDetails = get_all_active_menus();
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
                  $mainarray[$main_menu][$submenu1] = ['menu_id' => [], 'functions' => []];
              }
              $mainarray[$main_menu][$submenu1]['menu_id'][] = $menu_id;
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

        $data['mainarray'] = $mainarray;
        $data['subarray'] =  $subarray;
        $data['lastarray'] = $lastarray;  
        $data['users'] = load_active_employees_or_users();  
        $data['permissions'] = load_permissions('User Permissions','Permission Actions','User Menu Mapping');  
        $this->load->view('fixed/header', $head);
        $this->load->view('roles/user_permissions', $data);
        $this->load->view('fixed/footer');
    }



    public function user_permission_addedit_action()
    {
        //  ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        // Get the JSON input
        
        $inputData = json_decode(file_get_contents('php://input'), true);

        $selectedMenus = isset($inputData['selectedMenus']) ? $inputData['selectedMenus'] : null;
        $selectedApprovals = isset($inputData['selectedApprovals']) ? $inputData['selectedApprovals'] : null;
        $user_id = isset($inputData['user_id']) ? $inputData['user_id'] : null;

        // $first_level_approval = isset($inputData['first_level_approval']) ? $inputData['first_level_approval'] : null;
        // $second_level_approval = isset($inputData['second_level_approval']) ? $inputData['second_level_approval'] : null;
        // $third_level_approval = isset($inputData['third_level_approval']) ? $inputData['third_level_approval'] : null;
        // echo "<pre>"; print_r($selectedApprovals); die();
        if (empty($selectedMenus)) {
            $response = [
                'status' => 'error',
                'message' => 'No menus selected.'
            ];
        } 
        else {
            $preparedArray = [];
            $permissionArray = [];
            foreach ($selectedMenus as $menu) {
                $preparedArray[] = [
                    'user_id' => $user_id,
                    'menu_link_id' => $menu['menu_id'],
                    'created_by' => $this->session->userdata('id'),
                    'created_date_time' => date('Y-m-d H:i:s'),
                ];
            }
            $approval_link_id = $this->roles->latest_approval_link_id();
            foreach ($selectedApprovals as $approval) {
                $permissionArray[] = [
                    'user_id' => $user_id,
                    'approval_link_id' => $approval_link_id+1,
                    'first_level_approval' => $approval['first_level_approval'],
                    'second_level_approval' => $approval['second_level_approval'],
                    'third_level_approval' => $approval['third_level_approval'],
                    'module_id' => $approval['module_id'],
                    'created_by' => $this->session->userdata('id'),
                    'created_date_time' => date('Y-m-d H:i:s'),
                ];
            }


            if($permissionArray)
            {
                $this->roles->copy_from_user_module_approval_to_log($user_id);
                $this->db->delete('cberp_menu_user_module_approval',['user_id' => $user_id]);
                $this->db->insert_batch('cberp_menu_user_module_approval',$permissionArray);
            }
            if($preparedArray)
            {
                $this->roles->copy_from_user_menu_links_to_log($user_id);
                $this->db->delete('cberp_user_menu_links',['user_id' => $user_id]);
                $this->db->insert_batch('cberp_user_menu_links',$preparedArray);
            }
        }
        echo json_encode(array('status' => 'Success'));
    }


    public function prepare_menu_arrays()
    {
       
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
                    $mainarray[$main_menu][$submenu1] = ['menu_id' => [], 'functions' => []];
                }
                $mainarray[$main_menu][$submenu1]['menu_id'][] = $menu_id;
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
            // Print main menu with menu ID
            echo "<strong>$main_menu</strong><br>";
    
            // Loop through submenu1
            foreach ($submenu1Array as $submenu1 => $submenuData) {
                if (!is_array($submenuData)) continue; // Skip if not an array
                $menu_ids = implode(', ', $submenuData['menu_id']);
                echo "&nbsp;&nbsp;&nbsp;&nbsp;$submenu1 (Menu ID: $menu_ids)<br>";
    
                // Print functions under submenu1
                foreach ($submenuData['functions'] as $function) {
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$function - $menu_ids<br>";
                }
    
                // Check for submenu2 in subarray
                if (isset($subarray[$main_menu][$submenu1])) {
                    // print_r($submenu2Data);
                    foreach ($subarray[$main_menu][$submenu1] as $submenu2 => $submenu2Data) {
                        $submenu2_ids = implode(', ', $submenu2Data['menu_id']);
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$submenu2</strong> (Menu ID: $submenu2_ids)<br>";
    
                        // Print functions under submenu2
                        foreach ($submenu2Data['functions'] as $index => $subFunction) {
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$subFunction - {$submenu2Data['menu_id'][$index]}<br>";
                        }
    
                        // Check for menu_detail in lastarray
                        if (isset($lastarray[$main_menu][$submenu1][$submenu2])) {
                            foreach ($lastarray[$main_menu][$submenu1][$submenu2] as $menu_detail => $menuDetailData) {
                                $menu_detail_ids = implode(', ', $menuDetailData['menu_id']);
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$menu_detail</strong> (Menu ID: $menu_detail_ids)<br>";
    
                                // Print functions under menu_detail
                                foreach ($menuDetailData['functions'] as $index => $detailFunction) {
                                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$detailFunction - {$menuDetailData['menu_id'][$index]}<br>";
                                }
                            }
                        }
                    }
                }
            }
        }
        echo "</pre>";
    }
    
   

    public function load_roles_by_id()
    {
        $roledetails = $this->roles->load_roles_by_id($this->input->post('role_id')); 
        echo json_encode(array('status' => 'Success', 'data' => $roledetails));
    }

    public function update_user_role()
    {
    
          ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $role_id = $this->input->post('role_id');
        $user_id = $this->input->post('user_id');
        $this->db->update('cberp_users',['roleid'=>$role_id],['id'=> $user_id]);
        $menuslinked = linked_menus_by_roles_id($role_id);        
        $selectedApprovals = $this->roles->linked_menus_approvel_by_role_id($role_id);        
        $preparedArray = [];
        $permissionArray = [];
        $approval_link_id = $this->roles->latest_approval_link_id();
        foreach ($selectedApprovals as $approval) {
            $permissionArray[] = [
                'user_id' => $user_id,
                'approval_link_id' => $approval_link_id+1,
                'first_level_approval' => $approval['first_level_approval'],
                'second_level_approval' => $approval['second_level_approval'],
                'third_level_approval' => $approval['third_level_approval'],
                'module_id' => $approval['module_id'],
                'created_by' => $this->session->userdata('id'),
                'created_date_time' => date('Y-m-d H:i:s'),
            ];
        }
        foreach ($menuslinked as $menu) {
            $preparedArray[] = [
                'user_id' => $user_id,
                'menu_link_id' => $menu['menu_link_id']
            ];
        }
        if($preparedArray)
        {
            $this->roles->copy_from_user_menu_links_to_log($user_id);
            $this->roles->copy_from_user_module_approval_to_log($user_id);
            $this->db->delete('cberp_user_menu_links',['user_id' => $user_id]);
            $this->db->delete('cberp_menu_user_module_approval',['user_id' => $user_id]);
            $this->db->insert_batch('cberp_user_menu_links',$preparedArray);
            $this->db->insert_batch('cberp_menu_user_module_approval',$permissionArray);
        }

        echo json_encode(array('status' => 'Success'));
    }


    public function reset_and_load_role_permissions()
    {
        
        $role_id = $this->input->post('role_id');
        $user_id = $this->input->post('user_id');

        $menuslinked = linked_menus_by_roles_id($role_id);        
        $selectedApprovals = $this->roles->linked_menus_approvel_by_role_id($role_id);        
        $preparedArray = [];
        $permissionArray = [];
        $approval_link_id = $this->roles->latest_approval_link_id();
        foreach ($selectedApprovals as $approval) {
            $permissionArray[] = [
                'user_id' => $user_id,
                'approval_link_id' => $approval_link_id+1,
                'first_level_approval' => $approval['first_level_approval'],
                'second_level_approval' => $approval['second_level_approval'],
                'third_level_approval' => $approval['third_level_approval'],
                'module_id' => $approval['module_id'],
                'created_by' => $this->session->userdata('id'),
                'created_date_time' => date('Y-m-d H:i:s'),
            ];
        }
        foreach ($menuslinked as $menu) {
            $preparedArray[] = [
                'user_id' => $user_id,
                'menu_link_id' => $menu['menu_link_id']
            ];
        }
        if($preparedArray)
        {
            $this->roles->copy_from_user_menu_links_to_log($user_id);
            $this->roles->copy_from_user_module_approval_to_log($user_id);
            $this->db->delete('cberp_menu_user_module_approval',['user_id' => $user_id]);
            $this->db->delete('cberp_user_menu_links',['user_id' => $user_id]);
            $this->db->insert_batch('cberp_user_menu_links',$preparedArray);
            $this->db->insert_batch('cberp_menu_user_module_approval',$permissionArray);
        }
        echo json_encode(array('status' => 'Success'));
    }

    public function reload_previous_menus()
    { 
        $role_id = $this->input->post('role_id');
        $user_id = $this->input->post('user_id');

        $menuslinked = $this->roles->fetch_from_log_to_user_menu_links($user_id);    
        
        echo json_encode(array('status' => 'Success'));
    }
    function menu_report()
    {
        $data = [];
        $head['title'] = "Customer Menu Report";
        $dat= $this->input->post('customer');  
        $_SESSION['ReportData'] = [       
        
                  'customer' => $customer
              ];
    
  
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/menu_report', $data);
        $this->load->view('fixed/footer');
    }
    
   
  function menu_report1()
  {
 
        $data['user_id'] = $this->input->get('user', true);
        $data['linked_menus'] = [];
        $data['linked_modules_by_roleid'] =[];
        $data['linked_approvals'] = [];
        $data['role_id'] = "";
        $data['menus_approvels'] ="";
        $data['roles'] = get_roles();
        $data['modules'] = get_modules();
        $data['menu_log'] = 0;
        if($data['user_id'])
        {
            $data['linked_menus'] = linked_menus_by_user_id($data['user_id']);
            // $data['approval_levels'] = $this->roles->linked_menus_approvel_by_user_id($data['user_id']);
            $data['role_id'] = role_by_userid($data['user_id']);            
            $data['linked_modules_by_roleid'] = linked_modules_by_user_id($data['user_id']);
            $data['linked_approvals'] = linked_user_module_approvals($data['user_id']);
            $data['menu_log'] = $this->roles->check_prvious_data_in_menu_log($data['user_id']);
        }
        // echo "<pre>"; print_r($data['linked_approvals']); die();
        //if user is not linked with any menus
        if(empty($data['linked_menus']) && $data['role_id'])
        {
            $data['linked_menus'] = linked_menus_by_roles_id($data['role_id']);
            // $data['approval_levels'] = $this->roles->linked_menus_approvel_by_role_id($data['user_id']);
            $data['linked_modules_by_roleid'] = linked_modules_by_roleid($data['role_id']);
        }
        
        
        $data['roles'] = get_roles();        
        $data['modules'] = get_modules();
        $menuDetails = get_all_active_menus();
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
                  $mainarray[$main_menu][$submenu1] = ['menu_id' => [], 'functions' => []];
              }
              $mainarray[$main_menu][$submenu1]['menu_id'][] = $menu_id;
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

        $data['mainarray'] = $mainarray;
        $data['subarray'] =  $subarray;
        $data['lastarray'] = $lastarray;  
        $data['users'] = load_active_employees_or_users();    
        $this->load->view('fixed/header', $head);
        $this->load->view('roles/user_permissions1', $data);
        $this->load->view('fixed/footer');
        

  }
  public function set_permissions_for_the_user1()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $data['user_id'] = $this->input->get('user', true);
        $data['linked_menus'] = [];
        $data['linked_modules_by_roleid'] =[];
        $data['linked_approvals'] = [];
        $data['role_id'] = "";
        $data['menus_approvels'] ="";
        $data['roles'] = get_roles();
        $data['modules'] = get_modules();
        $data['menu_log'] = 0;
        if($data['user_id'])
        {
            $data['linked_menus'] = linked_menus_by_user_id($data['user_id']);
            // $data['approval_levels'] = $this->roles->linked_menus_approvel_by_user_id($data['user_id']);
            $data['role_id'] = role_by_userid($data['user_id']);            
            $data['linked_modules_by_roleid'] = linked_modules_by_user_id($data['user_id']);
            $data['linked_approvals'] = linked_user_module_approvals($data['user_id']);
            $data['menu_log'] = $this->roles->check_prvious_data_in_menu_log($data['user_id']);
        }
        // echo "<pre>"; print_r($data['linked_approvals']); die();
        //if user is not linked with any menus
        if(empty($data['linked_menus']) && $data['role_id'])
        {
            $data['linked_menus'] = linked_menus_by_roles_id($data['role_id']);
            // $data['approval_levels'] = $this->roles->linked_menus_approvel_by_role_id($data['user_id']);
            $data['linked_modules_by_roleid'] = linked_modules_by_roleid($data['role_id']);
        }
        
        
        $data['roles'] = get_roles();        
        $data['modules'] = get_modules();
        $menuDetails = get_all_active_menus();
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
                  $mainarray[$main_menu][$submenu1] = ['menu_id' => [], 'functions' => []];
              }
              $mainarray[$main_menu][$submenu1]['menu_id'][] = $menu_id;
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

        $data['mainarray'] = $mainarray;
        $data['subarray'] =  $subarray;
        $data['lastarray'] = $lastarray;  
        $data['users'] = load_active_employees_or_users();    
        $this->load->view('fixed/header', $head);
        $this->load->view('roles/user_permissions1', $data);
        $this->load->view('fixed/footer');
    }




}
