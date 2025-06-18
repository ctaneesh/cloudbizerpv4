<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tree extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('parser', 'session'));
        $this->load->library('session');
        $this->load->helper('array');
        $this->load->model('Tree_model');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
      }
	public function index()
	{
        $head['title'] = "Lead Report";       
        $this->load->view('fixed/header', $head);        
		$this->load->view('lead_tree_view_report');        
        $this->load->view('fixed/footer');
	}
    // Get Leads
    public function get_leads() {
        $leads = $this->Tree_model->get_leads();
        $data = [];
        foreach ($leads as $lead) {
            if($lead['quote_count'] >0 ){
                $data[] = [
                    'id' => $lead['lead_number'],
                    'text' => $lead['lead_number'],
                    'children' => true,
                    'icon' => 'fa fa-folder-open text-warning' // blue lead icon
                ];
            }
            else{
                $data[] = [
                    'id' => $lead['lead_number'],
                    'text' => $lead['lead_number'],
                    'children' => false,
                    'icon' => 'fa fa-folder-open text-warning' // blue lead icon
                ];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    // Get Quotes
    public function get_quote() {
        $lead_number = $this->input->get('id');
        $quotes = $this->Tree_model->get_quotes_by_lead($lead_number);
        $data = [];
    
        foreach ($quotes as $quote) {
            $data[] = [
                'id' => 'quote_' . $quote['quote_number'],
                'text' => $quote['quote_number'],
                'children' => true,
                'icon' => 'fa fa-file-contract text-success' // green quote icon
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    public function get_sales_orders() {
        $quote_id = $this->input->get('id'); // will be like "quote_123"
        $quote_number = str_replace('quote_', '', $quote_id); // extract number

        $sales_orders = $this->Tree_model->get_sales_orders_by_quote($quote_number);
        $data = [];

        foreach ($sales_orders as $so) {
            $data[] = [
                'id' => 'so_' . $so['salesorder_number'],
                'text' => $so['salesorder_number'],
                'children' => true, // if more nested levels like delivery notes exist
                'icon' => 'fa fa-box text-primary' // blue box icon
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    public function get_delivery_notes() {
        $so_id = $this->input->get('id'); // will be like "quote_123"
        $so_number = str_replace('so_', '', $so_id); // extract number

        $delivery_notes = $this->Tree_model->get_delivery_notes_by_sales($so_number);
        $data = [];

        foreach ($delivery_notes as $dn) {
            $data[] = [
                'id' => 'delivery_' . $dn['delivery_note_number'],
                'text' => $dn['delivery_note_number'],
                'children' => true, // if more nested levels like delivery notes exist
                'icon' => 'fa fa-cube text-danger' // blue box icon
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    public function get_delivery_returns_invoice() {
        $parentId = $this->input->get('id'); // like 'delivery_123'
        $deliveryId = str_replace('delivery_', '', $parentId);

        // Example: Fetch returns
        $delivery_returns = $this->Tree_model->get_delivery_returns_by_notes($deliveryId);

        // Example: Fetch invoices
        $invoices = $this->Tree_model->get_delivery_invoices_by_notes($deliveryId);

        $data = [];

        foreach ($delivery_returns as $return) {
            $data[] = [
                'id' => 'return_' . $return['delivery_return_number'],
                'text' => $return['delivery_return_number'],
                'icon' => 'fa fa-undo text-danger',
                'children' => false
            ];
        }

        foreach ($invoices as $invoice) {
            $data[] = [
                'id' => 'invoice_' . $invoice['invoice_number'],
                'text' =>$invoice['invoice_number'],
                'icon' => 'fa fa-file-invoice-dollar text-primary',
                'children' => false
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function lead_details() {
        $lead_number = $this->input->get('lead_number');
        $items = $this->Tree_model->get_lead_items($lead_number);
          $html = '';
        // return view as HTML table
        if($items){
                $html = ' <div class="lead-block mb-4 p-3 border rounded">
                        <h4>
                            
                            <span>Customer Name :  '.$items[0]['customer_name'] .'</span> &nbsp;
                            
                        </h4></div>';
              $html .= '<table class="table table-bordered table-striped" id="items-table">';
              $html .= '<thead ><tr><th>Prduct Code</th><th>Product Name</th><th>Date</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>';
          
              foreach ($items as $item) {
                  $html .= '<tr>';
                  $html .= '<td>' . $item['product_code'] . '</td>';
                  $html .= '<td>' . $item['product_name'] . '</td>';
                  $html .= '<td>' . $item['date_received'] . '</td>';
                  $html .= '<td>' . $item['quantity'] . '</td>';
                  $html .= '<td>' . $item['product_price'] . '</td>';
                  $html .= '<td>' . $item['subtotal'] . '</td>';
                  $html .= '</tr>';
              }
          
              $html .= '</tbody></table>';
            }
              echo $html;
          
    }
    
    public function quote_details() {
        $quote_number = $this->input->get('quote_number');
        $items = $this->Tree_model->get_quote_items($quote_number); // You'll create this
         // return view as HTML table
       
         $html = '<table class="table table-bordered table-striped" id="items-table">';
         $html .= '<thead ><tr><th>Product Code</th><th>Product Name</th><th>Date</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>';
     
         foreach ($items as $item) {
             $html .= '<tr>';
             $html .= '<td>' . $item['product_code'] . '</td>';
             $html .= '<td>' . $item['product_name'] . '</td>';
             $html .= '<td>' . $item['quote_date'] . '</td>';
             $html .= '<td>' . $item['quantity'] . '</td>';
             $html .= '<td>' . $item['product_price'] . '</td>';
             $html .= '<td>' . $item['total_amount'] . '</td>';
             $html .= '</tr>';
         }
     
         $html .= '</tbody></table>';
     
         echo $html;
     
    }
    
        public function sales_order_details() {
        $sales_order_number = $this->input->get('sales_order_number');
        $so_number = str_replace('so_', '', $sales_order_number); // extract number

        $items = $this->Tree_model->get_sales_order_items($so_number); // You'll create this
         // return view as HTML table
       
         $html = '<table class="table table-bordered table-striped" id="items-table">';
         $html .= '<thead ><tr><th>Product Code</th><th>Product Name</th><th>Date</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>';
     
         foreach ($items as $item) {
             $html .= '<tr>';
             $html .= '<td>' . $item['product_code'] . '</td>';
             $html .= '<td>' . $item['product_name'] . '</td>';
             $html .= '<td>' . $item['salesorder_date'] . '</td>';
             $html .= '<td>' . $item['quantity'] . '</td>';
             $html .= '<td>' . $item['product_price'] . '</td>';
             $html .= '<td>' . $item['total_amount'] . '</td>';
             $html .= '</tr>';
         }
     
         $html .= '</tbody></table>';
     
         echo $html;
     
    }
    public function delivery_note_details() {
        $dn_number = $this->input->get('delivery_note_number');

        $items = $this->Tree_model->get_delivery_note_items($dn_number); // You'll create this
         // return view as HTML table
       
         $html = '<table class="table table-bordered table-striped" id="items-table">';
         $html .= '<thead ><tr><th>Product Code</th><th>Product Name</th><th>Date</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>';
     
         foreach ($items as $item) {
             $html .= '<tr>';
             $html .= '<td>' . $item['product_code'] . '</td>';
             $html .= '<td>' . $item['product_name'] . '</td>';
             $html .= '<td>' . $item['delivery_note_date'] . '</td>';
             $html .= '<td>' . $item['quantity'] . '</td>';
             $html .= '<td>' . $item['product_price'] . '</td>';
             $html .= '<td>' . $item['subtotal'] . '</td>';
             $html .= '</tr>';
         }
     
         $html .= '</tbody></table>';
     
         echo $html;
     
    }
    public function delivery_return_details(){
        $return_number = $this->input->get('delivery_return_number');
        $items = $this->Tree_model->get_delivery_return_items($return_number); // You'll create this
         // return view as HTML table
       
         $html = '<table class="table table-bordered table-striped" id="items-table">';
         $html .= '<thead ><tr><th>Product Code</th><th>Product Name</th><th>Date</th><th>Delivered Qty</th><th>Return Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>';
     
         foreach ($items as $item) {
             $html .= '<tr>';
             $html .= '<td>' . $item['product_code'] . '</td>';
             $html .= '<td>' . $item['product_name'] . '</td>';
             $html .= '<td>' . $item['created_date'] . '</td>';
             $html .= '<td>' . $item['delivered_quantity'] . '</td>';
             $html .= '<td>' . $item['return_quantity'] . '</td>';
             $html .= '<td>' . $item['product_price'] . '</td>';
             $html .= '<td>' . $item['subtotal'] . '</td>';
             $html .= '</tr>';
         }
     
         $html .= '</tbody></table>';
     
         echo $html;
    }
}