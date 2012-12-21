<?php
class Acp_ajax extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->auth = $this->load->database('auth', TRUE);  
        }
        
        function select_world_db()
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('world_db');
            $query = $this->auth->get('realmlist', '1');
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    return $row['world_db'];
                }
            }
            else
                return 'world';
        }
        
        function return_items($str)
        {
            $this->load->database();
            $config['hostname'] = $this->db->hostname;
            $config['username'] = $this->db->username;
            $config['password'] = $this->db->password;
            $config['database'] = $this->select_world_db();
            $config['dbdriver'] = "mysql";
            $config['dbprefix'] = "";
            $config['pconnect'] = FALSE;
            $config['db_debug'] = TRUE;
            $config['cache_on'] = FALSE;
            $config['cachedir'] = "";
            $config['char_set'] = "utf8";
            $config['dbcollat'] = "utf8_general_ci";

            $this->world = $this->load->database($config, TRUE);  

            $this->world->select('entry, name, quality');
            $this->world->like('name', $str);
            $query = $this->world->get('item_template', '10');

            $cont = array();
            $br = 0;
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $br++;
                    $cont[$br]['name'] = $row['name'];
                    $cont[$br]['entry'] = $row['entry'];
                    $cont[$br]['quality'] = $row['quality'];
                }
            }
            
            return $cont;
        }
        
        function limit_string($string, $charlimit)
        {
            if(strlen($string)>$charlimit)
            {
                if(substr($string,$charlimit-1,1) != ' ')
                {
                    $string = substr($string,'0',$charlimit);
                    $array = explode(' ',$string);
                    array_pop($array);
                    $new_string = implode(' ',$array);

                    return $new_string.' ...';
                }
                else
                {   
                    return substr($string,'0',$charlimit-1).' ...';
                }
            }
            else
                return $string;
        } 
        
        function test_serv($ip, $port)
        {
            $socket = @fsockopen($ip, $port, $ERROR_NO, $ERROR_STR,(float)0.5);
            if($socket)
            {
                @fclose($socket);
                return true;
            } 
            else 
                return false;
        }
        
        function select_max_players($realm_info)
        {
			if($realm_info['core'] == 'trinity')
			{
				$this->auth = $this->load->database('auth', TRUE);
				$this->auth->select_max('maxplayers');
				$this->auth->where('realmid', $realm_info['id']);
				$query = $this->auth->get('uptime', '1');
			}
			elseif($realm_info['core'] == 'oregon')
			{
				$this->load->database();
				$config['hostname'] = $this->db->hostname;
				$config['username'] = $this->db->username;
				$config['password'] = $this->db->password;
				$config['database'] = $realm_info['world_db'];
				$config['dbdriver'] = "mysql";
				$config['dbprefix'] = "";
				$config['pconnect'] = FALSE;
				$config['db_debug'] = TRUE;
				$config['cache_on'] = FALSE;
				$config['cachedir'] = "";
				$config['char_set'] = "utf8";
				$config['dbcollat'] = "utf8_general_ci";

				$this->world = $this->load->database($config, TRUE);  
				$this->world->select_max('maxplayers');
				$query = $this->world->get('uptime', '1');
			}
			else
				return;
				
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $max = $row['maxplayers'];
                }
            }
            if($max)
                return $max;
            else
                return '0';
        }

        function show_uptime($realm_info, $online=TRUE)
        {
            if($online!=TRUE)
                return sprintf('%sd %sh %sm', 0, 0, 0);
				
			$uptime = sprintf('%sd %sh %sm', 0, 0, 0);			
            
            if($realm_info['core'] == 'trinity')
			{
				$this->auth = $this->load->database('auth', TRUE);  
				$this->auth->select('uptime');
				$this->auth->where('realmid', $realm_info['id']);
				$this->auth->order_by('starttime', 'desc');
				$query = $this->auth->get('uptime', '1');
			}
			elseif($realm_info['core'] == 'oregon')
			{	
				$this->load->database();
				$config['hostname'] = $this->db->hostname;
				$config['username'] = $this->db->username;
				$config['password'] = $this->db->password;
				$config['database'] = $realm_info['world_db'];
				$config['dbdriver'] = "mysql";
				$config['dbprefix'] = "";
				$config['pconnect'] = FALSE;
				$config['db_debug'] = TRUE;
				$config['cache_on'] = FALSE;
				$config['cachedir'] = "";
				$config['char_set'] = "utf8";
				$config['dbcollat'] = "utf8_general_ci";

				$this->world = $this->load->database($config, TRUE);  
				$this->world->select('uptime');
				$this->world->order_by('starttime', 'desc');
				$query = $this->world->get('uptime', '1');
			}
			else
				return;
				
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $days = floor($row['uptime'] / 86400);
                    $hours = floor($row['uptime'] % 86400 / 3600);
                    $minutes = floor($row['uptime'] % 3600 / 60);
                    $seconds = $row['uptime'] % 60;
                    $uptime = sprintf('%sd %sh %sm', $days, $hours, $minutes);
                }
            }
            return $uptime;
        }

        function select_online_characters($characters_db, $faction='')
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

            $this->characters->select('guid');
            $this->characters->where('online', '1');
            if($faction!='')
                $this->characters->where_in('race', $faction);
            $query = $this->characters->get('characters');

            return $query->num_rows();
        }

        function get_percent($faction_players='0', $total_players='0') 
        {
            if($total_players!='' && $total_players!='0')
            {
                $count1 = $faction_players / $total_players;
                $count2 = $count1 * 100;
                $count = round($count2);

                return $count;
            }
        }
        
        function check_gm_online($realm_info)
        {
            $this->auth = $this->load->database('auth', TRUE);
            $this->auth->select('id');
            $this->auth->where('gmlevel >', '1');
            $query = $this->auth->get('account_access');
            
            $gms = $query->result_array();
            
            $this->load->database();
            $config['hostname'] = $this->db->hostname;
            $config['username'] = $this->db->username;
            $config['password'] = $this->db->password;
            $config['database'] = $realm_info['char_db'];
            $config['dbdriver'] = "mysql";
            $config['dbprefix'] = "";
            $config['pconnect'] = FALSE;
            $config['db_debug'] = TRUE;
            $config['cache_on'] = FALSE;
            $config['cachedir'] = "";
            $config['char_set'] = "utf8";
            $config['dbcollat'] = "utf8_general_ci";

            $this->characters = $this->load->database($config, TRUE);  
            $br = 0;
            foreach ($gms as $gm)
            {
                $this->characters->select('guid');
                $this->characters->where('account', $gm['id']);
                $this->characters->where('online', '1');
                $query = $this->characters->get('characters');
                if($query->num_rows()>0)
                    $br++;
            }
            
            return $br;
        }
        
	function show_realms_status()
        {
            $this->auth->select('id, name, address, port, icon, char_db, world_db, core, p_limit');
            $query = $this->auth->get('realmlist');

            $cont = '';
            if($query->num_rows() > 0)
            {
                $icon = array(
                    '0' => 'Normal',
                    '4' => 'Normal',
                    '1' => '<font color="red">PVP</font>',
                    '6' => '<font color="green">RP</font>',
                    '8' => 'RP PVP'
                );
                $alliance = array("1","3","4","7","11");
                $horde = array("2","5","6","8","10");
                $i='0';
                foreach ($query->result_array() as $row)
                {
                    $i++;
                    if($this->test_serv($row['address'], $row['port'])==TRUE)
                    {
                        $online_players = $this->select_online_characters($row['char_db']);
                        $online_players_alliance = $this->select_online_characters($row['char_db'], $alliance);
                        $online_players_horde = $this->select_online_characters($row['char_db'], $horde);
                        $percents_allyance = $this->get_percent($online_players_alliance, $online_players);
                        $percents_horde = $this->get_percent($online_players_horde, $online_players);
                        if($percents_allyance==0 && $percents_horde!=100) $percents_allyance='50';
                        if($percents_horde==0 && $percents_allyance!=100) $percents_horde='50';
                        if($percents_allyance + $percents_horde > 100)
                        {
                            if($percents_allyance > $percents_horde)
                                $percents_allyance--;
                            elseif($percents_horde > $percents_allyance)
                                $percents_horde--;
                        }
                        
                        $percents_allyance_string =  $percents_allyance.'%';
                        if($percents_allyance==0)
                            $percents_allyance_string = '';
                        $percents_horde_string = $percents_horde.'%';
                        if($percents_horde==0)
                            $percents_horde_string = '';
                        
                        $cont[$i]['realm_status'] = "Online";
                        $cont[$i]['realm_id'] = $row['id'];
                        $cont[$i]['realm_name'] = $this->limit_string($row['name'], '23');
                        $cont[$i]['realm_icon'] = $icon[$row['icon']];
                        $cont[$i]['realm_uptime'] = $this->show_uptime($row);
                        $cont[$i]['realm_max_players'] = $this->select_max_players($row);
                        $cont[$i]['realm_online_players'] = $online_players;
                        $cont[$i]['realm_online_allyance'] = $online_players_alliance;
                        $cont[$i]['realm_online_horde'] = $online_players_horde;
                        $cont[$i]['realm_gm_online'] = $this->check_gm_online($row);
                        $cont[$i]['realm_status_bar'][$i]['percents_allyance'] = $percents_allyance;
                        $cont[$i]['realm_status_bar'][$i]['percents_allyance_string'] = $percents_allyance_string;
                        $cont[$i]['realm_status_bar'][$i]['percents_horde'] = $percents_horde;  
                        $cont[$i]['realm_status_bar'][$i]['percents_horde_string'] = $percents_horde_string;  
                        $cont[$i]['realm_total_online_percent'] = ($online_players/$row['p_limit']) * 100;
                        $cont[$i]['realm_player_limit'] = $row['p_limit'];
                    }
                    else
                    {
                        $cont[$i]['realm_status'] = "Offline";
                        $cont[$i]['realm_id'] = $row['id'];
                        $cont[$i]['realm_name'] = $this->limit_string($row['name'], '23');
                        $cont[$i]['realm_icon'] = $icon[$row['icon']];
                        $cont[$i]['realm_uptime'] = $this->show_uptime($row,FALSE);
                        $cont[$i]['realm_max_players'] = $this->select_max_players($row);
                        $cont[$i]['realm_online_players'] = 0;
                        $cont[$i]['realm_online_allyance'] = 0;
                        $cont[$i]['realm_online_horde'] = 0;
                        $cont[$i]['realm_gm_online'] = 0;
                        $cont[$i]['realm_status_bar'] = array();         
                        $cont[$i]['realm_total_online_percent'] = 0;
                        $cont[$i]['realm_player_limit'] = $row['p_limit'];
                    }
                }
            }
            return $cont;        
        }
        
        function check_username($username)
        {
            $this->auth->where('username', $username);
            $query = $this->auth->get('account', '1');

            return $this->auth->affected_rows();
        }

        function check_email($email)
        {
            $this->auth->where('email', $email);
            $query = $this->auth->get('account', '1');

            return $this->auth->affected_rows();
        }
}
?>