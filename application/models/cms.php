<?php
class Cms extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->cms = $this->load->database('default', TRUE);  
        } 
		
		function return_reams()
		{
			$this->cms = $this->load->database('auth', TRUE);  
			$this->cms->select('id, name');
            $query = $this->cms->get('realmlist');
            $i = 0;
            $cont = array();
			if($query->num_rows() > 0)
            {
				foreach ($query->result_array() as $row)
				{
					$i++;
					$cont[$i]['link'] = base_url('index.php/status/index/'.$row['id']);
					$cont[$i]['name'] = $row['name']; 
					$cont[$i]['top_link'] = base_url('index.php/top_killers/index/'.$row['id']);
					$cont[$i]['arena_link'] = base_url('index.php/top_arenas/index/'.$row['id']);
					$cont[$i]['char_ban_link'] = base_url('index.php/bans/character/'.$row['id']);
					$cont[$i]['id'] = $row['id'];
				}
			}
			else
			{
				$i++;
				$cont[$i]['link'] = "#";
                $cont[$i]['name'] = "No Realms";
				$cont[$i]['top_link'] = "#";
				$cont[$i]['arena_link'] = "#";
				$cont[$i]['char_ban_link'] = "#";
				$cont[$i]['id'] = 0;
			}
            
            return $cont;
		}
		
		function show_total_accounts()
		{
			$this->auth = $this->load->database('auth', TRUE);

            $this->auth->select('id');
            $query = $this->auth->get('account');

            return $query->num_rows();
		}
		
		function get_realm_chracters($id, $characters_db, $realm_id)
		{
			$this->load->database();
            $config['hostname'] = $this->db->hostname;
            $config['username'] = $this->db->username;
            $config['password'] = $this->db->password;
            $config['database'] = $characters_db;
            $config['dbdriver'] = "mysql";
            $config['dbprefix'] = "";
            $config['pconnect'] = FALSE;
            $config['db_debug'] = TRUE;
            $config['cache_on'] = FALSE;
            $config['cachedir'] = "";
            $config['char_set'] = "utf8";
            $config['dbcollat'] = "utf8_general_ci";

            $this->characters = $this->load->database($config, TRUE);  

            $this->characters->select('guid, race, gender, class, name');
            $this->characters->where('account', $id);
            $query = $this->characters->get('characters');
			
			$cont = array();
			$i = 0;
			
			if($query->num_rows() > 0)
            {
				foreach ($query->result_array() as $row)
				{
					$i++;
					$cont[$i]['char_link'] = base_url('index.php/character/index/'.$row['guid'].'/'.$realm_id);
					$cont[$i]['char_name'] = $row['name']; 
					$cont[$i]['char_class'] = $row['class'];
					$cont[$i]['char_race'] = $row['race'];
					$cont[$i]['char_gender'] = $row['gender'];
				}
			}
			else
			{
				$i++;
				$cont[$i]['char_link'] = "#";
                $cont[$i]['char_name'] = "No chars for that realm";
				$cont[$i]['char_class'] = "";
				$cont[$i]['char_race'] = "";
				$cont[$i]['char_gender'] = "";
			}
            return $cont;
		}
		
		function return_characters($id)
		{
			$this->auth = $this->load->database('auth', TRUE);  
			$this->auth->select('name, id, char_db');
            $query = $this->auth->get('realmlist');
            $cont = array();
			$i = 0;
			if($query->num_rows() > 0)
            {
				foreach ($query->result_array() as $row)
				{
					$i++;
					$cont[$i]['link'] = base_url('index.php/status/index/'.$row['id']);
					$cont[$i]['name'] = $row['name'];   
					$cont[$i]['characters'] = $this->get_realm_chracters($id, $row['char_db'], $row['id']);
				}
			}
			else
			{
				$i++;
				$cont[$i]['link'] = "#";
                $cont[$i]['name'] = "No realms";    
			}
            return $cont;
		}
        
        function insert_log($comment='', $type='')
        {
            if($comment=='')
                return;
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->where('type', $type);
            $data = array(
                'type' => $type,
                'comment' => $comment,
                'account' => $this->session->userdata('username')
            );
            $query = $this->cms->insert('logs', $data);
        }
        
        function return_log($type='')
        {
            $this->cms = $this->load->database('default', TRUE);  
            if($type!='')
                $this->cms->where('type', $type);
            $this->cms->order_by('date', 'desc');
            $query = $this->cms->get('logs', '20');

            return $query->result_array();
        }
        
        function get_username($id)
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->where('id', $id);
            $query = $this->cms->get('account_addition', '1');

            return $query->row_array();
        }
		
		function get_user_posts($id)
        {
            $this->cms = $this->load->database('default', TRUE);  
			$this->cms->select('id');
            $this->cms->where('poster', $id);
            $query = $this->cms->get('forum_posts');

            return $query->num_rows();
        }
    
		function show_content_header($id='')
        {
            $this->cms = $this->load->database('default', TRUE);  
            if($id=='')
                $id = $this->session->userdata('id');
            $cms_userdata = $this->get_username($id);

            $cont = array();
            $cont[0]['username'] = $this->session->userdata('username');
            $cont[0]['user_id'] = $cms_userdata['id'];
            $cont[0]['user_vp'] = $cms_userdata['vp'];
            $cont[0]['user_dp'] = $cms_userdata['dp'];
            $cont[0]['user_posts'] = $cms_userdata['posts'];
            $cont[0]['user_ip'] = $_SERVER['REMOTE_ADDR'];
            $cont[0]['user_nickname'] = $cms_userdata['username'];
			$cont[0]['user_location'] = $cms_userdata['location'];
			$cont[0]['user_gender'] = $cms_userdata['gender'];
			$cont[0]['user_posts'] = $this->get_user_posts($id);
			$cont[0]['user_avatar'] = $cms_userdata['avatar'];
			$cont[0]['user_rank'] = $cms_userdata['rank'];
			$cont[0]['user_reputation'] = $cms_userdata['reputation'];
            
            return $cont;
        }
        
        function for_ech_realm($id='',$selected='')
        {
            if(empty($id))
                return;
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id, name, char_db');
            $query = $this->auth->get('realmlist');
            $no_chars = 0;
            $cont = '';
            $cont .= '<div class="styled-select"><select name="character"><option value="'.$selected.'">'.$selected.'</option>';
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $cont .= '<option disabled="disabled" class="disabled">'.$row['name'].'</option>';
                    if($this->show_realm_characters($row['char_db'], $id)!='')
                        $cont .= $this->show_realm_characters($row['char_db'], $id);
                    else
                        $no_chars++;     
                }
                if($no_chars>0)
                    $cont .= '<option>No Characters</option>';
            }
            else
                $cont .= '<option disabled="disabled" class="disabled">No Realms</option>';
            $cont .= '</select></div>';
            if($no_chars == TRUE)
                $cont .= '*<small>You cannot post comments before you make and choose your character</small>.';
            return $cont;
        }
        
        function return_languages()
        {
            $this->cms = $this->load->database('default', TRUE);  
            $query = $this->cms->get('languages');
            $i = 0;
            $cont = array();
            foreach ($query->result_array() as $row)
            {
                $i++;
                $cont[$i]['language'] = $row['language'];
                $cont[$i]['language_string'] = $row['language_string'];
                $cont[$i]['countries'] = explode(',', $row['countries']);                    
            }
            
            return $cont;
        }
		
		function check_account_banned($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->where('id', $id);
            $this->auth->where('active', '1');

            $query = $this->auth->get('account_banned', 1);

            return $this->auth->affected_rows();
        }
		
		function check_account_access($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->where('id', $id);
            $this->auth->where('gmlevel > ', 5);

            $query = $this->auth->get('account_access', 1);

            return $this->auth->affected_rows();
        }
        
        function show_realm_characters($char_db='', $id='')
        {
            if(empty($char_db) || empty($id))
                return;
            
            $this->load->database();
            $config['hostname'] = $this->db->hostname;
            $config['username'] = $this->db->username;
            $config['password'] = $this->db->password;
            $config['database'] = $char_db;
            $config['dbdriver'] = "mysql";
            $config['dbprefix'] = "";
            $config['pconnect'] = FALSE;
            $config['db_debug'] = TRUE;
            $config['cache_on'] = FALSE;
            $config['cachedir'] = "";
            $config['char_set'] = "utf8";
            $config['dbcollat'] = "utf8_general_ci";

            $this->characters = $this->load->database($config, TRUE);  

            $this->characters->select('name');
            $this->characters->where('account', $id);
            $query = $this->characters->get('characters');
            $cont = '';
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $cont .= '<option value="'.$row['name'].'">'.$row['name'].'</option>';
                }
            }
            return $cont;
        }
}
?>