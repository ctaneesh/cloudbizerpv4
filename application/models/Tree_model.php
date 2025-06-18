<?php
class Tree_model extends CI_Model{
	private static $db;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
        public function get_all_product(){
                $this->db->select('pid,product_code,product_name,product_price,fproduct_price,quantity,alert,cberp_product_cat.title');
                $this->db->from('cberp_products');
                $this->db->join('cberp_product_to_category','cberp_product_to_category.product_id=cberp_products.pid');
                $this->db->join('cberp_product_cat','cberp_product_cat.id=cberp_product_to_category.category_id');

                // $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
                $this->db->where('cberp_products.status','Enable');
                $this->db->order_by('cberp_products.pid','desc');
                $this->db->group_by('cberp_products.pid');

                $result = $this->db->get();
                // echo $this->db->last_query();die();
                $show = $result->result_array();
                return $show;
        }
        public function get_low_quantity_product(){
                $this->db->select('pid, product_code, product_name, product_price, fproduct_price, quantity, alert, cberp_product_cat.title');
                $this->db->from('cberp_products');
                $this->db->join('cberp_product_to_category', 'cberp_product_to_category.product_id = cberp_products.pid');
                $this->db->join('cberp_product_cat', 'cberp_product_cat.id = cberp_product_to_category.category_id');
                // $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');            
                $this->db->where('cberp_products.status', 'Enable');
                $this->db->where('quantity <= alert'); 
            
                $this->db->group_by('cberp_products.pid');
                $this->db->order_by('cberp_products.pid', 'desc');
            
                $result = $this->db->get();
                // echo $this->db->last_query();die();
                return $result->result_array();
        }
        // public function get_product_lead_quote_details(){
        //         $this->db->select('enquiry_id,enquiry_number,date_received,name');
        //         $this->db->from('cberp_customer_leads');
        //         $this->db->join('cberp_customers', 'cberp_customer_leads.customer_id = cberp_customers.customer_id');
        //         $this->db->group_by('cberp_customer_leads.enquiry_id');
        //         $this->db->order_by('cberp_customer_leads.enquiry_id', 'desc');
        //         $result = $this->db->get();
        //         echo $this->db->last_query();die();
        //         return $result->result_array();
        // }
        // public function get_product_lead_quote_details() {
        //         $this->db->select("
        //             cberp_customer_leads.enquiry_id,
        //             cberp_customer_leads.enquiry_number,
        //             cberp_customer_leads.date_received,
        //             cberp_customers.name,
            
        //             (
        //                 SELECT ('tid,pid,product,quantity,price')
        //                 FROM customer_general_enquiry_items cei
        //                 WHERE cei.tid = cberp_customer_leads.enquiry_id
        //             ) AS lead_items,
            
        //             (
        //                 SELECT ('cq.tid,cq.lead_id,cq.quote_number,prepared_dt,pid,quantity,price')
        //                 FROM cberp_quotes cq
        //                 JOIN cberp_quotes_items cqi ON cqi.tid = cq.id
        //                 WHERE cq.lead_id = cberp_customer_leads.enquiry_id
        //             ) AS quote_items
        //         ");
            
        //         $this->db->from('cberp_customer_leads');
        //         $this->db->join('cberp_customers', 'cberp_customer_leads.customer_id = cberp_customers.customer_id');
        //         $this->db->group_by('cberp_customer_leads.enquiry_id');
        //         $this->db->order_by('cberp_customer_leads.enquiry_id', 'desc');
            
        //         $result = $this->db->get();
            
