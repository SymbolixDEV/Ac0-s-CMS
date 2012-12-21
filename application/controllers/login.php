<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();
		if($this->auto->is_logged)
			redirect('index.php/profile');
	} 
   
    function validation_login()
    {        
        $rules = $this->form_validation;
        $rules->set_rules('login_username', $this->lang->line('username'), 'required|alpha_numeric|trim');
        $rules->set_rules('login_password', $this->lang->line('password'), 'required|alpha_numeric|trim');
        $return_link = $this->input->post('return_link');
            
        if ($rules->run() == TRUE)
        {
            $this->load->model('login_model');
            $username = $this->input->post('login_username');
            $password = $this->input->post('login_password');            
            
            if($this->login_model->get_account($username, $password) == 1)
            {
                $data_user = $this->login_model->get_session_username($username);
                $data = array(
                    'is_logged_session' => TRUE,
                    'id' => $data_user['id'],
                    'username' => $data_user['username']
                    );
                $gmlevel = $this->login_model->get_user_gmlevel($data_user['id']);
                $data['gmlevel'] = ($gmlevel>5) ? $gmlevel : null;
                $data['gmlogged'] = ($gmlevel>5) ? TRUE : FALSE;
                $this->session->set_userdata($data);
                    
                $this->login_model->check_user_data($data_user['id'], $data_user['username']);
                    
                if($this->input->post('login_remember_me')=='1')
                {
                    $remember_me_token = strtoupper($username).'-'.$this->login_model->sha_password($username, $password);
                    $cookie = array(
                        'name'   => 'remember_me_token',
                        'value'  => $remember_me_token,
                        'expire' => '31536000'
                        );
                    $this->input->set_cookie($cookie);                                        
                }
                redirect($return_link);
            }
            else
            {
                $this->session->set_flashdata('login_status', "<div class='fail'><span class='ico_cancel'>Invalid account.</span></div>");
                redirect($return_link);
            }
        }
        else
        {
            $this->session->set_flashdata('login_status', "<div class='warning'><span class='ico_warning'>Enter username/password.</span></div>");
            redirect($return_link);
        }
    }
}