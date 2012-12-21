<?php
class Acp_console extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->cms = $this->load->database('default', TRUE);  
        }
        
        function fill_drop_down()
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id, name');
            $query = $this->auth->get('realmlist');

            $data[0] = 'Choose Realmlist';
            if ($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $data[$row['id']] = $row['name'];
                }
            }
            return $data;
        }
        
        function realm_info($realm_id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id, name, address, ra, ra_port, soap, soap_port');
            $this->auth->where('id', $realm_id);
            $query = $this->db->get('realmlist');

            return $query->row_array();
        }
}
?>