        //         echo $this->db->last_query(); die(); // Optional: view generated SQL
        //         return $result->result_array();
        //     }


        
        public function get_product_lead_quote_details() {
                $this->db->select("
                    cberp_customer_leads.lead_id,
                    cberp_customer_leads.lead_number,
                    cberp_customer_leads.date_received,
                    cberp_customers.name,
            
                    -- Lead items as a single aggregated string
                    (
                        SELECT GROUP_CONCAT(
                            CONCAT('PID: ', cei.pid, ', Product: ', cei.product, ', quantity: ', cei.quantity, ', Price: ', cei.price)
                            SEPARATOR ' | '
                        )
                        FROM cberp_customer_lead_items cei
                        WHERE cei.lead_id = cberp_customer_leads.lead_id
                    ) AS lead_items,
            
                    -- Quote items as a single aggregated string
                    (
                        SELECT GROUP_CONCAT(
                            CONCAT('Quote#: ', cq.quote_number, ', Product ID: ', cqi.pid, ', quantity: ', cqi.quantity, ', Price: ', cqi.price, ', Quote Date: ', cq.prepared_dt)
                            SEPARATOR ' | '
                        )
                        FROM cberp_quotes cq
                        JOIN cberp_quotes_items cqi ON cqi.quote_number  = cq.quote_number 
                        WHERE cq.lead_number	 = cberp_customer_leads.lead_id
                    ) AS quote_items
                ");
                $this->db->from('cberp_customer_leads');
                $this->db->join('cberp_customers', 'cberp_customer_leads.customer_id = cberp_customers.customer_id');
                $this->db->group_by('cberp_customer_leads.lead_id');
                $this->db->order_by('cberp_customer_leads.lead_id', 'asc');
                $result = $this->db->get();
                // echo $this->db->last_query(); die(); 
                return $result->result_array();
            }
        
    public function get_pos_with_items() {
        $this->db->select('po.id AS po_id, po.purchase_number AS po_name, COUNT(i.id) AS item_count');
        $this->db->from('cberp_purchase_orders po');
        $this->db->join('cberp_purchase_order_items i', 'i.tid = po.id', 'left');
        $this->db->group_by('po.id');
        $this->db->order_by('po.id', 'asc');
        $result = $this->db->get();
        return $result->result_array();
    }
    public function get_items_by_po($po_id) {
        $this->db->select('code as product_code,product as product_name, quantity, price as product_price,cberp_purchase_order_items.subtotal');
        $this->db->from('cberp_purchase_order_items'); 
        $this->db->join('cberp_purchase_orders', 'cberp_purchase_order_items.tid = cberp_purchase_orders.id', 'left');
        // $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_purchase_orders.purchase_number', $po_id);
        return $this->db->get()->result_array();
    }
    public function check_po_return($po_number) {
        $query = $this->db->get_where('purchase_returns', ['po_number' => $po_number]);
        return $query->num_rows() > 0;

        $this->db->select('po.id AS po_id, po.purchase_number AS po_name, COUNT(i.id) AS item_count');
        $this->db->from('cberp_purchase_orders po');
        $this->db->join('cberp_purchase_order_items i', 'i.tid = po.id', 'left');
        $this->db->group_by('po.id');
        $this->db->order_by('po.id', 'desc');
        $result = $this->db->get();
        return $result->result_array();
    }
    public function check_po_reciept($po_number){
        $this->db->select('pr.id AS pr_id, pr.transaction_number AS pr_name, COUNT(i.id) AS item_count,COUNT(e.id) AS expense_count');
        $this->db->from('cberp_purchase_receipts pr');
        $this->db->join('cberp_purchase_receipt_items i', 'i.stockreciptid = pr.id', 'left');
        $this->db->join('cberp_purchase_receipt_expenses e', 'e.stockreciptid =pr.id', 'left');
        $this->db->join('cberp_purchase_orders', 'pr.purchase_id = cberp_purchase_orders.id', 'left');

        $this->db->where('cberp_purchase_orders.purchase_number',$po_number);
        $this->db->group_by('pr.id');
        $this->db->order_by('pr.id', 'desc');
        $result = $this->db->get();
        return $result->row_array();
    }
    
    public function check_expense_for_receipt($reciept_id){
        $this->db->select('COUNT(e.id) AS expense_count');
        $this->db->from('cberp_purchase_receipt_expenses e');
        $this->db->where('e.stockreciptid',$reciept_id);
        // $this->db->group_by('pr.id');
        // $this->db->order_by('pr.id', 'desc');
        $result = $this->db->get();
        return $result->row_array();
    }
    public function get_reciept_by_po($purchase_reciept_id){
        $this->db->select('pri.created_date as date,pri.id AS id,stockreciptid as recipt_id, pri.product_name, pri.product_code,pri.product_quantity,price,amount');
        $this->db->from('cberp_purchase_receipt_items pri');
        $this->db->join('cberp_purchase_receipts pr', 'pr.id = pri.stockreciptid', 'left');
        $this->db->join('cberp_purchase_orders', 'pr.purchase_id = cberp_purchase_orders.id', 'left');
        // $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_purchase_orders.purchase_number',$purchase_reciept_id);
        $this->db->group_by('pri.id');
        $this->db->order_by('pri.id', 'desc');
        $result = $this->db->get();
        // echo $this->db->last_query(); die(); 
        return $result->result_array();
    }
    public function get_expenses_by_pr($purchase_reciept_id){
        $this->db->select('e.id,stockreciptid,expense_name,e.bill_number_cost as bill_no,e.bill_date_cost as date,e.costing_amount');
        $this->db->from('cberp_purchase_receipt_expenses e');
        $this->db->join('cberp_purchase_receipts pr', 'pr.id = e.stockreciptid', 'left');
        $this->db->join('cberp_purchase_orders', 'pr.purchase_id = cberp_purchase_orders.id', 'left');
        $this->db->where('cberp_purchase_orders.purchase_number',$purchase_reciept_id);
        $this->db->group_by('e.id');
        $this->db->order_by('e.id', 'desc');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function get_leads() {
        $this->db->select(' cberp_customer_leads.lead_id,cberp_customer_leads.lead_number, cberp_customer_leads.date_received,cberp_customers.name,COUNT(cberp_quotes.quote_number) AS quote_count');
        $this->db->from('cberp_customer_leads');
        $this->db->join('cberp_customers', 'cberp_customer_leads.customer_id = cberp_customers.customer_id','left');
        $this->db->join('cberp_quotes', 'cberp_quotes.lead_number = cberp_customer_leads.lead_number','left');
        $this->db->group_by('cberp_customer_leads.lead_number');
        $this->db->order_by('cberp_customer_leads.lead_number', 'asc');
        $result = $this->db->get();
            //   echo $this->db->last_query(); die(); 
        return $result->result_array();
    }
    public function get_lead_items($lead_id){
        $this->db->select('cberp_customers.name as customer_name,cberp_customer_leads.date_received,cberp_customer_lead_items.product_code,cberp_product_description.product_name, quantity, price as product_price,cberp_customer_lead_items.subtotal');
        $this->db->from('cberp_customer_lead_items'); 
        $this->db->join('cberp_customer_leads', 'cberp_customer_lead_items.lead_id = cberp_customer_leads.lead_id', 'left');
        $this->db->join('cberp_customers', 'cberp_customer_leads.customer_id = cberp_customers.customer_id');
        $this->db->join('cberp_products', 'cberp_customer_lead_items.product_code = cberp_products.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_customer_leads.lead_number', $lead_id);
        $result = $this->db->get();
        return $result->result_array();
    
    }
    public function get_quotes_by_lead($lead_number) {
        $this->db->select('quote_number');
        $this->db->from('cberp_quotes');
        $this->db->join('cberp_customer_leads', 'cberp_quotes.lead_number = cberp_customer_leads.lead_number', 'left');
        $this->db->join('cberp_customer_lead_items', 'cberp_customer_leads.lead_id = cberp_customer_lead_items.lead_id');
        $this->db->where('cberp_customer_leads.lead_number', $lead_number);
        $this->db->group_by('cberp_quotes.quote_number');
        return $this->db->get()->result_array();
    }
    public function get_quote_items($quote_number) {
        $this->db->select('cberp_quotes.quote_date,cberp_quotes.quote_number,cberp_quotes_items.product_code,cberp_product_description.product_name, cberp_quotes_items.quantity, cberp_quotes_items.price as product_price, cberp_quotes_items.total_amount');
        $this->db->from('cberp_quotes_items');
        $this->db->join('cberp_quotes', 'cberp_quotes_items.quote_number = cberp_quotes.quote_number','left');
        $this->db->join('cberp_products', 'cberp_quotes_items.product_code = cberp_products.product_code','left');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_quotes.quote_number', $quote_number);
        return $this->db->get()->result_array();
    }
    public function get_sales_orders_by_quote($quote_number) {
        $this->db->select('so.salesorder_number');
        $this->db->from('cberp_sales_orders so');
        $this->db->where('so.quote_number', $quote_number);
        $this->db->order_by('so.salesorder_date', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_sales_order_items($so_number) {
        $this->db->select('so.salesorder_number, so.salesorder_date,cberp_sales_orders_items.product_code,cberp_product_description.product_name, cberp_sales_orders_items.quantity, cberp_sales_orders_items.price as product_price, cberp_sales_orders_items.total_amount');
        $this->db->from('cberp_sales_orders so');
        $this->db->join('cberp_sales_orders_items', 'cberp_sales_orders_items.salesorder_number  = so.salesorder_number ', 'left');
        $this->db->join('cberp_products', 'cberp_sales_orders_items.product_code = cberp_products.product_code','left');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_sales_orders_items.salesorder_number', $so_number);
        $this->db->group_by('so.salesorder_number ');
        $this->db->order_by('so.salesorder_date	', 'ASC');
        $result = $this->db->get();
        // echo $this->db->last_query(); die(); 
        return $result->result_array();
    }
    public function get_delivery_notes_by_sales($so_number){
        $this->db->select('dn.delivery_note_number ');
        $this->db->from('cberp_delivery_notes dn');
        $this->db->where('dn.salesorder_number', $so_number);
        $this->db->order_by('dn.delivery_note_date', 'ASC');

        return $this->db->get()->result_array();
    }
    public function get_delivery_note_items($dn_number){
        $this->db->select('dn.delivery_note_number, dn.delivery_note_date, 
            dni.product_code, cberp_product_description.product_name, 
            dni.quantity, dni.product_price, dni.subtotal');
        $this->db->from('cberp_delivery_notes dn');
        $this->db->join('cberp_delivery_note_items dni', 'dni.delivery_note_number = dn.delivery_note_number', 'left');
        $this->db->join('cberp_products p', 'dni.product_code = p.product_code', 'left');        
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = p.product_code');
        $this->db->where('dni.delivery_note_number', $dn_number);
        $this->db->group_by('dn.delivery_note_number');
        $this->db->order_by('dn.delivery_note_date', 'ASC');
        
        $result = $this->db->get();
        return $result->result_array();

    }
    public function get_delivery_returns_by_notes($dn_number){
        $this->db->select('delivery_return_number ');
        $this->db->from('cberp_delivery_returns');
        $this->db->where('delivery_note_number', $dn_number);
        $this->db->order_by('created_date', 'ASC');
         $result = $this->db->get(); 
        return $result->result_array();
    }
    public function get_delivery_invoices_by_notes($dn_number){
        $this->db->select('in.invoice_number');
        $this->db->from('cberp_invoices in');
        $this->db->where('in.delevery_note_number', $dn_number);
        $this->db->order_by('in.invoice_date', 'ASC');

        return $this->db->get()->result_array();
    }
    public function get_delivery_return_items($return_number){
         $this->db->select('dr.delivery_return_number, dr.created_date, 
            dri.product_code, cberp_product_description.product_name, 
            dri.return_quantity,dri.delivered_quantity, dri.product_price, dri.subtotal');
        
        $this->db->from('cberp_delivery_returns dr');
        $this->db->join('cberp_delivery_return_items dri', 'dri.delivery_return_number = dr.delivery_return_number', 'left');
        $this->db->join('cberp_products p', 'dri.product_code = p.product_code', 'left');        
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('dri.delivery_return_number', $return_number);
        $this->db->group_by('dr.delivery_return_number');
        $this->db->order_by('dr.created_date', 'ASC');
        
        $result = $this->db->get();
        return $result->result_array();
    }
}

?>