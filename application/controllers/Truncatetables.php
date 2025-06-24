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

class Truncatetables extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");      
        $this->load->library('session');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(2)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $this->li_a = 'Truncate Tables';
       

    }

    public function index()
    {
        $head['title'] = "Truncate Tables";
        $tables = [
            'cberp_sales_orders',
            'cberp_sales_orders_items',
            'cberp_quote_ai',
            'cberp_quotes',
            'cberp_quotes_items',
            'cberp_customer_lead_attachments',
            'cberp_customer_lead_items',
            'cberp_customer_leads',
            'cberp_delivery_notes',
            'cberp_delivery_note_items',
            'cberp_invoice_items',
            'cberp_invoices',
            'cberp_purchase_orders',
            'cberp_purchase_order_items',
            'authorization_history',
            'cberp_transaction_tracking',
            'cberp_bank_transactions',
            'cberp_purchase_receipt_expenses',
            'cberp_purchase_receipt_items',
            'cberp_purchase_receipts',
            'cberp_customers_log',
            'cberp_employees_log',
            'cberp_purchase_orders',
            'cberp_purchase_order_items',
            'cberp_purchase_return_logs',
            'cberp_stock_returns',
            'cberp_stock_returns_items',
            'cberp_payments',
            'cberp_transactions',
            'cberp_delivery_returns',
            'cberp_delivery_return_items',
            'cberp_invoice_log',
            'cberp_invoice_payment_log',
            'cberp_invoice_payment_return_log',
            'cberp_invoice_return_log',
            'cberp_product_inventory_log',
            'cberp_products_log',
            'cberp_purchase_order_logs',
            'cberp_quotes_log',
            'cberp_supplier_log',
            'cberp_sales_orders_log',
            'customer_general_enquiry_log',
            'delivery_note_log',
            'supplier_stock_return_log',
            'purchase_receipt_log',
            'cberp_bank_transactions',
            'cberp_payment_transaction_link',
            'stock_transfer_wh_to_wh',
            'cberp_average_cost',
            'cberp_sent_received_files',
            'cberp_master_log',
            'cberp_purchase_reciept_returns',
            'cberp_purchase_reciept_returns_items',
            'cberp_approval',
            'cberp_invoice_payments',
            'cberp_invoice_payments_details',
        ];
        foreach ($tables as $table) {
            $this->db->truncate($table);
        }
        $this->db->update('cberp_accounts', ['lastbal' => '0.00']);
        echo 'Tables truncated successfully.';
        redirect(base_url('dashboard'));
    }

}