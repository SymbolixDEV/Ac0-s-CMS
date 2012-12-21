<?php
class Ajax_model extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->auth = $this->load->database('auth', TRUE);  
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
                return sprintf('%s Days %s Hours %s Minutes', 0, 0, 0);
				
			$uptime = sprintf('%s Days %s Hours %s Minutes', 0, 0, 0);			
            
            if($realm_info['core'] == 'trinity')
			{
				$this->auth = $this->load->database('auth', TRUE);  
				$this->auth->select('starttime');
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
				$this->world->select('starttime');
				$this->world->order_by('starttime', 'desc');
				$query = $this->world->get('uptime', '1');
			}
			else
				return;
				
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
					$uptime = time() - $row['starttime'];
                    $days = floor($uptime / 86400);
                    $hours = floor($uptime % 86400 / 3600);
                    $minutes = floor($uptime % 3600 / 60);
                    $seconds = $uptime % 60;
                    $uptime = sprintf('%s Days %s Hours %s Minutes', $days, $hours, $minutes);
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
		
		function select_total_characters($characters_db)
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
            $this->characters->where('name !=', '');
            $query = $this->characters->get('characters');

            return $query->num_rows();
        }
		
		function select_total_guilds($characters_db)
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

            $this->characters->select('guildid');
            $this->characters->where('name !=', '');
            $query = $this->characters->get('guild');

            return $query->num_rows();
        }
		
		function select_total_teams($characters_db)
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

            $this->characters->select('arenaTeamId');
            $this->characters->where('name !=', '');
            $query = $this->characters->get('arena_team');

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
        
		function show_next_flush($realm_info)
        {
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
			
			$next_flush = 0;
            
            if($realm_info['core'] == 'trinity')
			{ 
				$this->characters->select('value');
				$this->characters->where('comment', 'NextArenaPointDistributionTime');
				$query = $this->characters->get('worldstates', '1');
				if($query->num_rows() > 0)
				{
					$row = $query->row_array();
					$next_flush = $row['value'];
				}
			}
			elseif($realm_info['core'] == 'oregon')
			{	
				$this->characters->select('NextArenaPointDistributionTime');
				$query = $this->characters->get('saved_variables', '1');
				if($query->num_rows() > 0)
				{
					$row = $query->row_array();
					$next_flush = $row['NextArenaPointDistributionTime'];
				}
			}
			else
				return "Warning... Can't get next flush time.";
			
			return date('d M H:i', $next_flush);
        }
		
		function show_realms_status()
        {
			$this->auth = $this->load->database('auth', TRUE);
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
                        $cont[$i]['realm_icon'] = $icon[$row['icon']];
                        $cont[$i]['realm_uptime'] = $this->show_uptime($row);
                        $cont[$i]['realm_max_players'] = $this->select_max_players($row);
                        $cont[$i]['realm_online_players'] = $online_players;
                        $cont[$i]['realm_online_allyance'] = $online_players_alliance;
                        $cont[$i]['realm_online_horde'] = $online_players_horde;
                        $cont[$i]['realm_status_bar'][$i]['percents_allyance'] = $percents_allyance;
                        $cont[$i]['realm_status_bar'][$i]['percents_allyance_string'] = $percents_allyance_string;
                        $cont[$i]['realm_status_bar'][$i]['percents_horde'] = $percents_horde;  
                        $cont[$i]['realm_status_bar'][$i]['percents_horde_string'] = $percents_horde_string;  
                        $cont[$i]['realm_total_online_percent'] = ($online_players/$row['p_limit']) * 100;
                        $cont[$i]['realm_player_limit'] = $row['p_limit'];
						$cont[$i]['realm_name'] = $this->limit_string($row['name'], '23');
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
                        $cont[$i]['realm_status_bar'] = array();         
                        $cont[$i]['realm_total_online_percent'] = 0;
                        $cont[$i]['realm_player_limit'] = $row['p_limit'];
                    }
					$cont[$i]['realm_next_flush'] = $this->show_next_flush($row);
					$cont[$i]['realm_total_chaterters'] = $this->select_total_characters($row['char_db']);
					$cont[$i]['realm_total_guilds'] = $this->select_total_guilds($row['char_db']);
					$cont[$i]['realm_total_teams'] = $this->select_total_teams($row['char_db']);
                }
            }
            return $cont;        
        }
		
		function show_realm_info($id)
        {
			$this->auth = $this->load->database('auth', TRUE);
            $this->auth->select('id, name, address, port, icon, char_db, world_db, core, p_limit');
			$this->auth->where('id', $id);
            $query = $this->auth->get('realmlist', '1');

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
                foreach ($query->result_array() as $row)
                {
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
                        
                        $cont[$id]['realm_status'] = "Online";
                        $cont[$id]['realm_id'] = $row['id'];
                        $cont[$id]['realm_icon'] = $icon[$row['icon']];
                        $cont[$id]['realm_uptime'] = $this->show_uptime($row);
                        $cont[$id]['realm_max_players'] = $this->select_max_players($row);
                        $cont[$id]['realm_online_players'] = $online_players;
                        $cont[$id]['realm_online_allyance'] = $online_players_alliance;
                        $cont[$id]['realm_online_horde'] = $online_players_horde;
                        $cont[$id]['realm_status_bar'][$id]['percents_allyance'] = $percents_allyance;
                        $cont[$id]['realm_status_bar'][$id]['percents_allyance_string'] = $percents_allyance_string;
                        $cont[$id]['realm_status_bar'][$id]['percents_horde'] = $percents_horde;  
                        $cont[$id]['realm_status_bar'][$id]['percents_horde_string'] = $percents_horde_string;  
                        $cont[$id]['realm_total_online_percent'] = ($online_players/$row['p_limit']) * 100;
                        $cont[$id]['realm_player_limit'] = $row['p_limit'];
						$cont[$id]['realm_name'] = $this->limit_string($row['name'], '23');
                    }
                    else
                    {
                        $cont[$id]['realm_status'] = "Offline";
                        $cont[$id]['realm_id'] = $row['id'];
                        $cont[$id]['realm_name'] = $this->limit_string($row['name'], '23');
                        $cont[$id]['realm_icon'] = $icon[$row['icon']];
                        $cont[$id]['realm_uptime'] = $this->show_uptime($row,FALSE);
                        $cont[$id]['realm_max_players'] = $this->select_max_players($row);
                        $cont[$id]['realm_online_players'] = 0;
                        $cont[$id]['realm_online_allyance'] = 0;
                        $cont[$id]['realm_online_horde'] = 0;
                        $cont[$id]['realm_status_bar'] = array();         
                        $cont[$id]['realm_total_online_percent'] = 0;
                        $cont[$id]['realm_player_limit'] = $row['p_limit'];
                    }
                }
            }
            return $cont;        
        }
        
        function check_username($username)
        {
			$this->auth = $this->load->database('auth', TRUE);
            $this->auth->where('username', $username);
            $query = $this->auth->get('account', '1');

            return $this->auth->affected_rows();
        }

        function check_email($email)
        {
			$this->auth = $this->load->database('auth', TRUE);
            $this->auth->where('email', $email);
            $query = $this->auth->get('account', '1');

            return $this->auth->affected_rows();
        }
}
?>