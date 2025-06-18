<?php
/**
 * Cloud Biz Erp  Accounting,  Invoicing  and CRM Software
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

class Employee extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();
        
        $this->load->model('employee_model', 'employee');
        $this->load->library("Aauth");
        $this->load->model('country_model');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(9)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->li_a = 'emp';

    }

    public function index()
    {
       
        $data['permissions'] = load_permissions('HRM','Employees','Manage Employees');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employees List';
        $data['employee'] = $this->employee->list_employee_details();
        $data['warehouses'] = warehouse_list();
        $data['employees']  = employee_list();
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/list', $data);
        $this->load->view('fixed/footer');
    }

    public function salaries()
    {
        $data['permissions'] = load_permissions('HRM','Employees','Salaries');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employees List';
        $data['employee'] = $this->employee->list_employee();
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/salaries', $data);
        $this->load->view('fixed/footer');
    }


    public function view()
    {
        $id = $this->input->get('id');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employee Details';
        $data['employee'] = $this->employee->employee_details($id);
        $data['reportingemp'] = $this->employee->reporting_emp_byid($data['employee']['reportingto']);
        $data['expenseclaim'] = $this->employee->reporting_emp_byid($data['employee']['expense_claim_approver']);
        $data['eid'] = intval($id);
        $page = "employee";
        //erp2024 06-01-2025 detailed history log starts
        $data['detailed_log']= $this->employee->get_detailed_log($id,$page);
        $data['permissions'] = load_permissions('HRM','Employees','Manage Employees','View Page');
        // print_r($data['permissions']); die();
        $products = $data['detailed_log'];
      //  $dat = [];/
        // foreach($new_array as $row)
        // {
        //     foreach($row['seqence_number'] as $rw)
        //     {
        //         $x['y']=$row['new_value'];

        //     }
        // //    $dat['seqence_number']['new'] = $row['new_value'];
        //   //  $dat['seqence_number']['label'] = $row['field_label'];
        //    // $datanew['new'] = $new['new_value'];
        //    // $datanew['label'] = $new['field_label'];
        // }
        $groupedBySequence = []; // Initialize an empty array for grouping

        foreach ($products as $product) {
            $sequence = $product['seqence_number'];
            $groupedBySequence[$sequence][] = $product; // Group by sequence number
        }
        // $data['modules_by_role']= $this->employee->load_modules_by_roleid($data['employee']['roleid']);
        
        $data['groupedProducts'] = $groupedBySequence;
        $data['roles'] = get_roles();
        $data['modules'] = get_modules();
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
                    $menu_array[$row['menu_number']][] = $item;
                }
                    
            }
        }
        $data['menus_with_modules'] = $merged_array;
        $data['all_menus'] = $menu_array;
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/view', $data);
        $this->load->view('fixed/footer');

    }

    public function history()
    {
        $id = $this->input->get('id');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employee Details';
        $data['employee'] = $this->employee->employee_details($id);
        $data['history'] = $this->employee->salary_history($data['employee']['id']);
        $data['eid'] = intval($id);
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/history', $data);
        $this->load->view('fixed/footer');

    }


    public function add()
    {
        $data['permissions'] = load_permissions('HRM','Employees','New Employee');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Add Employee';
        $data['dept'] = $this->employee->department_list(0);
        $data['emplists'] = $this->employee->employee_lists();
        $data['countries'] = $this->country_model->country_list();
        $data['warehouses'] = $this->country_model->warehouse_list();
        $data['expense_approver'] = employee_list_with_roles();
        $data['roles'] = get_roles();
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/add', $data);
        $this->load->view('fixed/footer');

    }

    public function submit_user()
    {
        // if ($this->aauth->get_user()->roleid < 4) {
        //     redirect('/dashboard/', 'refresh');
        // }

        $username = $this->input->post('username', true);

        $password = $this->input->post('password', true);
        $roleid = 3;
        if ($this->input->post('roleid')) {
            $roleid = $this->input->post('roleid');

        }

        // if ($roleid > 3) {
        //     if ($this->aauth->get_user()->roleid < 5) {
        //         die('No! Permission');
        //     }
        // }

        $location = $this->input->post('location', true);
        $name = $this->input->post('name', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);
        $address = $this->input->post('address', true);
        $city = $this->input->post('city', true);
        $region = $this->input->post('region', true);
        $country = $this->input->post('country', true);
        $postbox = $this->input->post('postbox', true);
        $salary = numberClean($this->input->post('salary', true));
        $commission = $this->input->post('commission', true);
        $department = $this->input->post('department', true);

        // erp2024 newly added fields 03-06-2024 starts
        $nationality = $this->input->post('nationality', true);
        $residence_permit = $this->input->post('residence_permit', true);
        $expiry_date = $this->input->post('expiry_date', true);
        $passport_number = $this->input->post('passport_number', true);
        $passport_expiry = $this->input->post('passport_expiry', true);
        $passport_status = $this->input->post('passport_status', true);
        $join_date = $this->input->post('join_date', true); 
        $phonealt = $this->input->post('phonealt', true); 
        $emp_work_location = $this->input->post('emp_work_location', true); 
        $reportingto = $this->input->post('reportingto', true); 
        $amount_limit = $this->input->post('amount_limit', true);  
        $expense_claim_approver = $this->input->post('expense_claim_approver', true);  
        // erp2024 newly added fields 03-06-2024 ends

        $a = $this->aauth->create_user($email, $password, $username);
        if ((string)$this->aauth->get_user($a)->id != $this->aauth->get_user()->id) {
            $nuid = (string)$this->aauth->get_user($a)->id;
            if ($nuid > 0) {
                // erp2024 newly modified function 03-06-2024 starts
                $this->employee->add_employee($nuid, (string)$this->aauth->get_user($a)->username, $name, $roleid, $phone, $address, $city, $region, $country, $postbox, $location, $salary, $commission, $department, $nationality, $residence_permit, $expiry_date, $passport_number, $passport_expiry, $passport_status, $join_date, $phonealt,$emp_work_location, $reportingto, $amount_limit,$expense_claim_approver);
                // erp2024 newly modified function 03-06-2024 ends
            }

        } else {
            echo json_encode(array('status' => 'Error', 'message' => 'Entered email or username is already taken, please try again.'));
        }
    }

    public function invoices()
    {
        $id = $this->input->get('id');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employee Invoices';
        $data['employee'] = $this->employee->employee_details($id);
        $data['eid'] = intval($id);
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/invoices', $data);
        $this->load->view('fixed/footer');
    }

    public function invoices_list()
    {
        $eid = $this->input->post('eid');
        $list = $this->employee->invoice_datatables($eid);
        $data = array();

        $no = $this->input->post('start');


        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;
            // $row[] = $invoices->tid;
            $row[] = '<a href="' . base_url("invoices/create?id=$invoices->invoice_number") . '" title="View" target="_blank">'.$invoices->invoice_number.'</a>';
            $row[] = $invoices->name;
            $row[] = !empty($invoices->invoice_date) ? date('d-m-Y', strtotime($invoices->invoice_date)):"";
            $row[] = number_format($invoices->total, 2);
            switch ($invoices->status) {
                case "paid" :
                    $out = '<span class="label label-success st-paid">Paid</span> ';
                    break;
                case "due" :
                    $out = '<span class="label label-danger st-due">Due</span> ';
                    break;
                case "canceled" :
                    $out = '<span class="label label-warning st-canceled">Canceled</span> ';
                    break;
                case "partial" :
                    $out = '<span class="label label-primary st-partial">Partial</span> ';
                    break;
                default :
                    $out = '<span class="label label-info st-pending">Pending</span> ';
                    break;
            }
            $row[] = $out;
            $validtoken = hash_hmac('ripemd160', $invoices->invoice_number, $this->config->item('encryption_key'));  
            // base_url('billing/printinvoice?id=' . $invoice['iid'] . '&token=' . $validtoken);
            $row[] = '<a href="' . base_url('billing/printinvoice?id=' . $invoices->invoice_number . '&token=' . $validtoken) . '&d=1" class="btn btn-info btn-sm"  title="Download"><span class="fa fa-download"></span></a>';
            // $row[] = '<a href="' . base_url("invoices/create?id=$invoices->id") . '" class="btn btn-success btn-sm" title="View" target="_blank"><i class="fa fa-eye"></i></a> <a href="' . base_url("invoices/printinvoice?id=$invoices->id") . '&d=1" class="btn btn-info btn-sm"  title="Download"><span class="fa fa-download"></span></a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->employee->invoicecount_all($eid),
            "recordsFiltered" => $this->employee->invoicecount_filtered($eid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }
   

    
    public function quote()
    {
        $id = $this->input->get('id');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Quote List';
        $data['quote'] = $this->employee->quote_details($id);
        $data['eid'] = intval($id);
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/quotes', $data);
        $this->load->view('fixed/footer');
    }

    public function quote_list()
    {
        $eid = $this->input->post('eid');
        $list = $this->employee->quote_datatables($eid);
        // print_r($list); die();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $salebtn = '';
            // if($invoices->convertflg == '1'){
            //     $salesorderstatus = '<span class="st-Closed">' . $this->lang->line(ucwords("Received")) . '</span>';
            //     $salebtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->id") . '&token=1" class="btn btn-secondary btn-sm"  title="sales order">Sales Order(s)</span></a>';
            // }
            // else if($invoices->convertflg == '2'){
            //     $salesorderstatus = '<span class="st-partial">' . $this->lang->line(ucwords("Partially Received")) . '</span>';
            //     $salebtn = '<a href="' . base_url("SalesOrders/salesorder_new?id=$invoices->id") . '&token=1" class="btn btn-secondary btn-sm"  title="sales order">Sales Order(s)</span></a>';
            // }
            // else{
            //     $salebtn = '';
            //     $salesorderstatus ='';
            // }
            
            if($invoices->status=='pending'){
                $status = '<span class="st-Closed">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            }
            else if($invoices->status=='accepted'){
                $status = '<span class="st-accepted">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            }
            else if($invoices->status=='pending'){
                $status = '<span class="st-Closed">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            }
            else if($invoices->status=='rejected'){
                $status = '<span class="st-Closed">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            }
            else if($invoices->status=='customer_approved'){
                $status = '<span class="st-accepted">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            }
            else{
                $status = '<span class="st-accepted">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            }
                
            $approvstatus = '';
            // if($invoices->approvalflg=='1'){
            //     $approvstatus = '<span class="st-accepted">' . $this->lang->line('Approved') . '</span>';
            // }
            // else if($invoices->approvalflg=='2'){
            //     $approvstatus = '<span class="st-pending">' . $this->lang->line('Hold') . '</span>';
            // }
            // else if($invoices->approvalflg=='3'){
            //     $approvstatus = '<span class="st-Closed">' . $this->lang->line(ucwords('Reject')) . '</span>';
            // }
            // else if($invoices->prepared_flg=='0' && $invoices->approvalflg=='0'){
            //     $approvstatus = '';
            // }
            // else{
            //     $approvstatus = '<span class="st-Closed">' . $this->lang->line('Waiting for approval') . '</span>';
            // }
            $targeturl = '<a href="' . base_url("quote/create?id=$invoices->id") . '">&nbsp; ' . $invoices->tid . '</a>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $targeturl;
            // $approveddt = ($invoices->approved_dt)?(date('d-m-Y H:i:s',strtotime($invoices->approved_dt))):"";
            $row[] = $invoices->name;
            $row[] = dateformat($invoices->quote_date);
            $row[] = $invoices->total;
            $row[] = $approvstatus;
            $row[] = $status;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->employee->quote_count_all($eid),
            "recordsFiltered" => $this->employee->quote_count_filtered($eid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function transactions()
    {
        $id = $this->input->get('id');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employee Transactions';
        $data['employee'] = $this->employee->employee_details($id);
        $data['eid'] = intval($id);
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/transactions', $data);
        $this->load->view('fixed/footer');
    }

    public function translist()
    {
        $eid = $this->input->post('eid');
        $list = $this->employee->get_datatables($eid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $pid = $prd->id;
            $row[] = $prd->date;
            $row[] = '<a href="' . base_url() . 'transactions/view?id=' . $pid . '" title="View">'.$prd->account.'</a>';
            $row[] = amountExchange($prd->debit, 0, $this->aauth->get_user()->loc);
            $row[] = amountExchange($prd->credit, 0, $this->aauth->get_user()->loc);

            $row[] = $prd->payer;
            $row[] = $prd->method;
            $row[] = '<a data-object-id="' . $pid . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            // $row[] = '<a href="' . base_url() . 'transactions/view?id=' . $pid . '" class="btn btn-secondary btn-sm" title="View"><span class="icon-eye"></span></a> <a data-object-id="' . $pid . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->employee->count_all(),
            "recordsFiltered" => $this->employee->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    function disable_user()
    {
        if (!$this->aauth->get_user()->roleid == 5) {
            redirect('/dashboard/', 'refresh');
        }
        $uid = intval($this->input->post('deleteid'));

        $nuid = intval($this->aauth->get_user()->id);

        if ($nuid == $uid) {
            echo json_encode(array('status' => 'Error', 'message' =>
                'You can not disable yourself!'));
        } else {

            $this->db->select('banned');
            $this->db->from('cberp_users');
            $this->db->where('id', $uid);
            $query = $this->db->get();
            $result = $query->row_array();
            if ($result['banned'] == 0) {
                $this->aauth->ban_user($uid);
            } else {
                $this->aauth->unban_user($uid);
            }

            echo json_encode(array('status' => 'Success', 'message' =>
                'User Profile updated successfully!'));


        }
    }

    function enable_user()
    {
        if (!$this->aauth->get_user()->roleid == 5) {
            redirect('/dashboard/', 'refresh');
        }
        $uid = intval($this->input->post('deleteid'));

        $nuid = intval($this->aauth->get_user()->id);

        if ($nuid == $uid) {
            echo json_encode(array('status' => 'Error', 'message' =>
                'You can not disable yourself!'));
        } else {


            $a = $this->aauth->unban_user($uid);

            echo json_encode(array('status' => 'Success', 'message' =>
                'User Profile disabled successfully!'));


        }
    }

    function delete_user()
    {
        if (!$this->aauth->get_user()->roleid == 5) {
            redirect('/dashboard/', 'refresh');
        }
        $uid = intval($this->input->post('empid'));

        $nuid = intval($this->aauth->get_user()->id);

        if ($nuid == $uid) {
            echo json_encode(array('status' => 'Error', 'message' =>
                'You can not delete yourself!'));
        } else {

            $this->db->delete('cberp_employees', array('id' => $uid));

            $this->db->delete('cberp_users', array('id' => $uid));

            echo json_encode(array('status' => 'Success', 'message' =>
                'User Profile deleted successfully! Please refresh the page!'));


        }
    }


    public function calc_income()
    {
        $eid = $this->input->post('eid');

        if ($this->employee->money_details($eid)) {
            $details = $this->employee->money_details($eid);

            echo json_encode(array('status' => 'Success', 'message' =>
                '<br> Total Income: ' . amountExchange($details['credit'], 0, $this->aauth->get_user()->loc) . '<br> Total Expenses: ' . amountExchange($details['debit'], 0, $this->aauth->get_user()->loc)));

        }


    }

    public function calc_sales()
    {
        $eid = $this->input->post('eid');

        if ($this->employee->sales_details($eid)) {
            $details = $this->employee->sales_details($eid);

            echo json_encode(array('status' => 'Success', 'message' =>
                'Total Sales (Paid Payment):  ' . amountExchange($details['total'], 0, $this->aauth->get_user()->loc)));
       }


    }

    public function update()
    {
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }


        $id = $this->input->get('id');        
        $this->load->model('employee_model', 'employee');        
        $data['countries'] = $this->country_model->country_list();
        if ($this->input->post()) {

            $eid = $this->input->post('eid', true);
            

            $name = $this->input->post('name', true);
            $phone = $this->input->post('phone', true);
            $phonealt = $this->input->post('phonealt', true);
            $address = $this->input->post('address', true);
            $city = $this->input->post('city', true);
            $region = $this->input->post('region', true);
            $country = $this->input->post('country', true);
            $postbox = $this->input->post('postbox', true);
            $location = $this->input->post('location', true);
            $salary = numberClean($this->input->post('salary', true));
            $department = $this->input->post('department', true);
            $commission = $this->input->post('commission', true);
            $roleid = $this->input->post('roleid', true);

            // erp2024 newly added fields 03-06-2024 starts
            $nationality = $this->input->post('nationality', true);
            $residence_permit = $this->input->post('residence_permit', true);
            $expiry_date = $this->input->post('expiry_date', true);
            $passport_number = $this->input->post('passport_number', true);
            $passport_expiry = $this->input->post('passport_expiry', true);
            $passport_status = $this->input->post('passport_status', true);
            $join_date = $this->input->post('join_date', true); 
            $phonealt = $this->input->post('phonealt', true); 
            $emp_work_location = $this->input->post('emp_work_location', true);
            $reportingto = $this->input->post('reportingto', true); 
            $amount_limit = $this->input->post('amount_limit', true); 
            $expense_claim_approver = $this->input->post('expense_claim_approver', true); 
            // erp2024 newly added fields 03-06-2024 ends

            //erp2024 06-01-2025 detailed history log starts
            
            //parameters - pagename,item_no,actionname
            detailed_log_history('employee',$eid,'Updated', $_POST['changedFields']);
            //erp2024 06-01-2025 detailed history log ends 

            log_table_data('cberp_employees','cberp_employees_log', 'id' ,'employee_id','Update',$eid);
            $this->employee->update_employee($eid, $name, $phone, $phonealt, $address, $city, $region, $country, $postbox, $location, $salary, $department, $commission, $roleid,  $nationality, $residence_permit, $expiry_date, $passport_number, $passport_expiry, $passport_status, $join_date,$emp_work_location,$reportingto,$amount_limit,$expense_claim_approver);

        } else {
            $head['usernm'] = $this->aauth->get_user($id)->username;
            $head['title'] = $head['usernm'] . ' Profile';
            $data['user'] = $this->employee->employee_details($id);
            $data['dept'] = $this->employee->department_list($id, $this->aauth->get_user()->loc);
            $data['warehouses'] = $this->country_model->warehouse_list(); 
            $data['emplists'] = $this->employee->employee_lists($id);            
            $data['expense_approver'] = employee_list_with_roles();
            $data['roles'] = get_roles();
            $data['eid'] = intval($id);
            $this->load->view('fixed/header', $head);
            $this->load->view('employee/edit', $data);
            $this->load->view('fixed/footer');
        }


    }


    public function displaypic()
    {

        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }

        $this->load->model('employee_model', 'employee');
        $id = $this->input->get('id');
        $this->load->library("uploadhandler", array(
            'accept_file_types' => '/\.(gif|jpe?g|png)$/i', 'upload_dir' => FCPATH . 'userfiles/employee/'
        ));
        $img = (string)$this->uploadhandler->filenaam();
        if ($img != '') {
            $this->employee->editpicture($id, $img);
        }


    }


    public function user_sign()
    {
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }


        $this->load->model('employee_model', 'employee');
        $id = $this->input->get('id');
        $this->load->library("uploadhandler", array(
            'accept_file_types' => '/\.(gif|jpe?g|png)$/i', 'upload_dir' => FCPATH . 'userfiles/employee_sign/'
        ));
        $img = (string)$this->uploadhandler->filenaam();
        if ($img != '') {
            $this->employee->editsign($id, $img);
        }


    }


    public function updatepassword()
    {

        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        $this->load->library("form_validation");

        $id = $this->input->get('id');
        $this->load->model('employee_model', 'employee');


        if ($this->input->post()) {
            $eid = $this->input->post('eid');
            $this->form_validation->set_rules('newpassword', 'Password', 'required');
            $this->form_validation->set_rules('renewpassword', 'Confirm Password', 'required|matches[newpassword]');
            if ($this->form_validation->run() == FALSE) {
                echo json_encode(array('status' => 'Error', 'message' => '<br>Rules<br> Password length should  be at least 6 [a-z-0-9] allowed!<br>New Password & Re New Password should be same!'));
            } else {

                $newpassword = $this->input->post('newpassword');
                echo json_encode(array('status' => 'Success', 'message' => 'Password Updated Successfully!'));
                $this->aauth->update_user($eid, false, $newpassword, false);
            }


        } else {
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = $head['usernm'] . ' Profile';
            $data['user'] = $this->employee->employee_details($id);
            $data['eid'] = intval($id);
            $this->load->view('fixed/header', $head);
            $this->load->view('employee/password', $data);
            $this->load->view('fixed/footer');
        }


    }

    public function permissions()
    {
        $data['permissions'] = load_permissions('HRM','Employees','Permissions');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employee Permissions';
        $data['permission'] = $this->employee->employee_permissions();
        $data['employee'] = $this->employee->list_employee();
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/permissions', $data);
        $this->load->view('fixed/footer');


    }

    public function permissions_update()
    {

        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employee Permissions';
        $permission = $this->employee->employee_permissions();

        foreach ($permission as $row) {
            $i = $row['id'];
            $name1 = 'r_' . $i . '_1';
            $name2 = 'r_' . $i . '_2';
            $name3 = 'r_' . $i . '_3';
            $name4 = 'r_' . $i . '_4';
            $name5 = 'r_' . $i . '_5';
            $name6 = 'r_' . $i . '_6';
            $name7 = 'r_' . $i . '_7';
            $name8 = 'r_' . $i . '_8';
            $val1 = 0;
            $val2 = 0;
            $val3 = 0;
            $val4 = 0;
            $val5 = 0;
            $val6 = 0;
            $val7 = 0;
            $val8 = 0;
            if ($this->input->post($name1)) $val1 = 1;
            if ($this->input->post($name2)) $val2 = 1;
            if ($this->input->post($name3)) $val3 = 1;
            if ($this->input->post($name4)) $val4 = 1;
            if ($this->input->post($name5)) $val5 = 1;
            if ($this->input->post($name6)) $val6 = 1;
            if ($this->input->post($name7)) $val7 = 1;
            if ($this->aauth->get_user()->roleid == 5 && $i == 9) $val5 = 1;
            $data = array('r_1' => $val1, 'r_2' => $val2, 'r_3' => $val3, 'r_4' => $val4, 'r_5' => $val5, 'r_6' => $val6, 'r_7' => $val7);
            $this->db->set($data);
            $this->db->where('id', $i);
            $this->db->update('cberp_premissions');
        }

        echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('UPDATED')));
    }


    public function holidays()
    {
        $data['permissions'] = load_permissions('HRM','Employees','Holidays');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Holidays';
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/holidays');
        $this->load->view('fixed/footer');

    }


    public function hday_list()
    {
        $list = $this->employee->holidays_datatables();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $obj) {
            $datetime1 = date_create($obj->val1);
            $datetime2 = date_create($obj->val2);
            $interval = date_diff($datetime1, $datetime2);
            $day = $interval->format('%a days');
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $obj->val1;
            $row[] = $obj->val2;
            $row[] = $day;
            $row[] = $obj->val3;
            $row[] = "<a href='" . base_url("employee/editholiday?id=$obj->id") . "' class='btn btn-secondary btn-sm' title='Edit'><i class='fa fa-pencil'></i></a> " . '<a href="#" data-object-id="' . $obj->id . '" class="btn btn-secondary delete-object  btn-sm" title="Delete"><span class="fa fa-trash"></span></a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->employee->holidays_count_all(),
            "recordsFiltered" => $this->employee->holidays_count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function delete_hday()
    {
        $id = $this->input->post('deleteid');


        if ($this->employee->deleteholidays($id)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    public function addhday()
    {

        if ($this->input->post()) {

            $from = datefordatabase($this->input->post('from'));
            $todate = datefordatabase($this->input->post('todate'));
            $note = $this->input->post('note', true);

            $date1 = new DateTime($from);
            $date2 = new DateTime($todate);
            if ($date1 <= $date2) {


                if ($this->employee->addholidays($this->aauth->get_user()->loc, $from, $todate, $note)) {
                    echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') . "   <a href='addhday' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='holidays' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span>  </a>"));
                }
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR') . '- Invalid'));
            }
        } else {
            $data['id'] = $this->input->get('id');
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Add Holiday';
            $this->load->view('fixed/header', $head);
            $this->load->view('employee/addholyday', $data);
            $this->load->view('fixed/footer');
        }

    }


    public function editholiday()
    {

        if ($this->input->post()) {


            $id = $this->input->post('did');
            $from = datefordatabase($this->input->post('from'));
            $todate = datefordatabase($this->input->post('todate'));
            $note = $this->input->post('note', true);

            if ($this->employee->edithday($id, $this->aauth->get_user()->loc, $from, $todate, $note)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') . "  <a href='addhday' class='btn btn-indigo btn-sm'><span class='icon-plus-circle' aria-hidden='true'></span>  </a> <a href='holidays' class='btn btn-secondary btn-sm'><span class='icon-eye' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            $data['id'] = $this->input->get('id');
            $data['hday'] = $this->employee->hday_view($data['id'], $this->aauth->get_user()->loc);
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Edit Holiday';
            $this->load->view('fixed/header', $head);
            $this->load->view('employee/edithday', $data);
            $this->load->view('fixed/footer');
        }

    }


    public function departments()
    {
        $data['permissions'] = load_permissions('HRM','Departments','Manage Department');
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['department_list'] = $this->employee->department_list($this->aauth->get_user()->loc);
        $head['title'] = 'Departments';
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/departments', $data);
        $this->load->view('fixed/footer');

    }

    public function department()
    {

        $data['id'] = $this->input->get('id');
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['department'] = $this->employee->department_view($data['id'], $this->aauth->get_user()->loc);
        $data['department_list'] = $this->employee->department_elist($data['id']);
        $head['title'] = 'Departments';
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/department', $data);
        $this->load->view('fixed/footer');

    }

    public function delete_dep()
    {

        $id = $this->input->post('deleteid');


        if ($this->employee->deletedepartment($id)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    public function adddep()
    {

        if ($this->input->post()) {

            $name = $this->input->post('name', true);


            if ($this->employee->adddepartment($this->aauth->get_user()->loc, $name)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') . "  <a href='adddep' class='btn btn-indigo btn-sm'><span class='icon-plus-circle' aria-hidden='true'></span>  </a> <a href='departments' class='btn btn-secondary btn-sm'><span class='icon-eye' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {

            $data['permissions'] = load_permissions('HRM','Departments','New Department');
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Add Department';
            $this->load->view('fixed/header', $head);
            $this->load->view('employee/adddep',$data);
            $this->load->view('fixed/footer');
        }

    }

    public function editdep()
    {

        if ($this->input->post()) {

            $name = $this->input->post('name', true);
            $id = $this->input->post('did');

            if ($this->employee->editdepartment($id, $this->aauth->get_user()->loc, $name)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') . "  <a href='adddep' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='departments' class='btn btn-secondary btn-sm'><span class='icon-eye' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            $data['id'] = $this->input->get('id');
            $data['department'] = $this->employee->department_view($data['id'], $this->aauth->get_user()->loc);
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Edit Department';
            $this->load->view('fixed/header', $head);
            $this->load->view('employee/editdep', $data);
            $this->load->view('fixed/footer');
        }

    }

    public function payroll_create()
    {
        $data['permissions'] = load_permissions('HRM','Payroll','New Payroll');
        $this->load->library("Custom");
        $data['dual'] = $this->custom->api_config(65);
        $this->load->model('transactions_model', 'transactions');
        $data['cat'] = $this->transactions->categories();
        $data['accounts'] = $this->transactions->acc_list();
        $head['title'] = "Add Transaction";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/payroll_create', $data);
        $this->load->view('fixed/footer');

    }

    public function emp_search()
    {

        $name = $this->input->get('keyword', true);


        $whr = '';
        if ($this->aauth->get_user()->loc) {
            $whr = ' (cberp_users.loc=' . $this->aauth->get_user()->loc . ') AND ';
        }
        if ($name) {
            $query = $this->db->query("SELECT cberp_employees.* ,cberp_users.email FROM cberp_employees  LEFT JOIN cberp_users ON cberp_users.id=cberp_employees.id  WHERE $whr (UPPER(cberp_employees.name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(cberp_employees.phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
            $result = $query->result_array();
            echo '<ol>';
            $i = 1;
            foreach ($result as $row) {

                echo "<li onClick=\"selectPay('" . $row['id'] . "','" . $row['name'] . " ','" . amountFormat_general($row['salary']) . "')\"><span>$i</span><p>" . $row['name'] . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
                $i++;
            }
            echo '</ol>';
        }

    }

    public function payroll()
    {
        $data['permissions'] = load_permissions('HRM','Payroll','Manage Payroll');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employee Payroll Transactions';
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/payroll',$data);
        $this->load->view('fixed/footer');
    }

    public function payroll_emp()
    {

        $id = $this->input->get('id');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Employee Payroll Transactions';
        $data['employee'] = $this->employee->employee_details($id);
        $data['eid'] = intval($id);
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/payroll_employee', $data);
        $this->load->view('fixed/footer');
    }


    public function payrolllist()
    {

        $eid = $this->input->post('eid');
        $list = $this->employee->pay_get_datatables($eid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $pid = $prd->id;
            $row[] = '<a href="' . base_url() . 'transactions/view?id=' . $pid . '" title="View">'.$prd->date.'</a>';

            $row[] = amountExchange($prd->debit, 0, $this->aauth->get_user()->loc);
            $row[] = amountExchange($prd->credit, 0, $this->aauth->get_user()->loc);
            $row[] = $prd->account;
            $row[] = $prd->payer;
            $row[] = $prd->method;
            $row[] = '<a  href="#" data-object-id="' . $pid . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a> ';
            // $row[] = '<a href="' . base_url() . 'transactions/view?id=' . $pid . '" class="btn btn-secondary btn-sm" title="View"><span class="fa fa-eye"></span></a> <a  href="#" data-object-id="' . $pid . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a> ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->employee->pay_count_all($eid),
            "recordsFiltered" => $this->employee->pay_count_filtered($eid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function attendances()
    {
        $data['permissions'] = load_permissions('HRM','Employees','Attendence');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Attendance';
        $this->load->view('fixed/header', $head);
        $this->load->view('employee/attendance_list',$data);
        $this->load->view('fixed/footer');

    }

    public function attendance()
    {
        $data['permissions'] = load_permissions('HRM','Employees','Attendence');
        if ($this->input->post()) {
            $emp = $this->input->post('employee');
            $adate = datefordatabase($this->input->post('adate'));
            $from = timefordatabase($this->input->post('from'));
            $todate = timefordatabase($this->input->post('to'));
            $note = $this->input->post('note');

            if ($this->employee->addattendance($emp, $adate, $from, $todate, $note)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') . "  <a href='attendance' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='attendances' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            $data['emp'] = $this->employee->list_employee();
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'New Attendance';
            $this->load->view('fixed/header', $head);
            $this->load->view('employee/attendance', $data);
            $this->load->view('fixed/footer');
        }

    }

    public function auto_attendance()
    {
        if ($this->input->post()) {
            $auto_attand = $this->input->post('attend');

            if ($this->employee->autoattend($auto_attand)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('UPDATED')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            $this->load->model('plugins_model', 'plugins');

            $data['auto'] = $this->plugins->universal_api(62);


            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Auto Attendance';
            $this->load->view('fixed/header', $head);
            $this->load->view('employee/autoattend', $data);
            $this->load->view('fixed/footer');
        }

    }


    public function att_list()
    {
        $cid = $this->input->post('cid');
        $list = $this->employee->attendance_datatables($cid);
        $data = array();
        $no = $this->input->post('start');

        foreach ($list as $obj) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $obj->name;
            $row[] = dateformat($obj->adate) . ' &nbsp; ' . $obj->tfrom . ' - ' . $obj->tto;
            $row[] = round((strtotime($obj->tto) - strtotime($obj->tfrom)) / 3600, 2);
            $row[] = round($obj->actual_hours / 3600, 2);
            $row[] = $obj->note;

            $row[] = '<a href="#" data-object-id="' . $obj->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->employee->attendance_count_all($cid),
            "recordsFiltered" => $this->employee->attendance_count_filtered($cid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function delete_attendance()
    {
        $id = $this->input->post('deleteid');


        if ($this->employee->deleteattendance($id)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    public function set_authorization(){
        $id = $this->input->post('empid', true);
        $data = [
            'reportingto' => $this->input->post('reportingto', true),
            'amount_limit' => $this->input->post('amountto', true),
            'updated_by' => $this->session->userdata('id'),
            'updated_dt' => date('Y-m-d H:i:s'),
        ];
        $this->db->where('id', $id);
        $this->db->update('cberp_employees', $data);
        echo json_encode(array('status' => 'Success', 'message' =>'Authorization set successfully!'));

    }

    public function employeelist_ajax()
    {
        $this->load->model('employeelist_model', 'employeelist');
        $catid = $this->input->get('id');
        $sub = $this->input->get('sub');

        $list = $this->employeelist->get_datatables();
        // print_r($list); die();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            
            $pid = $prd->id;  
            $expiry_date = ($prd->expiry_date && $prd->expiry_date!='0000-00-00')?date('d-m-Y',strtotime($prd->expiry_date)):"";
            $passport_expiry = (!empty($prd->passport_expiry) && $prd->passport_expiry!='0000-00-00')?date('d-m-Y',strtotime($prd->passport_expiry)):"";
            $status = $prd->banned;
            if ($status == 1) {
                $status = '<span class="st-inactive">Inactive</span>';
                $btn = "<a href='#' data-object-id='" . $pid . "'  data-object1-id='" . $pid . "'  class='btn btn-secondary btn-sm delete-object' title='Enable'><i class='fa fa-thumbs-up'></i></a> <a href='#pop_model' data-toggle='modal' data-remote='false' data-object-id='" . $pid . "' class='btn btn-secondary btn-sm delemp' title='Delete'><i class='fa fa-trash'></i></a>";
            } else {
                $status = '<span class="st-active">Active</span>';
                $btn = "<a href='#' data-object-id='" . $pid . "' class='btn btn-secondary btn-sm delete-object' title='Disable'><i class='fa fa-thumbs-down'></i></a> 
                <a href='#pop_model' data-toggle='modal' data-remote='false' data-object-id='" . $pid . "' class='btn btn-secondary btn-sm delemp' title='Delete'><i class='fa fa-trash'></i></a>";
            }
            $btn .= " <a href='". base_url() . 'roles/set_permissions_for_the_user?user=' . $pid ."'  class='btn btn-secondary btn-sm' title='Permission'>Permission</a>";
            $no++;
            $row = array();
            $row[] = $no;         
            // $row[] = $prd->name; 
            $row[] = '<a href="' . base_url() . 'employee/view?id=' . $pid . '">' . $prd->name . '</a>';
            $row[] = user_role($prd->roleid);
            $row[] = $prd->phone;
            $row[] = $prd->email;
            $row[] = $expiry_date;
            $row[] = $passport_expiry;
            $row[] = $prd->warehouse;
            $row[] = $prd->reportingto;
            $row[] = $status;
            $row[] = $btn;
           
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->employeelist->count_all(),
            "recordsFiltered" => $this->employeelist->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}

