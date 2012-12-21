<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auto {

	public $is_logged, $is_admin, $is_baned, $language;
    
    public function __construct() 
    {	
		$this->is_logged = $this->is_logged_function();
		$this->language = $this->language_function();
		$this->is_baned = ($this->is_logged) ? $this->check_account_banned() : FALSE;
		$this->is_admin = ($this->is_logged) ? $this->check_account_access() : FALSE;
		$CI =& get_instance();
        $CI->lang->load('main', $this->language);
		$this->check_log_admin_function();
    }
	
	function check_account_banned()
	{
		$CI =& get_instance();
        $CI->load->model('cms');
		
        return ($CI->cms->check_account_banned($CI->session->userdata('id')) == 1) ? TRUE : FALSE;
    }
	
	function check_account_access()
	{
		$CI =& get_instance();
        $CI->load->model('cms');
		
        return ($CI->cms->check_account_access($CI->session->userdata('id')) == 1) ? TRUE : FALSE;
    }
    
    function check_log_admin_function()
    {
       $CI =& get_instance();
       if($CI->uri->segment(1)=="acp")
       {
            if($CI->session->userdata('gmlevel')<6)
                redirect('');
            if($CI->session->userdata('gmlogged')==TRUE)
            {
                $this->logging("Logged to ACP", '');
                $CI->session->unset_userdata('gmlogged');
            }
       }
    }
	
	function relative_time($time = '')
	{
		if(empty($time) or $time == '')
			return;
			
		$current = time();
		
		if($current - $time < 10)
			return 'In a few seconds.';
			
		if($current - $time < 50)
			return 'Before '.($current - $time).' seconds.';
			
		if($current - $time < 120)
			return 'About a minute ago.';
		
		if($current - $time < 3600)
			return 'Before '.floor(($current - $time) / 60).' minutes.';
			
		if($current - $time < 7200)
			return 'About a hour ago.';	
			
		if($current - $time < 86400)
			return (date('d', $current) != date('d', $time)) ? 'Yesterday '.date('h:i A', $time) : 'Today '.date('h:i A', $time);
			
		return (date('Y', $current) != date('Y', $time)) ? date('d F Y', $time) : date('d F', $time);
	}
	
	function show_total_accounts()
	{
		$CI =& get_instance();
        $CI->load->model('cms');

        return $CI->cms->show_total_accounts();
    }
	
    function logging($comment, $type)
    {
        $CI =& get_instance();
        $CI->load->model('cms');
        return $CI->cms->insert_log($comment, $type);
    }
    
    function return_log($type='')
    {        
        $CI =& get_instance();
        $CI->load->model('cms');
        return $CI->cms->return_log($type);
    }
	
	function get_realms()
	{
		$CI =& get_instance();
        $CI->load->model('cms');
        return $CI->cms->return_reams();
	}
	
	function get_characters()
	{
		$CI =& get_instance();
		if(!$CI->session->userdata('is_logged_session'))
			return;
		
        $CI->load->model('cms');
        return $CI->cms->return_characters($CI->session->userdata('id'));
	}
    
    function pages($page, $pages, $controller)
    {
        $CI =& get_instance();
        $cont = '';
        foreach($pages as $page_show)
        {
            $active = '';
            if($page==$page_show)
                $active = ' class="active" ';
            $uri = ($CI->uri->segment(2)=='') ? 'home' : $CI->uri->segment(2);
            $cont .= '<li><a href="'.base_url('index.php/acp/'.$uri.'/index/'.$page_show).'" '.$active.'>'.ucfirst($page_show).' '.ucfirst($controller).'</a></li>';
        }
        return $cont;
    }
    
    function head_content()
    {
        $cont = '';
        $cont .= '<meta http-equiv="content-type" content="text/html;charset=utf-8" />';
        $cont .= '<link rel="shortcut icon" href="'.base_url('content/img/favicon.ico').'" type="image/x-icon">';
        $cont .= '<link rel="stylesheet" type="text/css" href="'.base_url('content/css/default_style.css').'" />';
        $cont .= '<script type="text/javascript" src="'.base_url('content/js/jquery.js').'"></script>';
		$cont .= '<script type="text/javascript" src="'.base_url('content/js/core.js').'"></script>';
        $cont .= '<script type="text/javascript" src="'.base_url('content/js/tooltip.js').'"></script>';
		$cont .= '<script type="text/javascript" src="'.base_url('content/js/main.js').'"></script>';
		$cont .= '<script type="text/javascript" src="'.base_url('content/js/wow.js').'"></script>';
		$cont .= '<script type="text/javascript" src="'.base_url('content/js/jquery.placeholder.min.js').'"></script>';
		$cont .= '<script type="text/javascript" src="http://cdn.openwow.com/api/tooltip.js"></script>';
        
        return $cont;
    }
    
    function realms_status()
    {
        $CI =& get_instance();
        return '
		<span id="realmstatus_info"><center><img src="'.base_url('content/img/loader.gif').'" /></center></span>
        <script>
			window.onload = show_realms;
			function show_realms(){
				showContent("'.base_url('index.php/ajax/show_realms_status').'", "realmstatus_info");
				setTimeout("show_realms()", 10000);
			}
        </script><noscript>*<small>Please active your javascript to see realm status</small>.</noscript>';
    }
    
    function membership($id='')
    {
        $CI =& get_instance();
        
        if($id!='')
        {
            $CI->load->model('cms');
            return $CI->cms->show_content_header($id);
        }
        
        $is_logged_session = $CI->session->userdata('is_logged_session');
        
        if($is_logged_session == TRUE)
        {
            $CI->load->model('cms');
            $cont = $CI->cms->show_content_header();
            return $cont;
        }
        else
            return array();
    }
    
    function login_form()
    {
        $CI =& get_instance();
        $is_logged_session = $CI->session->userdata('is_logged_session');
        $cont = array();
        if($is_logged_session != TRUE)
            $cont = array(array());
        
        return $cont;
    }
    
    function is_logged_function()
    {
        $CI =& get_instance();
        $CI->load->helper('cookie');
        $is_logged_session = $CI->session->userdata('is_logged_session');
        $remember_me_token = get_cookie('remember_me_token');
        
        if($is_logged_session)
        { 
            return TRUE;
        }
        else
        {
            if(!empty($remember_me_token))
            {
                $CI->load->model("login_model");
                if($CI->login_model->check_cookie_token($remember_me_token) == '1')
                {
                    $username = explode('-', $remember_me_token);
                    $data_user = $CI->login_model->get_session_username($username[0]);
                    $data = array(
                        'is_logged_session' => TRUE,
                        'id' => $data_user['id'],
                        'username' => $data_user['username']
                        );
                    $gmlevel = $CI->login_model->get_user_gmlevel($data_user['id']);
                    $data['gmlevel'] = ($gmlevel>5) ? $gmlevel : null;
                    $data['gmlogged'] = ($gmlevel>5) ? TRUE : FALSE;
                    $CI->session->set_userdata($data);
                    return TRUE;
                }
                else
                {
                    delete_cookie("remember_me_token");
                    return FALSE;
                }
            }
            else
                return FALSE;
        }
    }
	
	function language_function()
    {
        $CI =& get_instance();
        $CI->load->helper('cookie');
        $language_cookie = get_cookie('language');
        $language_session = $CI->session->userdata('language_session');
            
        if($language_session=='')
        {
            if(empty($language_cookie))
            {
                $CI->load->model('country');
                $country = $CI->country->get_ip(getenv("REMOTE_ADDR"));
                
                $CI->load->model('cms');
                $languages = $CI->cms->return_languages();
                $language_string = '';
                foreach($languages as $language)
                {
                    foreach($language['countries'] as $cont)
                    {
                        if(trim($cont)==trim($country))
                            $language_string = $language['language_string'];
                    }
                }
                if($language_string=='')
                    $language_string = 'english';
                
                $cookie = array(
                    'name'   => 'language',
                    'value'  => $language_string,
                    'expire' => '31536000'
                    );
                $CI->input->set_cookie($cookie);  
                    
                $session = array(
                    'language_session' => $language_string
                    );
                $CI->session->set_userdata($session);
            }
            else
            {
                $data = array(
                    'language_session' => $language_cookie
                );
                $CI->session->set_userdata($data);
            }
        }
            
        return $CI->session->userdata('language_session');
    }   
    
    function show_all_languages()
    {
        $CI =& get_instance();
        $CI->load->model('cms');
        return $CI->cms->return_languages();
    }
}

?>