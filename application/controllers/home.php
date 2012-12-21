<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function index($article='0', $page='0', $limit='3')
    {
        $article = (int) $article;
        $page = (int) $page;
        $limit = (int) $limit;
        if ($article >= 0 && is_int($article) && $page >= 0 && is_int($page) && $limit > 0 && is_int($limit))
        {
            $this->load->model('home_model');
            $start = ($page!='0') ? ($page - 1) * $limit : 0;
			
            $data['news'] = $this->home_model->show_news($article, $start, $limit);
            $data['pages'] = $this->show_pages($article, $page, $limit);
            $data['vote_pages'] = $this->show_vote_sites();
            $data['title'] = $this->lang->line('home');
            $this->load->view('main_view', $data);
        }
        else
            redirect('home');
    }
    
    function post_comment()
    {
        $rules = $this->form_validation;
        $rules->set_rules('comment_textarea', 'Post', 'required|alpha_dash|trim|htmlspecialchars');
        $rules->set_rules('news_id', 'ID', 'greater_than[0]|required|integer|is_natural');
        
        $id = $this->input->post('news_id');   
        if ($rules->run() == TRUE)
        {
            if($this->session->userdata('is_logged_session')==TRUE)
            {
                $comment_text = $this->input->post('comment_textarea');
                $poster_id = $this->session->userdata('id');
                $this->load->model('home_model');
                $this->home_model->add_comment($id, $comment_text, $poster_id);
            }
        }
        redirect('index.php/home/index/'.$id);
    }
    
    function out()
    {
        $this->session->sess_destroy();
        delete_cookie("remember_me_token");
        redirect('');
    }
    
    function show_vote_sites()
    {
        $this->load->model('vote_model');
        $this->vote_model->check_voting();
        return $this->vote_model->show_vote_sites();
    }
    
    function show_pages($id='0', $page='0', $limit='3')
    {
        $this->load->model('home_model');
        $total_rows = $this->home_model->show_news_rows();
        $type='index/'.$id.'';
        if($id!='0')
        {
            $total_rows = $this->home_model->show_comment_rows($id);
            if($total_rows=='0')
                $total_rows='1';
            $limit .= '#comments';
        }
        $cont = '';
        if($total_rows>'0')
        {
            $start = ($page!='0') ? ($page - 1) * $limit : 0;
            $page = ($page == 0) ? 1 : $page;  
            $prev = ($page == 1) ? 1 : $page - 1;         //previous page is page - 1
            $next = $page + 1;                      //next page is page + 1
            $lastpage = ceil($total_rows/$limit);   //lastpage is = total pages / items per page, rounded up.
            $lpm1 = $lastpage - 1;
            $all_pages = ceil($total_rows/$limit);
            $cont = '<center style="margin:10px 0px 10px 0px;"><ol style="list-style: none;display: inline;">';
            if($page!='1')
            {
                $cont .= '<a href="'.base_url('index.php/home/'.$type.'/'.$prev.'/'.$limit).'"><li style="display: inline;"><<<</li></a>';
            }
            else
            {
                $cont .= '<a><li style="display: inline;"><<<</li></a>';
            }
            if($page>3)
            {
                $i=0;
                $br=0;
                while($i<$page-3 && $br<3)
                {
                    $br++;
                    $i++;
                    $cont .= '<a href="'.base_url('index.php/home/'.$type.'/'.$i.'/'.$limit).'"><li style="display: inline;"> '.$i.' </li></a>';
                }
                if($all_pages>6 && $page>6)
                    $cont .='<a><li style="display: inline;">...</li></a>';
            }
            $i = 0;
            if($all_pages<4) { $br = $all_pages; }
            else 
            {
                $br = $page+2; 
                if($page>3)
                    $i=$page-3;   
            }
            if($all_pages<=5) { $page_counter_middle = $all_pages; }
            else { $page_counter_middle = $all_pages-2; }
		
            while($i<$br && $i<$page_counter_middle)
            {
                $i++;
                $class = '';
                $href = 'href="'.base_url('index.php/home/'.$type.'/'.$i.'/'.$limit).'"';
                if($i==$page){ $class = 'active'; $href = ''; }
                $cont .='<a '.$href.'><li style="display: inline;" class="'.$class.'"> '.$i.' </li></a>';
            }
            if($all_pages>6 && $page<$all_pages-5)
            {
                $cont .='<a><li style="display: inline;">...</li></a>';
            }
            if($all_pages>3)
            {
                $i2 = $lastpage;
                $i2_3 = $lastpage - 3;

                if($all_pages-$page<5)
                {
                    $i2_3 = $lastpage - 2;
                }
                    
                if($all_pages<6)
                {
                    $i2_3 = $br;
                }
                while($i2_3<$i2)
                {
                    $i2_3++;
                    $class = '';
                    $href = 'href="'.base_url('index.php/home/'.$type.'/'.$i2_3.'/'.$limit).'"';
                    if($i2_3==$page){ $class = 'active'; $href = ''; }
                    $cont .='<a '.$href.'><li style="display: inline;" class="'.$class.'"> '.$i2_3.' </li></a>';
                }
            }
            if($page!=$lastpage)
            {
                $cont .= '<a href="'.base_url('index.php/home/'.$type.'/'.$next.'/'.$limit).'"><li style="display: inline;">>>></li></a>';
            }
            else
            {
                $cont .= '<a><li style="display: inline;">>>></li></a>';
            }
            $cont .= '</ol></center>';
        }
        return $cont;
    }
}