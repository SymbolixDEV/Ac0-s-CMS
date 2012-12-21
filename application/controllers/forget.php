<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forget extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
            if($this->auto->is_logged)
                redirect('index.php/profile');
        } 

	public function index()
	{
            $data['title'] = $this->lang->line('forgot_password');
            $this->load->view('main_view', $data);
	}
        
        function rundom($l)
        {
            $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $rundom = '';
            for ($i = 0; $i < $l; ++$i)
			$rundom .= substr($characters, (mt_rand() % strlen($characters)), 1);

            return $rundom;
        }
        
        function validation_forget()
        {
            $rules = $this->form_validation;
            $rules->set_rules('forget_username', $this->lang->line('username'), 'required|min_length[3]|max_length[32]|alpha_dash|trim');
            $rules->set_rules('forget_security_answer', $this->lang->line('security_answer'), 'required|trim');
            $rules->set_rules('forget_security_question', $this->lang->line('security_question'), 'required|alpha_dash|trim');
            
            if ($rules->run() == TRUE)
            {
                $this->load->model('forget_model');
                $username = $this->input->post('forget_username');
                $question = $this->input->post('forget_security_question');
                $answer = $this->input->post('forget_security_answer');
                $id = $this->forget_model->get_user_id($username);
                if($this->forget_model->check_user_security($id, $question, $answer)=='1')
                {
                    $num = $this->rundom('6');
                    if($this->forget_model->set_new_password($username, $num)=='1')
                    {
                        $config['protocol'] = $this->config->item('protocol');
                        $config['smtp_host'] = $this->config->item('smtp_host');
                        $config['smtp_port'] = $this->config->item('smtp_port');
                        $config['smtp_user'] = $this->config->item('smtp_user');
                        $config['smtp_pass'] = $this->config->item('smtp_pass');

                        $this->load->library('email', $config);
                        $this->email->set_newline("\r\n");

                        $this->email->from($this->config->item('smtp_user'), $this->config->item('site_title'));
                        $this->email->to($this->forget_model->get_email($username));		
                        $this->email->subject($this->config->item('site_title'));		
                        $this->email->message('Greetings from '.$this->config->item('site_title').'.
                            Here is your new password
                            -------------------------------------------
                            Username: '.$username.'
                            Password: '.$num.'
                            -------------------------------------------
                            '.$this->config->item('site_title').' '.date('Y'));

                        if($this->email->send())
                        {
                                $message = "Your new password is successfuly sended to your email.";
                        }
                        else
                        {
                            $message = "Successfuly. Your new password is: ".$num.".";
                        }
                        $this->session->set_flashdata('status', "<div class='success'><span class='ico_accept'>".$message."</span></div>");
                        redirect('index.php/forget');
                    }
                    else
                    {
                        $this->session->set_flashdata('status', "<div class='fail'><span class='ico_cancel'>Error on setting new password.</span></div>");
                        redirect('index.php/forget');
                    }
                }
                else
                {
                    $this->session->set_flashdata('status', "<div class='fail'><span class='ico_cancel'>Invalid Security Question/Answer.</span></div>");
                    redirect('index.php/forget');
                }
            }
            else
            {
                $this->session->set_flashdata('status', "<div class='warning'><span class='ico_warning'>Invalid type.</span></div>");
                redirect('index.php/forget');
            }
                
        }
}