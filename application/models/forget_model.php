<?php
class Forget_model extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            
        } 
        
        function sha_password($username, $password)
        {
            $username = strtoupper($username);
            $password = strtoupper($password);
            return SHA1($username.':'.$password);
        }
    
	function check_user_security($id, $question, $answer)
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->select('id');
            $this->cms->where('id', $id);
            $this->cms->where('security_question', $question);
            $this->cms->where('security_answer', $answer);

            $query = $this->cms->get('account_addition', '1');
            return $this->cms->affected_rows();
        }
        
        function set_new_password($username, $password)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $password = $this->sha_password($username, $password);
            $this->auth->where('username', $username);
            $data = array(
                'sha_pass_hash' => $password
            );
            $query = $this->auth->update('account', $data);

            return $this->auth->affected_rows();
        }
        
        function get_email($username)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('email');
            $this->auth->where('username', $username);
            
            $query = $this->auth->get('account', '1');
            foreach ($query->result_array() as $row)
            {
                return $row['email'];
            }
        }
        
        function get_user_id($username)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id');
            $this->auth->where('username', $username);
            
            $query = $this->auth->get('account', '1');
            foreach ($query->result_array() as $row)
            {
                return $row['id'];
            }
        }
}
?>