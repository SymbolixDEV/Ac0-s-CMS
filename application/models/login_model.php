<?php
class Login_model extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->auth = $this->load->database('auth', TRUE);  
        } 
        
        function sha_password($username, $password)
        {
            $username = strtoupper($username);
            $password = strtoupper($password);
            return SHA1($username.':'.$password);
        }
        
        function get_account($username, $password)
        {
            $password = $this->sha_password($username, $password);
            $this->auth->where('username', $username);
            $this->auth->where('sha_pass_hash', $password);
            $query = $this->auth->get('account');

            return $this->auth->affected_rows();
        }
        
        function get_user_gmlevel($id)
        {
            $this->auth->select('gmlevel');
            $this->auth->where('id', $id);
            $query = $this->auth->get('account_access', '1');
            
            if($query->num_rows()>0)
            {
                foreach($query->row_array() as $row)
                {
                    return $row[0];
                }
            }
            else
                return 0;
        }
        
        function get_session_username($username)
        {
            $this->auth->select('id, username');
            $this->auth->where('username', $username);
            $query = $this->auth->get('account', '1');

            return $query->row_array();
        }
        
        function add_user_data($id, $username)
        {
            $this->cms = $this->load->database('default', TRUE);  

            $data = array(
                'id' => $id,
                'username' => $username
            );
            $this->cms->insert('account_addition', $data);
        }
            
        
        function check_user_data($id, $username)
        {
            $this->cms = $this->load->database('default', TRUE);  

            $this->cms->select('id');
            $this->cms->where('id', $id);
            $query = $this->cms->get('account_addition', '1');

            if($query->num_rows()=='0')
            {
                $this->add_user_data($id, $username);
            }
        }
        
        function check_cookie_token($remember_me_token)
        {
            $data = explode('-', $remember_me_token);
            $username = $data[0];
            $password = $data[1];
            $this->auth->where('username', $username);
            $this->auth->where('sha_pass_hash', $password);
            $query = $this->auth->get('account');

            return $this->auth->affected_rows();
        }
}
?>