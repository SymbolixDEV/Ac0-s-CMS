<?php
class Tools_model extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->auth = $this->load->database('auth', TRUE);  
        } 
        
        function select_realm_char_db($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('char_db');
            $this->auth->where('id', $id);
            $query = $this->auth->get('realmlist');
            if($query->num_rows()>'0')
            {
                foreach($query->result_array() as $row)
                {
                    return $row['char_db'];
                }
            }
        }
		
		function select_realm_core($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('core');
            $this->auth->where('id', $id);
            $query = $this->auth->get('realmlist');
            if($query->num_rows()>'0')
            {
                foreach($query->result_array() as $row)
                {
                    return $row['core'];
                }
            }
        }
        
        function show_realms($id, $destination)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id, name, char_db');
            $query = $this->auth->get('realmlist');
            $cont = '';
            if($query->num_rows()>1)
            {
                $cont .= '<center><strong>Select Realm:</strong><br /><br /><strong><ol style="list-style:none; display:inline;">';
                $i = 0;
                foreach($query->result_array() as $row)
                {
                    $i++;
                    if($id!=$row['id'])
                        $cont .= '<a href="'.base_url($destination.$row['id']).'" onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, \'Change to '.$row['name'].' realm\');" ><li style="list-style:none; display:inline;">'.$row['name'].'</li></a>';
                    else
                        $cont .= '&nbsp;<li class="cool_button" style="list-style:none; display:inline;">'.$row['name'].'</li>&nbsp;';
                }
                $cont .= '</ol></strong></center><br />';
            }
            return $cont;
        }
        
        function show_top_killers($characters_db)
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

            $this->characters->select('guid, name, level, class, race, totalKills, todayKills, gender');
			$this->characters->order_by('totalKills', 'desc'); 
            $query = $this->characters->get('characters', '30');
            $cont = array();
            if($query->num_rows()>'0')
            {
                $i = 0;
                foreach($query->result_array() as $row)
                {
                   $i++;
                   $cont[$i]['player_number'] = $i;
                   $cont[$i]['player_guid'] = $row['guid'];
                   $cont[$i]['player_name'] = $row['name'];
                   $cont[$i]['player_total_kills'] = $row['totalKills'];
                   $cont[$i]['player_level'] = $row['level'];
                   $cont[$i]['player_icons'] = '<img src="'.base_url('content/img/icon/class/'.$row['class']).'.gif" title="Class" />&nbsp;<img src="'.base_url('content/img/icon/race/'.$row['race'].'-'.$row['gender']).'.gif" title="Race" />';
                   $cont[$i]['player_today_kills'] = $row['todayKills'];
                }
            }
            return $cont;
        }
		
		function _get_account_by_id($id)
		{
			$this->auth = $this->load->database('auth', TRUE);  
			$this->auth->select('username');
            $this->auth->where('id', $id);
            $query = $this->auth->get('account', '1');
			if($query->num_rows()>'0')
            {
                foreach($query->result_array() as $row)
                {
					return $row['username'];
				}
			}
			else
				return 'Deleted';
		}
		
		function count_all_bans($type, $realm)
		{
			switch($type)
			{
				case 0:
					$this->auth = $this->load->database('auth', TRUE);  
					$this->auth->select('id');
					$this->auth->where('active', '1');
					$this->auth->from('account_banned');
					break;
				case 1:
					$this->auth = $this->load->database('auth', TRUE);  
					$this->auth->select('ip');
					$this->auth->from('ip_banned');
					break;
				case 2:
					if($realm == '') return 0;
					$characters_db = $this->select_realm_char_db($realm);
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

					$this->auth = $this->load->database($config, TRUE); 
					$this->auth->select('guid');
					$this->auth->where('active', '1');
					$this->auth->from('character_banned');					
					break;
				default : 
					return 0;
			}
			
			return $this->auth->count_all_results();
		}
		
		function show_character_bans($start, $limit, $characters_db)
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
			$this->characters->select('guid, unbandate, banreason');
            $this->characters->where('active', '1');
            $query = $this->characters->get('character_banned', $limit, $start);
            $cont = array();
            if($query->num_rows()>'0')
            {
                $i = 0;
                foreach($query->result_array() as $row)
                {
                   $i++;
                   $cont[$i]['baned_name'] = $this->get_player_name($characters_db, $row['guid']);
                   $cont[$i]['baned_unbandate'] = date( "dS F, Y @ h:ia" , $row['unbandate']);
                   $cont[$i]['baned_reason'] = $row['banreason'];
                }
            }
            return $cont;
        }
		
		function show_account_bans($start, $limit)
        {
            $this->auth = $this->load->database('auth', TRUE);  
			$this->auth->select('id, unbandate, banreason');
            $this->auth->where('active', '1');
            $query = $this->auth->get('account_banned', $limit, $start);
            $cont = array();
            if($query->num_rows()>'0')
            {
                $i = 0;
                foreach($query->result_array() as $row)
                {
                   $i++;
                   $cont[$i]['baned_name'] = $this->_get_account_by_id($row['id']);
                   $cont[$i]['baned_unbandate'] = date( "dS F, Y @ h:ia" , $row['unbandate']);
                   $cont[$i]['baned_reason'] = $row['banreason'];
                }
            }
            return $cont;
        }
		
		function show_ip_bans($start, $limit)
        {
            $this->auth = $this->load->database('auth', TRUE);  
			$this->auth->select('ip, unbandate, banreason');
            $query = $this->auth->get('ip_banned', $limit, $start);
            $cont = array();
            if($query->num_rows()>'0')
            {
                $i = 0;
                foreach($query->result_array() as $row)
                {
                   $i++;
                   $cont[$i]['baned_name'] = $row['ip'];
                   $cont[$i]['baned_unbandate'] = date( "dS F, Y @ h:ia" , $row['unbandate']);
                   $cont[$i]['baned_reason'] = $row['banreason'];
                }
            }
            return $cont;
        }
		
		function get_player_name($characters_db, $guid)
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

            $this->characters->select('name');
            $this->characters->where('name !=', '');
			$this->characters->where('guid', $guid);
            $query = $this->characters->get('characters', '1');
            $cont = array();
            if($query->num_rows()>'0')
            {
                foreach($query->result_array() as $row)
                {
                   return $row['name'];
                }
            }
			else 
				return FALSE;
        }
		
		function get_char_icon($characters_db, $guid)
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

            $this->characters->select('guid, class, name');
            $this->characters->where('name !=', '');
			$this->characters->where('guid', $guid);
            $query = $this->characters->get('characters', '1');
            if($query->num_rows()>'0')
                foreach($query->result_array() as $row)
                   return '<img src="'.base_url('content/img/icon/class/'.$row['class']).'.gif" title="'.$row['name'].'" />';
			else 
				return FALSE;
        }
		
		
		
		function get_arena_combo($characters_db, $arena_id)
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
			$this->characters->where('arenaTeamId', $arena_id);
            $query = $this->characters->get('arena_team_member');
            $cont = array();
            if($query->num_rows()>'0')
            {
				$i = 0;
                foreach($query->result_array() as $row)
                {
					$i++;
					$cont[$i]['icon'] = $this->get_char_icon($characters_db, $row['guid']);
					$cont[$i]['guid'] = $row['guid'];
                }
            }
			return $cont;
        }
		
		function get_character_mmr($characters_db, $guid)
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

            $this->characters->select('matchMakerRating');
			$this->characters->where('guid', $guid);
            $query = $this->characters->get('character_arena_stats');
            if($query->num_rows()>'0')
            {
                foreach($query->result_array() as $row)
                {
					return $row['matchMakerRating'];
                }
            }
			else
				return 0;
        }
		
		function get_mmr($characters_db, $arena_id)
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
			$this->characters->where('arenaTeamId', $arena_id);
            $query = $this->characters->get('arena_team_member');
            if($query->num_rows()>'0')
            {
				$max = 0;
                foreach($query->result_array() as $row)
                {
					$char_mmr = $this->get_character_mmr($characters_db, $row['guid']);
					if($max<$char_mmr)
						$max = $char_mmr;
                }
            }
			return $max;
        }
		
		function show_top_arenas($characters_db, $core, $type)
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

			if($core == 'trinity')
				$this->characters->select('arenaTeamId, name, seasonGames, seasonWins, captainGuid, rating');
			elseif($core == 'oregon')
			{
				$this->characters->select('arena_team.arenaTeamId as arenaTeamId, arena_team.name as name, arena_team_stats.games as seasonGames, arena_team_stats.wins as seasonWins, arena_team.captainguid as captainGuid, arena_team_stats.rating as rating');
				$this->characters->join('arena_team_stats', 'arena_team_stats.arenateamid = arena_team.arenaTeamId');
			}
			$this->characters->where('name !=', '');
			$this->characters->where('type', $type);
			$this->characters->order_by('rating', 'desc'); 
            $query = $this->characters->get('arena_team', '30');
            $cont = array();
            if($query->num_rows()>'0')
            {
                $i = 0;
                foreach($query->result_array() as $row)
                {
                   $i++;
                   $cont[$i]['arena_number'] = $i;
                   $cont[$i]['arena_name'] = $row['name'];
                   $cont[$i]['arena_captain_name'] = ($this->get_player_name($characters_db, $row['captainGuid'])) ? $this->get_player_name($characters_db, $row['captainGuid']) : 'Deleted';
				   $cont[$i]['arena_captain_guid'] = $row['captainGuid'];
                   $cont[$i]['arena_rating'] = $row['rating'];
				   $cont[$i]['arena_combo'] = $this->get_arena_combo($characters_db, $row['arenaTeamId']);
				   if($core == 'trinity')
					$cont[$i]['arena_mmr'] = $this->get_mmr($characters_db, $row['arenaTeamId']);
				   $cont[$i]['arena_wins'] = $row['seasonWins'];
				   $cont[$i]['arena_loses'] = $row['seasonGames'] - $row['seasonWins'];
                }
            }
            return $cont;
        }
}
?>