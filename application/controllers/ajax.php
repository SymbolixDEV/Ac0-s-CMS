<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

		function show_realms_status()
        {
            $this->load->model('ajax_model');
            $data['base_url'] = base_url();
            $data['realms'] = $this->ajax_model->show_realms_status();
			
            $this->parser->parse($this->config->item('style').'/pages/realms', $data);

        }
		
		function show_realm_info($id='')
        {
            $this->load->model('ajax_model');
            $data['base_url'] = base_url();
            $data['realms'] = $this->ajax_model->show_realm_info($id);
			
			$this->parser->parse($this->config->item('style').'/pages/tooltip_realms', $data);
        }
        
        function username_availability($username='')
        {
            if($username!='' && strpos($username, '%')===False && strlen($username)>'3' && strlen($username)<'33')
            {
                $this->load->model('ajax_model');
                if($this->ajax_model->check_username($username)=='0')
                    echo '<img style="vertical-align:middle;" src="'.base_url('content/img/accept.png').'" />';
                else
                    echo '<img style="vertical-align:middle;" src="'.base_url('content/img/cancel.png').'" />';
            }
            else
                echo '<img style="vertical-align:middle;" src="'.base_url('content/img/cancel.png').'" />';
        }

        function password_availability($password='')
        {
            if($password!='' && strpos($password, '%')===False && strlen($password)>='6' && strlen($password)<='40')
                echo '<img style="vertical-align:middle;" src="'.base_url('content/img/accept.png').'" />';
            else
                echo '<img style="vertical-align:middle;" src="'.base_url('content/img/cancel.png').'" />';
        }

        function password_re_availability($password='', $confirm='')
        {
            if($password!='' && $confirm!='')
            {
                if($password==$confirm)
                    echo '<img style="vertical-align:middle;" src="'.base_url('content/img/accept.png').'" />';
                else
                    echo '<img style="vertical-align:middle;" src="'.base_url('content/img/cancel.png').'" />';
            }
            else
                echo '<img style="vertical-align:middle;" src="'.base_url('content/img/cancel.png').'" />';
        }

        function username_email($email='')
        {
            if($email!='' && $this->form_validation->valid_email($email))
            {
                $this->load->model('ajax_model');
                if($this->ajax_model->check_email($email)=='0')
                    echo '<img style="vertical-align:middle;" src="'.base_url('content/img/accept.png').'" />';
                else
                    echo '<img style="vertical-align:middle;" src="'.base_url('content/img/cancel.png').'" />';
            }
            else
                echo '<img style="vertical-align:middle;" src="'.base_url('content/img/cancel.png').'" />';
        }
}