<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
            if($this->auto->is_logged)
                redirect('');
			
			$this->load->helper('captcha');
        } 

		public function index()
		{
				$data['title'] = "Register";
				$this->load->view('main_view', $data);
		}
        
        function validation_register()
        {
            $rules = $this->form_validation;
            $rules->set_rules('register_username', 'Username', 'required|min_length[3]|max_length[32]|alpha_dash');
            $rules->set_rules('register_password', 'Password', 'required|min_length[6]|max_length[40]|trim');
            $rules->set_rules('register_re_password', 'Password Confirm', 'required|matches[register_password]|trim');
            $rules->set_rules('register_email', 'Email', 'required|valid_email|trim');
            $rules->set_rules('register_security_question', 'Security Question', 'required');
            $rules->set_rules('register_security_answer', 'Security Answer', 'required|alpha_dash|trim');
			$rules->set_rules('register_captcha', 'Captcha', 'required|callback_captcha_check');

            if ($rules->run() == TRUE)
            {
                $this->load->model('register_model');
                $username = $this->input->post('register_username');
                $password = $this->input->post('register_password');
                $email = $this->input->post('register_email');
                
                if($this->register_model->check_username($username) == '1')
                {
                    $this->session->set_flashdata('status', "<div class='fail'><span class='ico_cancel'>Username exist.</span></div>");
                    redirect('index.php/register');
                }
                if($this->register_model->check_email($email) == '1')
                {
                    $this->session->set_flashdata('status', "<div class='fail'><span class='ico_cancel'>Email exist.</span></div>");
                    redirect('index.php/register');
                }
                
                if($this->register_model->register_account($username, $password, $email) == '1')
                {
                    $data_user = $this->register_model->get_session_username($username);
                    $data = array(
                        'is_logged_session' => TRUE,
                        'id' => $data_user['id'],
                        'username' => $data_user['username']
                    );
                    $this->session->set_userdata($data);
                    $this->register_model->add_addition_information($data_user['id'], $username, $this->input->post('register_security_question'), $this->input->post('register_security_answer'));
                    
                    redirect('index.php/profile');
                }
            }
            else
            {
                $this->session->set_flashdata('status', "<div class='warning'><span class='ico_warning'>You must fill correct every fields.</span></div>");
                redirect('index.php/register');
            }
        }
		
		function captcha_check()
		{
            // Then see if a captcha exists:
            $exp=time()-600;
            $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
            $binds = array($this->input->post('register_captcha'), $this->input->ip_address(), $exp);
			$this->cms = $this->load->database('default', TRUE);  
            $query = $this->cms->query($sql, $binds);
            $row = $query->row();

            if ($row->count == 0)
            {
                $this->form_validation->set_message('status', "<div class='warning'><span class='ico_warning'>Incorrect Captcha.</span></div>");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
		}
}