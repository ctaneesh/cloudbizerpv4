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

class Tools extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }

        $this->load->model('tools_model', 'tools');
        $this->li_a = 'misc';

    }

    //todo section

    public function todo()
    {
        $this->li_a = 'project';
        // if (!$this->aauth->premission(4)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $data['permissions'] = load_permissions('Project','To Do List','Manage To Do List');
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'ToDo List';
        $data['totalt'] = $this->tools->task_count_all();

        $this->load->view('fixed/header', $head);
        $this->load->view('todo/index', $data);
        $this->load->view('fixed/footer');

    }

    public function addtask()
    {
        $this->li_a = 'project';
        // if (!$this->aauth->premission(4)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $data['permissions'] = load_permissions('Project','To Do List','New To Do List');
        $this->load->model('employee_model', 'employee');
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['emp'] = $this->employee->list_employee();
        $head['title'] = 'Add Task';

        $this->load->view('fixed/header', $head);
        $this->load->view('todo/addtask', $data);
        $this->load->view('fixed/footer');

    }

    public function edittask()
    {
        $this->li_a = 'project';
        // if (!$this->aauth->premission(4)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $data['permissions'] = load_permissions('Project','To Do List','Manage To Do List');
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $name = $this->input->post('name', true);
            $status = $this->input->post('status');
            $priority = $this->input->post('priority');
            $stdate = $this->input->post('staskdate', true);
            $tdate = $this->input->post('taskdate', true);
            $employee = $this->input->post('employee');
            $content = $this->input->post('content');
            $stdate = ($stdate);
            $tdate = ($tdate)." ".date("H:i:s");

            if ($this->tools->edittask($id, $name, $status, $priority, $stdate, $tdate, $employee, $content)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('UPDATED') . "  <a href='todo' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {

            $this->load->model('employee_model', 'employee');

            $head['usernm'] = $this->aauth->get_user()->username;
            $data['emp'] = $this->employee->list_employee();
            $head['title'] = 'Edit Task';

            $id = $this->input->get('id');
            $data['task'] = $this->tools->viewtask($id);
            // erp2025 12-03-2025 start
            $page = "Tasks";
            $data['detailed_log']= get_detailed_logs($id,$page);
            $detailed_logs = $data['detailed_log'];
            $groupedBySequence = []; 
            foreach ($detailed_logs as $detailed_log) {
                $sequence = $detailed_log['seqence_number'];
                $groupedBySequence[$sequence][] = $detailed_log; 
            }
            $data['groupedOrder'] = $groupedBySequence;
            // echo "<pre>"; print_r($data['groupedOrder']); die();
            // erp2025 12-03-2025 end
            $this->load->view('fixed/header', $head);
            $this->load->view('todo/edittask', $data);
            $this->load->view('fixed/footer');
        }

    }

    public function save_addtask()
    {
        // if (!$this->aauth->premission(4)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }

        $name = $this->input->post('name', true);
        $status = $this->input->post('status');
        $priority = $this->input->post('priority');
        $stdate = $this->input->post('staskdate');
        $tdate = $this->input->post('taskdate');
        $employee = $this->input->post('employee');
        $content = $this->input->post('content');
        $assign = $this->aauth->get_user()->id;
        $stdate = ($stdate);
        $tdate = ($tdate)." ".date("H:i:s");
        $created_date_time = date("Y-m-d H:i:s");
       
        if ($this->tools->addtask($name, $status, $priority, $stdate, $tdate, $employee, $assign, $content, $created_date_time)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('New Task Added') . "  <a href='addtask' class='btn btn-secondary btn-sm btn-crud breaklink'><span class='fa fa-plus' aria-hidden='true'></span>  </a>   <a href='todo' class='btn btn-secondary btn-sm btn-crud breaklink'><span class='fa fa-eye' aria-hidden='true'></span>  </a>"));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }

    }

    public function set_task()
    {
        // if (!$this->aauth->premission(4)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $id = $this->input->post('tid');
        $stat = $this->input->post('stat');
        $this->tools->settask($id, $stat);
        echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('UPDATED'), 'pstatus' => 'Success'));


    }

    public function view_task()
    {
        // if (!$this->aauth->premission(4)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $id = $this->input->post('tid');

        $task = $this->tools->viewtask($id);

        echo json_encode(array('name' => $task['name'], 'description' => $task['description'], 'employee' => $task['emp'], 'assign' => $task['assign'], 'priority' => $task['priority'], 'target_url' => $task['target_url']));
    }

    public function task_stats()
    {
        // if (!$this->aauth->premission(4)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $this->tools->task_stats();


    }

    public function delete_i()
    {
        // if (!$this->aauth->premission(4)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $id = $this->input->post('deleteid');

        if ($this->tools->deletetask($id)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }


    public function todo_load_list()
    {
        // if (!$this->aauth->premission(4)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        // class="open-task" data-task-id="123"
        $cday = $this->input->get('cday');
        $list = $this->tools->task_datatables($cday);
        $data = array();
        $no = $this->input->post('start');

        foreach ($list as $task) {
            $no++;
            $name = '<a class="check text-default" data-id="' . $task->id . '" data-stat="Due"> <i class="fa fa-check"></i> </a><a href="#" data-id="' . $task->id . '" class="view_task">' . $task->name . '</a>';
            if ($task->status == 'Done') {
                $name = '<a class="check text-success" data-id="' . $task->id . '" data-stat="Done"> <i class="fa fa-check"></i> </a><a href="#" data-id="' . $task->id . '" class="view_task">' . $task->name . '</a>';
            }
            $target_url = $task->description;
            if($task->target_url)
            {
                $target_url = '<a href="'.$task->target_url.'" class="open-task" data-task-id="' . $task->id . '"> '.$task->description.' </a>';
                // $target_url = '<a href="'.$task->target_url.'" class="open-task" data-task-id="' . ($task->id) . '"> '.$task->description.' </a>';
            }
            $row = array();
            $row[] = $no;
            $row[] = dateformat_time($task->created_date_time);
            $row[] = '<a href="#" class="btn btn-secondary btn-sm rounded set-task breaklink" data-id="' . $task->id . '" data-stat="0" data-status="'.$task->status.'"> SET </a>' . $name;
            $row[] = $target_url;
            $row[] = dateformat_time($task->duedate);
            $row[] = dateformat($task->start);
            $row[] = $task->assign;
            $row[] = $task->emp;
            $row[] = '<span class="task_' . $task->status . '">' . $this->lang->line($task->status) . '</span>';

            $row[] = '<a class="btn btn-secondary btn-sm" href="edittask?id=' . $task->id . '" data-object-id="' . $task->id . '"> <i class="fa fa-pencil"></i> </a>&nbsp;<a class="btn btn-secondary btn-sm delete-object" href="#" data-object-id="' . $task->id . '"> <i class="fa fa-trash"></i> </a>';


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->tools->task_count_all(),
            "recordsFiltered" => $this->tools->task_count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }


    //set goals

    public function setgoals()
    {
        if ($this->aauth->get_user()->roleid < 5) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }
        $this->li_a = 'company';
        if ($this->input->post('income')) {

            $income = (float)$this->input->post('income');
            $expense = (float)$this->input->post('expense');
            $sales = (float)$this->input->post('sales');
            $netincome = (float)$this->input->post('netincome');

            
            if ($this->tools->setgoals($income, $expense, $sales, $netincome)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('UPDATED')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Set Goals';
            $data['goals'] = $this->tools->goals(1);

            $this->load->view('fixed/header', $head);
            $this->load->view('goals/index', $data);
            $this->load->view('fixed/footer');
        }

    }

    //notes

    public function notes()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Notes';
        $this->load->view('fixed/header', $head);
        $this->load->view('notes/index');
        $this->load->view('fixed/footer');
    }


    public function notes_load_list()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $list = $this->tools->notes_datatables();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $note) {
            $row = array();
            $no++;
            $row[] = $no;
            $row[] = $note->title;
            $row[] = dateformat($note->cdate);

            $row[] = '<a href="editnote?id=' . $note->id . '" class="btn btn-secondary btn-sm" title="EditE"><span class="fa fa-pencil"></span></a> <a class="btn btn-secondary btn-sm delete-object" href="#" data-object-id="' . $note->id . '" title="Delete"> <i class="fa fa-trash"></i> </a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->tools->notes_count_all(),
            "recordsFiltered" => $this->tools->notes_count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function addnote()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        if ($this->input->post('title')) {

            $title = $this->input->post('title', true);
            $content = $this->input->post('content');

            if ($this->tools->addnote($title, $content)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') . "  <a href='addnote' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='notes' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Add Note';
            $this->load->view('fixed/header', $head);
            $this->load->view('notes/addnote');
            $this->load->view('fixed/footer');
        }

    }

    public function editnote()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        if ($this->input->post('title')) {
            $id = $this->input->post('id');
            $title = $this->input->post('title', true);
            $content = $this->input->post('content');

            if ($this->tools->editnote($id, $title, $content)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('UPDATED') . "  <a href='notes' class='btn btn-secondary btn-sm'><span class='fa fa-eye' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            $id = $this->input->get('id');
            $data['note'] = $this->tools->note_v($id);
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Edit';
            $this->load->view('fixed/header', $head);
            $this->load->view('notes/editnote', $data);
            $this->load->view('fixed/footer');
        }

    }


    public function delete_note()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $id = $this->input->post('deleteid');

        if ($this->tools->deletenote($id)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }


    //documents


    public function documents()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Documents';
        $this->load->view('fixed/header', $head);
        $this->load->view('notes/documents');
        $this->load->view('fixed/footer');


    }

    public function document_load_list()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $list = $this->tools->document_datatables();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $document) {
            $row = array();
            $no++;
            $row[] = $no;
            $row[] = $document->title;
            $row[] = dateformat($document->cdate);

            $row[] = '<a href="' . base_url('userfiles/documents/' . $document->filename) . '" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i> ' . $this->lang->line('View') . '</a> <a class="btn btn-secondary btn-sm delete-object" href="#" data-object-id="' . $document->id . '"> <i class="fa fa-trash"></i> </a>';


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->tools->document_count_all(),
            "recordsFiltered" => $this->tools->document_count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }


    public function adddocument()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $this->load->helper(array('form'));
        $data['response'] = 3;
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Add Document';

        $this->load->view('fixed/header', $head);

        if ($this->input->post('title')) {
            $title = $this->input->post('title', true);
            $config['upload_path'] = './userfiles/documents';
            $config['allowed_types'] = 'docx|docs|txt|pdf|xls';
            $config['encrypt_name'] = TRUE;
            $config['max_size'] = 3000;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('userfile')) {
                $data['response'] = 0;
                $data['responsetext'] = 'File Upload Error';

            } else {
                $data['response'] = 1;
                $data['responsetext'] = 'Document Uploaded Successfully. <a href="documents"
                                       class="btn btn-secondary btn-sm"><i
                                                class="fa fa-folder"></i>
                                    </a>';
                $filename = $this->upload->data()['file_name'];
                $this->tools->adddocument($title, $filename);
            }

            $this->load->view('notes/adddocument', $data);
        } else {


            $this->load->view('notes/adddocument', $data);


        }
        $this->load->view('fixed/footer');


    }


    public function delete_document()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $id = $this->input->post('deleteid');

        if ($this->tools->deletedocument($id)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }


    public function pendingtasks()
    {
        // if (!$this->aauth->premission(6)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $tasks = $this->tools->pending_tasks();

        $tlist = '';
        $tc = 0;
        foreach ($tasks as $row) {


            $tlist .= '<a href="javascript:void(0)" class="list-group-item">
                      <div class="media">
                        <div class="media-left valign-middle"><i class="icon-bullhorn2 icon-bg-circle bg-cyan"></i></div>
                        <div class="media-body">
                          <h6 class="media-heading">' . $row['name'] . '</h6>
                          <p class="notification-text font-small-2 text-muted">Due date is ' . dateformat($row['duedate']) . '.</p><small>
                            Start <time  class="media-meta text-muted">' . dateformat($row['start']) . '</time></small>
                        </div>
                      </div></a>';
            $tc++;
        }

        echo json_encode(array('tasks' => $tlist, 'tcount' => $tc));


    }

    public function mark_task_as_read()
    {
        $id = $this->input->post('id');
    
        if ($id) {
            // Step 1: Get the name by ID (corrected syntax)
            $query = $this->db->select('name')
                              ->from('cberp_todolist')
                              ->where('id', $id)
                              ->get();
    
            if ($query->num_rows() > 0) {
                $name = $query->row()->name;
                $this->db->where('name', $name);
                $this->db->update('cberp_todolist', ['read_flag' => '1']);
    
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID not found']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
        }
    }
    
    

}
