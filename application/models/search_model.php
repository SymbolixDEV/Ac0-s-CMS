<?php
class Search_model extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->auth = $this->load->database('auth', TRUE);  
        } 
        
        function get_realms()
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id, name, char_db');
            $query = $this->auth->get('realmlist');
            $cont = '';
			
			return $query->result_array();
        }
        
		function get_characters($str)
        {
			$realms = $this->get_realms();
			
			$cont = array();
			$i = 0;
			
			foreach ($realms as $realm)
			{
				$this->load->database();
				$config['hostname'] = $this->db->hostname;
				$config['username'] = $this->db->username;
				$config['password'] = $this->db->password;
				$config['database'] = $realm['char_db'];
				$config['dbdriver'] = "mysql";
				$config['dbprefix'] = "";
				$config['pconnect'] = FALSE;
				$config['db_debug'] = TRUE;
				$config['cache_on'] = FALSE;
				$config['cachedir'] = "";
				$config['char_set'] = "utf8";
				$config['dbcollat'] = "utf8_general_ci";

				$this->characters = $this->load->database($config, TRUE); 

				$this->characters->select('guid, name, level, class, gender, race, online');
				foreach(explode(" ", $str) as $like)
					$this->characters->like('name', $like);
				$query = $this->characters->get('characters', '30');
				
				if($query->num_rows()>0)
				{
					foreach($query->result_array() as $row)
					{
						$i++;
						$cont[$i]['guid'] = $row['guid'];
						$cont[$i]['name'] = $row['name'];
						$cont[$i]['icon'] = '<img src="'.base_url('content/img/icon/class/'.$row['class']).'.gif" title="Class" />&nbsp;<img src="'.base_url('content/img/icon/race/'.$row['race'].'-'.$row['gender']).'.gif" title="Race" />';
						$cont[$i]['level'] = $row['level'];
						$cont[$i]['status'] = ($row['online']==1) ? '<span style="color: green;">Online</span>' : '<span style="color: red;">Offline</span>';
						$cont[$i]['realm_name'] = $realm['name'];
						$cont[$i]['realm_id'] = $realm['id'];
					}
				}
			}
            return $cont;
        }
}
?>