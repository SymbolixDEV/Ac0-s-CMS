<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
            
        }
        
	public function index($page = 'menage', $id='')
	{
            $data['title'] = ucfirst($page);
            $data['ui'] = 'News';
            $data['left_content'] = $this->auto->pages($page, array('menage', 'add', 'logs'), 'news');
            $data['right_content'] = $this->show_right_content($page, $id);
            $this->load->view('acp/main_view', $data);
	}
        
        
        function show_right_content($page, $id)
        {
            if($page=='edit' && $id=='' || $page=='delete' && $id=='')
            {
                $this->index();
                return;
            }
            
            $cont = '';
            $this->load->model('acp_news');
            switch($page)
            {
                case "add":
                    $cont .= $this->session->flashdata('not_filled');
                    $cont .= form_open('index.php/acp/news/add_validation');
                    $data = array(
                        'name'        => 'news_title',
                        'id'          => 'news_title'
                        );
                    $cont .= '<label for="news_title">News Title</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'news_content',
                        'id'          => 'news_content',
                        'style'       => 'width: 655px;min-width: 655px;max-width:655px;'
                        );
                    $cont .= '<br /><label for="news_content">News Content</label>: <br />'.form_textarea($data);
                    $data = array(
                        'name'        => 'news_add_submit',
                        'id'          => 'news_add_submit',
                        'value'       => 'Submit',
                        );
                    $cont .= '<br />'.form_submit($data);
                    $cont .= form_close();
                    break;
                
                case "edit":
                    $cont .= $this->acp_news->return_new($id);
                    break;
                
                case "logs":
                    $cont .= "<table width='100%' border = '#000000'>
                                <tr><td>Account</td><td>Action</td><td>Date</td></tr>";
                    foreach($this->auto->return_log('news') as $row)
                    {
                        $cont .= "<tr><td>".$row['account']."</td><td>".$row['comment']."</td><td>".$row['date']."</td></tr>";
                    }
                    $cont .= "</table>";
                    break;
                
                case "delete":
                    $this->acp_news->delete_news($id);
                    $this->auto->logging('Deleted New ID: '.$id, 'news');
                    redirect('index.php/acp/news');
                    break;
                
                default:
                    $cont .= $this->acp_news->return_news();
                    break;
            }
            return $cont;
        }
        
        function edit_validation()
        {
            $rules = $this->form_validation;
            $rules->set_rules('news_id', 'ID', 'required|trim');
            $rules->set_rules('news_title', 'Title', 'required|trim');
            $rules->set_rules('news_content', 'Content', 'required|trim');
            
            $id = $this->input->post('news_id');
            
            if ($rules->run() == TRUE)
            {
                $news_title = $this->input->post('news_title');
                $news_content = $this->input->post('news_content');
                $this->load->model('acp_news');
                $this->acp_news->update_new($id,$news_title,$news_content);
                $this->auto->logging('Edited New ID: '.$id, 'news');
                redirect('index.php/acp/news', 'refresh');
            }
            else
            {
                $this->session->set_flashdata('not_filled', "<div class='fail'><span class='ico_cancel'>Please fill every field.</span></div>");
                redirect('index.php/acp/news/index/edit/'.$id, 'refresh');
            }
        }
        function add_validation()
        {
            $rules = $this->form_validation;
            $rules->set_rules('news_title', 'Title', 'required|trim');
            $rules->set_rules('news_content', 'Content', 'required|trim');
            
            if ($rules->run() == TRUE)
            {
                $news_title = $this->input->post('news_title');
                $news_content = $this->input->post('news_content');
                $this->load->model('acp_news');
                $this->acp_news->add_new($news_title,$news_content);
                $this->auto->logging('Added News', 'news');
                redirect('index.php/acp/news', 'refresh');
            }
            else
            {
                $this->session->set_flashdata('not_filled', "<div class='fail'><span class='ico_cancel'>Please fill every field.</span></div>");
                redirect('index.php/acp/news/index/add', 'refresh');
            }
        }
}