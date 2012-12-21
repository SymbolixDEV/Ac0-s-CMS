<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vote extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
        }        
        
        public function vote_site($id)
	{
            $this->load->model('vote_model');
            $user_ip = getenv("REMOTE_ADDR");
            $user_id = $this->session->userdata('id');
            $link = $this->vote_model->select_vote_link($id);
            $points = $this->vote_model->select_vote_points($id);
            if($this->session->userdata('is_logged_session'))
            {
                if($this->vote_model->_check_voting_user($id, $user_id)=='0' && $this->vote_model->_check_voting_ip($id, $user_ip)=='0')
                {
                    if($this->vote_model->add_points($user_id, $points)=='1')
                    {
                        $this->vote_model->add_voting($id, $user_ip, $user_id);
                        redirect($link);
                    }
                    else
                        redirect('');
                }
                else
                    redirect('');
            }
            else
            {
                if($this->vote_model->_check_voting_ip($id, $user_ip)=='0')
                {
                    $this->vote_model->add_voting($id, $user_ip);
                    redirect($link);
                }
                else
                    redirect('');
            } 
        }
}
?>
