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

class Messages extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
            exit;
        }


        $this->load->model('dashboard_model');
        $this->load->model('tools_model');


    }


    public function index()
    {        
        $head['title'] = "Messages";
        $this->load->view('fixed/header',$head);
        $this->load->view('messages/index');
        $this->load->view('fixed/footer');
    }

    public function sendpm()
    {


        $subject = $this->input->post('subject', true);
        $message = $this->input->post('text', true);
        $receiver = $this->input->post('userid');

        if (strlen($subject) < 5 or $message == '') {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Invalid Message/Subject!"));
        } else {

            $this->aauth->send_pm($this->aauth->get_user()->id, $receiver, $subject, $message);

            echo json_encode(array('status' => 'Success', 'message' =>
                "Message Sent!!!!!", "reciever"=>$receiver));
        }


    }

    public function view()
    {

        $head['title'] = "Messages";
        $data['pmid'] = $this->input->get('id');
        $this->aauth->set_as_read_pm($data['pmid']);
        $this->load->model('message_model', 'message');
        $data['employee'] = $this->message->employee_details($data['pmid']);

        $this->load->view('fixed/header',$head);
        $this->load->view('messages/view', $data);
        $this->load->view('fixed/footer');


    }

    public function deletepm()
    {


        $pmid = $this->input->post('pmid');


        if ($this->aauth->delete_pm($pmid)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                "Message Deleted!"));
        } else {


            echo json_encode(array('status' => 'Error', 'message' =>
                "Error !"));
        }


    }
    public function unreadmessagecount()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);    
        $this->load->model('message_model', 'message');  
        $loginid =    $this->session->userdata('id');
        $result = $this->message->unread_message_count($loginid);    
        $list_pm = $this->message->unreadmsglist(6, 0, $loginid, false);
        $msglist ="";
        foreach ($list_pm as $row) {

            $msglist .= '<a href="' . base_url('messages/view?id=' . $row->msgid) . '">
            <div class="media">';
            // if($row->picture)
            // {
            //      $msglist .= '<div class="media-left"><span class="avatar avatar-sm  rounded-circle"><img src="' . base_url('userfiles/employee/' . $row->picture) . '" alt="avatar"><i></i></span></div>';
            // }
                
                 $msglist .= '<div class="media-body">
                <h6 class="media-heading">' . $row->name . '</h6>
                <p class="notification-text font-small-3 text-muted">' . $row->{'title'} . '</p><small>
                    <time class="media-meta text-muted" datetime="' . $row->{'date_sent'} . '">' . $row->{'date_sent'} . '</time></small>
                </div>
            </div></a>';
        } 
       
        echo json_encode(array('status' => 'Success', 'unreadmsgs' =>$result, "msglist"=>$msglist, "targetuser"=>$loginid));
    }


}
