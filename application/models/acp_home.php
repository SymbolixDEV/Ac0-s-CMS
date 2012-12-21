<?php
class Acp_home extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->cms = $this->load->database('default', TRUE);  
        }
        
        function accounts_today()
        {
            $data = date("Y-m-d");
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id');
            $this->auth->like('joindate', $data);
            $query = $this->auth->get('account');
            
            return $query->num_rows();
        }
        
        function all_accounts()
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id');
            $query = $this->auth->get('account');
            
            return $query->num_rows();
        }
        
}
?>