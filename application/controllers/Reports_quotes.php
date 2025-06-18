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

class Reports_quotes extends CI_Controller
{
    private $configurations;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Report_quote_model', 'quote');
        $this->load->model('quote_model', 'prdquote');
        $this->load->model('SalesOrder_model', 'salesorder');
        $this->load->model('authorizationapproval_model', 'authorization_approval');
        $this->load->library("Aauth");
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(1)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->li_a = 'sales';
        $this->session->unset_userdata('orderid');   
        $this->configurations = $this->session->userdata('configurations');
    }

   

    //invoices list
    public function index()
    {
        $head['title'] = "Quote Reports";
        $data['eid'] = intval($this->input->get('eid'));
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('invoices_model');       
        $condition = "";
        $data['counts'] = $this->invoices_model->get_dynamic_count('cberp_quotes','invoicedate','total',$condition);
        $data['employees']  = employee_list();
        $data['customers']  = customer_list();
        $data['permissions'] = load_permissions('Sales','Reports','Quote Report');
        $this->load->view('fixed/header', $head);
        $this->load->view('reportquotes/list', $data);
        $this->load->view('fixed/footer');
    }
  
    

    
   
    // erp2024 15-10-2024 quote direct send ends
    public function ajax_list()
    {
        $eid = 0;
        // if ($this->aauth->premission(9)) {
        //     $eid = $this->input->post('eid');
        // } 
        $list = $this->quote->get_datatables($eid);
     // print_r($list); die();
        $data = array();
        $currentTid = null;
        $no = $this->input->post('start');
        foreach ($list as $index => $invoices) {
            $salebtn = '';
           
            $couarr = $this->quote->quote_products($invoices->id);

           // echo count($couarr)."<br>";
            
           
            $targeturl = '<a href="' . base_url("quote/create?id=$invoices->id") . '">&nbsp; ' . $invoices->tid . '</a>';

           // echo $invoices->tid;
           $itemcode="";
           $qty="";
           $price="";
           $sub_tot=0;
           $subtotal=0;
           $grandtotal=0;
           $cost="";
           $itemdesc="";
           $cost_total=0;
           $profit=0;
           $subt=0;

           $totalprofit=0;
           $totalprofit_1=0;
            $no++;
            $row = array();
            $row[] = $no;
            $subtotal=$invoices->qty*$invoices->price;
           // $row[] = $targeturl;
           $row[] = (!empty($invoices->invoicedate)) ? dateformat($invoices->invoicedate) :"";
           $row[] = $invoices->tid;
            $approveddt = ($invoices->approved_dt)?(date('d-m-Y H:i:s',strtotime($invoices->approved_dt))):"";
            $row[] = $invoices->created_name;
            $row[] = $invoices->lead_id;
            $row[] = (!empty($invoices->created_date)) ? dateformat($invoices->created_date) :"";
            $row[] = $invoices->leadname;
            $row[] = $invoices->code;
            $row[] = $invoices->product_name;
            $row[] = $invoices->qty;
            $row[] = $invoices->price;                      
            $row[] = round($subtotal,3);  
            $row[] = $invoices->product_cost; 
            $row[] = round($invoices->product_cost*$invoices->qty,3);
            if(count($couarr)=="1")  {        
            $row[] = round($subtotal,3);
            $pr=round(round($subtotal,3)-round($invoices->product_cost*$invoices->qty,3),3);
            $row[] = $pr;
            }else{
                $row[]="";
                $row[] = "";
            }
            
                    
           $totalprofit=$totalprofit+$pr;
           // $row[] = $invoices->lead_id;
            // $row[] = '<a href="' . base_url("quote/view?id=$invoices->id") . '" class="btn btn-secondary btn-sm" target="_blank" title="View"><i class="fa fa-eye"></i></a> <a href="' . base_url("billing/printquote?id=$invoices->id") . '&token=1" class="btn btn-secondary btn-sm"  title="Print" target="_blank"><span class="fa fa-print"></span></a> <a href="#" data-object-id="' . $invoices->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
          //  $sub = array_fill(0, count($row), '');
           // $data[] = $sub;
          

           $couarr = $this->quote->quote_products($invoices->id);

          


           $ab=count($couarr);

           //echo  $invoices->tid.",";

           $subt=0;
           $totcost=0;

           if(count($couarr)>"1"){

            foreach ($couarr as $val){

                $subt=$subt+$val['subtotal'];

               $totcost=$totcost+$val['qty']*$val['product_cost'];
               
            }


          $subt=round($subt,3);

          

     if (($index + 1 == count($list)) || ($list[$index + 1]->tid !== $invoices->tid)) {

        $profit=round($subt-$totcost,3);

        $totalprofit_1=$profit+$totalprofit_1;
      
        $subtotalRow=array('','','','','','','','','','','','','',round($totcost,3),$subt,$profit);
       
        $data[] = $subtotalRow;

        }

        $subtotal = 0;
        $subt = 0;
        
       
    }else{
       
        $currentTid = $invoices->tid;


   
    }

    
    
    
          
          // $sub = array('','','','','','','','','','','','','','','','','');
            //$data[] = $sub;
        }

        // Check if any items were processed and add a grand total row

        $alltotal=$totalprofit+$totalprofit_1;

      //  echo $alltotal;


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->quote->count_all($eid),
            "recordsFiltered" => $this->quote->count_filtered($eid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

   
    
   



   
    
  


}
