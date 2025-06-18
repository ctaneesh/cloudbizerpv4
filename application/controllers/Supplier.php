<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplier_model', 'supplier');     
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');        
        $this->load->model('country_model');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(2)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $this->li_a = 'stock';
    }

    public function index()
    {
        $data['permissions'] = load_permissions('CRM','Suppliers','Manage Suppliers','List');
        // print_r($data['permissions']); die();
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Supplier';
        $this->load->view('fixed/header', $head);
        $this->load->view('supplier/clist',$data);
        $this->load->view('fixed/footer');
    }

    public function create()
    {
        $supplier_id = $this->input->get('id');
        $data['permissions'] = load_permissions('CRM','Suppliers','New Supplier');
        $data['supplierid']="";
        if($supplier_id)
        {
            $data['permissions'] = load_permissions('CRM','Suppliers','Manage Suppliers','List');
            $data['supplier'] = $this->supplier->details($supplier_id);
            $data['supplierid']=$supplier_id;
            $page = "supplier";
            //erp2024 06-01-2025 detailed history log starts
            $data['detailed_log']= $this->supplier->get_detailed_log($supplier_id,$page);
            $products = $data['detailed_log'];
            $groupedBySequence = []; 
            foreach ($products as $product) {
            $sequence = $product['seqence_number'];
            $groupedBySequence[$sequence][] = $product; 
            }
            $data['groupedDatas'] = $groupedBySequence;
        }
        // echo "<pre>"; print_r($data['supplier']); die();
        $this->load->library("Common");
        $data['langs'] = $this->common->languages();
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Create Supplier';        
        // erp2024 new function
        $data['countries'] = $this->country_model->country_list();
        // erp2024 new function ends
        $this->load->view('fixed/header', $head);
        // erp2024 removed view 10-06-2024
        // $this->load->view('supplier/create', $data);
        // erp2024 removed view 10-06-2024
        // erp2024 newly added  view 10-06-2024    
        $data['countries'] = $this->country_model->country_list();
        $this->load->view('supplier/create-supplier', $data);
        // erp2024 newly added  view 10-06-2024
        $this->load->view('fixed/footer');
    }

    public function view()
    {
        $custid = $this->input->get('id');
        $data['details'] = $this->supplier->details($custid);
        // $data['customergroup'] = $this->supplier->group_info($data['details']['gid']);
        $data['money'] = $this->supplier->money_details($custid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'View Supplier';
        $data['log'] = $this->supplier->gethistory($custid);
        $page = "supplier";
        //erp2024 06-01-2025 detailed history log starts
        $data['detailed_log']= $this->supplier->get_detailed_log($custid,$page);
        $products = $data['detailed_log'];
        $groupedBySequence = []; 
        foreach ($products as $product) {
           $sequence = $product['seqence_number'];
           $groupedBySequence[$sequence][] = $product; 
        }
        $data['groupedSupplier'] = $groupedBySequence;
        //erp2024 06-01-2025 detailed history log ends
        $data['permissions'] = load_permissions('CRM','Suppliers','Manage Suppliers','View Page');
        $this->load->view('fixed/header', $head);
        $this->load->view('supplier/view', $data);
        $this->load->view('fixed/footer');
    }

    public function load_list()
    {
        $list = $this->supplier->get_datatables();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $suppliers) {
            $no++;

            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url("supplier/view?id=$suppliers->supplier_id") .'">' . $suppliers->name . '</a>';
            $row[] = $suppliers->address . ',' . $suppliers->city . ',' . $suppliers->country;
            $row[] = $suppliers->email;
            $row[] = $suppliers->phone;
            $row[] = ' <a href="' . base_url("supplier/create?id=$suppliers->supplier_id") .'" class="btn btn-secondary btn-sm" title=""Edit><span class="fa fa-pencil"></span></a> <a href="#" data-object-id="' . $suppliers->supplier_id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            // $row[] = '<a href="supplier/view?id=' . $suppliers->id . '" class="btn btn-secondary btn-sm" title="View"><span class="fa fa-eye"></span></a> <a href="supplier/edit?id=' . $suppliers->id . '" class="btn btn-secondary btn-sm" title=""Edit><span class="fa fa-pencil"></span></a> <a href="#" data-object-id="' . $suppliers->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->supplier->count_all(),
            "recordsFiltered" => $this->supplier->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    //edit section
    public function edit()
    {
        $supplier_id = $this->input->get('id');
        $this->load->library("Common");
        $data['customer'] = $this->supplier->details($supplier_id);
        // $data['customergroup'] = $this->supplier->group_info($supplier_id);
        // $data['customergrouplist'] = $this->supplier->group_list();
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['countries'] = $this->country_model->country_list();        
        $data['langs'] = $this->common->languages();
        $head['title'] = 'Edit Supplier';
        $this->load->view('fixed/header', $head);  
        //erp2024 removed view section 10-06-2024
        // $this->load->view('supplier/edit', $data);
        //erp2024 removed view section 10-06-2024
        //erp2024 new view section 10-06-2024
        $this->load->view('supplier/edit-supplier', $data);
        $this->load->view('fixed/footer');

    }

    public function addsupplier()
    {
        
        $name = $this->input->post('name', true);
        $company = $this->input->post('company', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);
        $address = $this->input->post('address', true);
        $city = $this->input->post('city', true);
        $region = $this->input->post('region', true);
        $country = $this->input->post('country', true);
        $postbox = $this->input->post('postbox', true);
        $tax_id = $this->input->post('tax_id', true);
        //emp2024 new fileds 03-06-2024 ends      
        $contact_person = $this->input->post('contact_person', true);
        $land_line = $this->input->post('land_line', true);
        $contact_phone1 = $this->input->post('contact_phone1', true);
        $contact_phone2 = $this->input->post('contact_phone2', true);
        $contact_email1 = $this->input->post('contact_email1', true);
        $contact_email2 = $this->input->post('contact_email2', true);
        $contact_designation = $this->input->post('contact_designation', true);
        $website_url = $this->input->post('website_url', true);
        $account_number = $this->input->post('account_number', true);
        $account_holder = $this->input->post('account_holder', true);
        $bank_country = $this->input->post('bank_country', true);
        $bank_location = $this->input->post('bank_location', true);

        
       $this->supplier->add($name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $tax_id, $contact_person, $land_line, $contact_phone1, $contact_phone2, $contact_email1, $contact_email2, $contact_designation, $website_url, $account_number, $account_holder, $bank_country, $bank_location);

        

        //emp2024 new fileds 03-06-2024 ends 

    }

    public function editsupplier()
    {
        $id = $this->input->post('id', true);
        $name = $this->input->post('name', true);
        $company = $this->input->post('company', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);
        $address = $this->input->post('address', true);
        $city = $this->input->post('city', true);
        $region = $this->input->post('region', true);
        $country = $this->input->post('country', true);
        $postbox = $this->input->post('postbox', true);
        $tax_id = $this->input->post('tax_id', true);
        //emp2024 new fileds 03-06-2024 ends      
        $contact_person = $this->input->post('contact_person', true);
        $land_line = $this->input->post('land_line', true);
        $contact_phone1 = $this->input->post('contact_phone1', true);
        $contact_phone2 = $this->input->post('contact_phone2', true);
        $contact_email1 = $this->input->post('contact_email1', true);
        $contact_email2 = $this->input->post('contact_email2', true);
        $contact_designation = $this->input->post('contact_designation', true);
        $website_url = $this->input->post('website_url', true);
        $account_number = $this->input->post('account_number', true);
        $account_holder = $this->input->post('account_holder', true);
        $bank_country = $this->input->post('bank_country', true);
        $bank_location = $this->input->post('bank_location', true);
        //emp2024 new fileds 03-06-2024 ends

        if ($id) {
            $this->supplier->edit($id, $name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $tax_id, $contact_person, $land_line, $contact_phone1, $contact_phone2, $contact_email1, $contact_email2, $contact_designation, $website_url, $account_number, $account_holder, $bank_country, $bank_location);
        }
    }


    public function delete_i()
    {
        $id = $this->input->post('deleteid');

        if ($this->supplier->delete($id)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    public function displaypic()
    {
        $id = $this->input->get('id');
        $this->load->library("uploadhandler", array(
            'accept_file_types' => '/\.(gif|jpe?g|png)$/i', 'upload_dir' => FCPATH . 'userfiles/customers/'
        ));
        $img = (string)$this->uploadhandler->filenaam();
        if ($img != '') {
            $this->supplier->editpicture($id, $img);
        }


    }


    public function translist()
    {
        $cid = $this->input->post('cid');
        $list = $this->supplier->trans_table($cid);
        $data = array();
        // $no = $_POST['start'];
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
            $row[] = $this->lang->line($prd->method);

            $row[] = '<a href="#" data-object-id="' . $pid . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->supplier->trans_count_all($cid),
            "recordsFiltered" => $this->supplier->trans_count_filtered($cid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function inv_list()
    {
        $cid = $this->input->post('cid');
        $list = $this->supplier->inv_datatables($cid);
        $data = array();

        $no = $this->input->post('start');
        
        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;
            // $row[] = $invoices->tid;
            $row[] = '<a href="' . base_url("purchase/view?id=$invoices->id") . '" title="View" >'.$invoices->purchase_number.'</a>';
            $row[] = $invoices->invoicedate;
            $row[] = amountExchange($invoices->total, 0, $this->aauth->get_user()->loc);
            $row[] = '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            $row[] = '<a href="' . base_url("invoices/printinvoice?id=$invoices->id") . '&d=1" class="btn btn-secondary btn-sm"  title="Download"><span class="fa fa-download"></span></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            // $row[] = '<a  target="_blank" href="' . base_url("purchase/view?id=$invoices->id") . '" class="btn btn-secondary btn-sm" title="View"><i class="fa fa-eye"></i></a> <a href="' . base_url("invoices/printinvoice?id=$invoices->id") . '&d=1" class="btn btn-secondary btn-sm"  title="Download"><span class="fa fa-download"></span></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->supplier->inv_count_all($cid),
            "recordsFiltered" => $this->supplier->inv_count_filtered($cid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }


    public function transactions()
    {
        $custid = $this->input->get('id');
        $data['details'] = $this->supplier->details($custid);
        $data['money'] = $this->supplier->money_details($custid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'View Supplier';
        $this->load->view('fixed/header', $head);
        $this->load->view('supplier/transactions', $data);
        $this->load->view('fixed/footer');
    }

    public function invoices()
    {
        $custid = $this->input->get('id');
        $data['details'] = $this->supplier->details($custid);

        $data['money'] = $this->supplier->money_details($custid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'View Supplier Invoices';
        $this->load->view('fixed/header', $head);
        $this->load->view('supplier/invoices', $data);
        $this->load->view('fixed/footer');
    }

    public function bulkpayment()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $data['id'] = $this->input->get('id');
        $data['details'] = $this->supplier->details($data['id']);
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $this->session->set_userdata("cid", $data['id']);
        $head['title'] = 'Bulk Payment Invoices';
        $this->load->view('fixed/header', $head);
        $this->load->view('supplier/bulkpayment', $data);
        $this->load->view('fixed/footer');
    }

    public function bulk_post()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $csd = $this->input->post('customer', true);
        $sdate = datefordatabase($this->input->post('sdate'));
        $edate = datefordatabase($this->input->post('edate'));
        $trans_type = $this->input->post('trans_type', true);
        $data['details'] = $this->supplier->sales_due($sdate, $edate, $csd, $trans_type);

        $due = $data['details']['total'] - $data['details']['pamnt'];
        echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Calculated') . ' ' . amountExchange($due), 'due' => amountExchange_s($due)));
    }

    public function bulk_post_payment()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $csd = $this->input->post('customer', true);
        $account = $this->input->post('account', true);
        $pay_method = $this->input->post('pmethod', true);
        $amount = numberClean($this->input->post('amount', true));
        $sdate = datefordatabase($this->input->post('sdate_2'));
        $edate = datefordatabase($this->input->post('edate_2'));

        $trans_type = $this->input->post('trans_type_2', true);
        $note = $this->input->post('note', true);
        $data['details'] = $this->supplier->sales_due($sdate, $edate, $csd, $trans_type, false, $amount, $account, $pay_method, $note);

        $due = 0;
        echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Paid') . ' ' . amountExchange($amount), 'due' => amountExchange_s($due)));
    }

    //erp2024 new Supplier add section 10-06-2024
    public function add_new_supplier()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        // echo '<pre>';
        // print_r($_POST);
        // print_r($_FILES);
        // echo '</pre>';
        // echo "here";
        // erp2024 modified 03-06-2024
        // $this->form_validation->set_rules('name', 'name', 'required');
        // $this->form_validation->set_rules('phone', 'phone', 'required');
        // $this->form_validation->set_rules('email', 'email', 'required|valid_email');

        // Run validation
        // if ($this->form_validation->run() == FALSE) {            
        //     echo json_encode(array('status' => 'Error', 'message' => 'Enter Required Fields'));
        //     die();
        // }
        $config['upload_path'] = FCPATH . 'userfiles/customers/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 2048; 
        $this->load->library('upload', $config);
        $files = ['computer_card_image', 'sponser_image', 'picture'];
        $file_data = [];   
        foreach ($files as $file) {
            if (!$this->upload->do_upload($file)) {
                continue;
            } else {
                $file_data[$file] = $this->upload->data();
            }
        }
        $computer_card_image = isset($file_data['computer_card_image']) ? $file_data['computer_card_image']['file_name'] : '';
        $sponser_image = isset($file_data['sponser_image']) ? $file_data['sponser_image']['file_name'] : '';
        $profile_pic = isset($file_data['picture']) ? $file_data['picture']['file_name'] : 'example.png';
        // erp2024 modified 03-06-2024

        $name = $this->input->post('name', true);
        $company = $this->input->post('company', true);
        $phone = trim($this->input->post('phone', true));
        $email = $this->input->post('email', true);
        $address = $this->input->post('address', true);
        $city = $this->input->post('city', true);
        $region = $this->input->post('region', true);
        $country = $this->input->post('country', true);
        $postbox = $this->input->post('postbox', true);
        $tax_id = $this->input->post('tax_id', true);
        $customergroup = $this->input->post('customergroup');
        $language = $this->input->post('language', true);
        $create_login = $this->input->post('c_login', true);
        $password = $this->input->post('password_c', true);
        $document_id = $this->input->post('document_id', true);
        $custom = $this->input->post('c_field', true);
        $discount = $this->input->post('discount', true);

        // erp2024 new fields supplier table
        $registration_number = $this->input->post('registration_number', true);
        $expiry_date = $this->input->post('expiry_date', true);
        $computer_card_number = $this->input->post('computer_card_number', true);
        $sponser_id = $this->input->post('sponser_id', true);
        $credit_limit = $this->input->post('credit_limit', true);
        $credit_period = $this->input->post('credit_period', true);        
        $contact_person = $this->input->post('contact_person', true);
        $land_line = $this->input->post('land_line', true);
        $contact_phone1 = $this->input->post('contact_phone1', true);
        $contact_phone2 = $this->input->post('contact_phone2', true);
        $contact_email1 = $this->input->post('contact_email1', true);
        $contact_email2 = $this->input->post('contact_email2', true);
        $contact_designation = $this->input->post('contact_designation', true);
        $account_number = $this->input->post('account_number', true);
        $account_holder = $this->input->post('account_holder', true);
        $bank_country = $this->input->post('bank_country', true);
        $bank_location = $this->input->post('bank_location', true);
        $bank_name = $this->input->post('bank_name', true);

        $supplierid = $this->input->post('supplierid', true);

        if($supplierid && empty($file_data['computer_card_image']['file_name']))
        {
            $computer_card_image = isset($file_data['computer_card_image']) ? $file_data['computer_card_image']['file_name'] : $this->input->post('computer_card_image_text', true);
        }
        if($supplierid && empty($file_data['sponser_image']['file_name']))
        {
            $sponser_image_text = $this->input->post('sponser_image_text', true);
            $sponser_image = isset($file_data['sponser_image']) ? $file_data['sponser_image']['file_name'] : $this->input->post('sponser_image_text', true);
        }
        if($supplierid && empty($file_data['picture']['file_name']))
        {
            $profile_pic = isset($file_data['picture']) ? $file_data['picture']['file_name'] : $this->input->post('picture_text', true);
        }

        $supplier_id = $this->supplier->add_new($name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $language, $create_login, $password, $document_id, $custom, $discount, $registration_number, $expiry_date, $computer_card_number, $sponser_id,  $credit_limit, $credit_period, $computer_card_image, $sponser_image, $profile_pic, $contact_person, $land_line, $contact_phone1, $contact_phone2, $contact_email1, $contact_email2, $contact_designation, $account_number, $account_holder, $bank_country, $bank_location, $bank_name, $supplierid);        
        //erp2024 new fields supplier table

        if($supplier_id)
        {
            $billing_data = [
                'supplier_id' => $supplier_id,
                'billing_name' => $this->input->post('billing_name', true),
                'billing_contact_person' => $this->input->post('billing_contact_person', true),
                'billing_phone' => $this->input->post('billing_phone', true),
                'billing_email' => $this->input->post('billing_email', true),
                'billing_address_1' => $this->input->post('billing_address_1', true),
                'billing_address_2' => $this->input->post('billing_address_2', true),
                'billing_city' => $this->input->post('billing_city', true),
                'billing_region' => $this->input->post('billing_region', true),
                'billing_country' => $this->input->post('billing_country', true),
                'billing_postal_code' => $this->input->post('billing_postal_code', true),
            ];
            $shipping_data = [ 
                'supplier_id' => $supplier_id,
                'shipping_name' => $this->input->post('shipping_name', true),
                'shipping_contact_person' => $this->input->post('shipping_contact_person', true),
                'shipping_phone' => $this->input->post('shipping_phone', true),
                'shipping_email' => $this->input->post('shipping_email', true),
                'shipping_address_1' => $this->input->post('shipping_address_1', true),
                'shipping_address_2' => $this->input->post('shipping_address_2', true),
                'shipping_city' => $this->input->post('shipping_city', true),
                'shipping_region' => $this->input->post('shipping_region', true),
                'shipping_country' => $this->input->post('shipping_country', true),
                'shipping_postal_code' => $this->input->post('shipping_postal_code', true),
            ];
            if($supplierid)
            {
                $this->db->update('cberp_supplier_billing', $billing_data,['supplier_id'=>$supplierid]);
                $this->db->update('cberp_supplier_shipping', $shipping_data,['supplier_id'=>$supplierid]);
                detailed_log_history('supplier',$supplierid,'Updated', $_POST['changedFields']);
            }
            else{
                $this->db->insert('cberp_supplier_billing', $billing_data);
                $this->db->insert('cberp_supplier_shipping', $shipping_data);
            }
           
        }
         echo json_encode(array('status' => 'Success', 'data' => 'Added Successfully'));

    }
    public function edit_new_supplier()
    {
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // echo '<pre>';
        // print_r($_POST);
        // print_r($_FILES);
        // echo '</pre>';
        // echo "here";
        // erp2024 modified 03-06-2024
        $config['upload_path'] = FCPATH . 'userfiles/customers/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 2048; 
        $this->load->library('upload', $config);
        $files = ['computer_card_image', 'sponser_image'];
        $file_data = [];   
        foreach ($files as $file) {
            if (!$this->upload->do_upload($file)) {
                continue;
            } else {
                $file_data[$file] = $this->upload->data();
            }
        }
        $computer_card_image = isset($file_data['computer_card_image']) ? $file_data['computer_card_image']['file_name'] : '';
        $sponser_image = isset($file_data['sponser_image']) ? $file_data['sponser_image']['file_name'] : '';
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $id = $this->input->post('id');
       
        $name = $this->input->post('name', true);
        $company = $this->input->post('company', true);
        $phone = trim($this->input->post('phone', true));
        $email = $this->input->post('email', true);
        $address = $this->input->post('address', true);
        $city = $this->input->post('city', true);
        $region = $this->input->post('region', true);
        $country = $this->input->post('country', true);
        $postbox = $this->input->post('postbox', true);
        $customergroup = $this->input->post('customergroup', true);
        $tax_id = $this->input->post('tax_id', true);
        $shipping_name = $this->input->post('shipping_name', true);
        $shipping_phone = trim($this->input->post('shipping_phone', true));
        $shipping_email = $this->input->post('shipping_email', true);
        $shipping_address_1 = $this->input->post('shipping_address_1', true);
        $shipping_city = $this->input->post('shipping_city', true);
        $shipping_region = $this->input->post('shipping_region', true);
        $shipping_country = $this->input->post('shipping_country', true);
        $shipping_postbox = $this->input->post('shipping_postbox', true);
        $document_id = $this->input->post('document_id', true);
        $custom = $this->input->post('c_field', true);
        $language = $this->input->post('language', true);
        $discount = $this->input->post('discount', true);

        // erp2024 new fields customer table 03-06-2024
        $registration_number = $this->input->post('registration_number', true);
        $expiry_date = $this->input->post('expiry_date', true);
        $computer_card_number = $this->input->post('computer_card_number', true);
        $sponser_id = $this->input->post('sponser_id', true);
        $credit_limit = $this->input->post('credit_limit', true);
        $credit_period = $this->input->post('credit_period', true);        
        $contact_person = $this->input->post('contact_person', true);
        $land_line = $this->input->post('land_line', true);
        $contact_phone1 = $this->input->post('contact_phone1', true);
        $contact_phone2 = $this->input->post('contact_phone2', true);
        $contact_email1 = $this->input->post('contact_email1', true);
        $contact_email2 = $this->input->post('contact_email2', true); 
        $contact_designation = $this->input->post('contact_designation', true); 
        $account_number = $this->input->post('account_number', true);
        $account_holder = $this->input->post('account_holder', true);
        $bank_country = $this->input->post('bank_country', true);
        $bank_location = $this->input->post('bank_location', true);
        $bank_name = $this->input->post('bank_name', true);
        //erp2024 new fields customer table  03-06-2024     

        //erp2024 new parameters 03-06-2024         
        //$response = json_encode(array('status' => 'Error', 'message' => 'Somthing went wrong'));  
        if ($id) {
            // log_table_data('cberp_suppliers','cberp_supplier_log', 'id' ,'supplier_id','Update',$id);

            //erp2024 07-01-2025 detailed history log starts
          // print_r($_POST['changedFields']);
            detailed_log_history('supplier',$id,'Updated', $_POST['changedFields']);
             //erp2024 07-01-2025 detailed history log ends 
            //********* */

            $response = $this->supplier->edit_supplier($id, $name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox, $document_id, $custom, $language, $discount, $registration_number, $expiry_date, $computer_card_number, $sponser_id,  $credit_limit, $credit_period, $computer_card_image, $sponser_image, $contact_person, $land_line, $contact_phone1, $contact_phone2, $contact_email1, $contact_email2, $contact_designation, $account_number, $account_holder, $bank_country, $bank_location, $bank_name);
            echo $response;
        }
       
    }

    //erp2024 new Supplier add section 23-07-2024 
    public function supplier_details_byid(){
        $details = $this->supplier->supplier_details_byid($this->input->post('cid', true));
        echo json_encode(array('status' => 'Success', 'data' => $details['address']));

    }

    public function delete_file(){
        $fieldname = $this->input->post('fieldname');
        $id = $this->input->post('id');
        $fieldval= $this->input->post('fieldval');
        $this->db->update('cberp_suppliers',[$fieldname => NULL],['supplier_id'=>$id]);
        unlink(FCPATH . 'userfiles/customers/' . $fieldval);
        echo json_encode(array('status' => '1', 'message' =>"Success"));            
    }
 

}
