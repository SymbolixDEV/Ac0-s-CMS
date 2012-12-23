<?php
class Profile_model extends CI_Model
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
		
		function check_auth_account_exist($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id');
            $this->auth->where('id', $id);
            $query = $this->auth->get('account', '1');
            
            return $this->auth->affected_rows();
        }
		
		function check_cms_account_exist($id)
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->select('id');
            $this->cms->where('id', $id);
            $query = $this->cms->get('account_addition', '1');
            
            return $this->cms->affected_rows();
        }
		
		public function return_all_characters($id) 
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('name, id, char_db');
            $query = $this->auth->get('realmlist');
            $data[NULL] = 'Choose Character';
            $br = 0;
			$data[$this->session->userdata('username')] = $this->session->userdata('username');
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    foreach($this->return_characters($id, $row['char_db']) as $character)
                    {
                        $data[$character['name']] = $character['name'];
                    }
                }
            }
            return $data;
        }
		
		function return_characters($id, $characters_db)
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
            $this->characters->where('account', $id);
            $query = $this->characters->get('characters');
           
            return $query->result_array();
        }
		
		function set_new_nickname($id, $nick)
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->where('id', $id);
            $data = array(
                'username' => $nick
            );
            $query = $this->cms->update('account_addition', $data);

            return $this->cms->affected_rows();
        }
		
		function set_new_location($id, $location)
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->where('id', $id);
            $data = array(
                'location' => $location
            );
            $query = $this->cms->update('account_addition', $data);

            return $this->cms->affected_rows();
        }
		
		function set_new_gender($id, $gender)
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->where('id', $id);
            $data = array(
                'gender' => $gender
            );
            $query = $this->cms->update('account_addition', $data);

            return $this->cms->affected_rows();
        }
		
		function check_account($username, $password)
        {
            $password = $this->sha_password($username, $password);
            $this->auth->where('username', $username);
            $this->auth->where('sha_pass_hash', $password);
            $query = $this->auth->get('account');

            return $this->auth->affected_rows();
        }
		
		function set_new_password($username, $password)
        {
            $password = $this->sha_password($username, $password);
            $this->auth->where('username', $username);
            $data = array(
                'sha_pass_hash' => $password
            );
            $query = $this->auth->update('account', $data);

            return $this->auth->affected_rows();
        }
		
		function set_new_expansion($id, $expansion)
        {
            $this->auth->where('id', $id);
            $data = array(
                'expansion' => $expansion
            );
            $query = $this->auth->update('account', $data);

            return $this->auth->affected_rows();
        }
		
		function show_purchase($realm_id, $type)
        {
            $cont = '';
            $data = array ('realm_id' => $realm_id, 'type' => $type, 'return_link' => current_url());
            $cont .= form_open('index.php/profile/purchase', '', $data);
            $realm_info = $this->get_realm($realm_id);
            $cont .= $this->session->flashdata('invalid_option');
            $cont .= $this->show_realms($realm_id, 'index.php/profile/'.$type.'_shop/');
            $cont .= $this->return_rewards($realm_id, $type);
            $attributes_submit = array('name' => 'purchase_submit', 'id' => 'purchase_submit', 'class' => '', 'value' => "Purchase");
            $cont .= '<br />'.$this->return_character_menu($realm_info['char_db']).' &nbsp;<span style="padding-top:2px;">'.form_submit($attributes_submit).'</span>';
            $cont .= form_close();
            
            return $cont;
        }
		
		function get_realm($realm_id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('name, ra, ra_port, soap, soap_port, char_db, world_db, id, unstuck_price, teleport_price, unstuck, teleport, changes, change_faction_price, change_race_price, change_appearance_price, 3d_char_preview');
            $query = $this->auth->get('realmlist', '1');
            if($query->num_rows() > 0)
            {
                return $query->row_array();
            }
        }
		
		function return_rewards($realm_id, $type, $id='')
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->where('realm', $realm_id);
            if($id!='')
                $this->cms->where('entry', $id);
            $query = $this->cms->get($type.'_rewards');
            $cont = '';
            if($query->num_rows() > 0)
            {
                if($id!='')
                    return $query->row_array();
                
                $cont .= '<table style="width: 100%;">';
                $cont .= validation_errors("<tr><td colspan='4'><div class='warning'><span class='ico_warning'>", "</span></div></td></tr>");
                $cont .= $this->session->flashdata('show_result');
                $cont .= '<tr>';
                $cont .= '<td>Name</td>';
                $cont .= '<td>Description</td>';
                $cont .= '<td></td>';
                $cont .= '<td>Cost</td>';
                $cont .= '<td></td>';
                $cont .= '</tr><tr height="10px"></tr>';

                $text_type = array('vote' => 'vp', 'donate' => 'dp');

                foreach ($query->result_array() as $row)
                {
                    $link = ($row['custom']==0) ? 'href="http://wotlk.openwow.com/?item='.$row['entry'].'" ' : '';
                    $cont .= '<tr>';
                    $cont .= '<td><a '.$link.' class="q'.$row['quality'].'">'.$row['name'].'</a>';
                    $cont .= '<td>'.$row['description'].'</td>';
                    $cont .= '<td>x'.$row['quantity'].'</td>';
                    $cont .= '<td>'.$row['points'].' '.$text_type[$type].'</td>';
                    $cont .= '<td class="acenter">'.form_radio('item_entry', $row['entry'], FALSE).'</td>';
                    $cont .= '</tr>';
                }
                $cont .= '</table>';
            }
            else
                $cont .= '<center><b>No rewards</b></center>';
            return $cont;
        }
		
		function ra_access($ra_port, $command)
        {
            $telnet = fsockopen($this->config->item('server_ip'), $ra_port, $error, $error_str, 30);
            if($telnet)
            {
                $ra_user = $this->config->item('admin_user');
                $ra_pass = $this->config->item('admin_pass');

                fputs($telnet, $ra_user."\n");
                sleep(2);
                fputs($telnet, $ra_pass."\n");
                sleep(2);

                fputs($telnet,  $command."\n");
                sleep(2);
                fclose($telnet);
                return 1;
            }
            else
            {
                return 0;
            }
        }
        
        function soap_access($soap_port, $command)
        {
            $soap_user = $this->config->item('admin_user');
            $soap_pass = $this->config->item('admin_pass');
            
            $connection = new SoapClient(NULL, array("location" => "http://localhost:$soap_port/", "uri" => "urn:TC", "style" => SOAP_RPC, "login" => $soap_user, "password" => $soap_pass));
            
            try
            {
                $result = $connection->executeCommand(new SoapParam($command, "command"));
            }
            
            catch(Exception $e)
            {
                return 0;
            }
            
            return 1;
        }
		
		function update_points($id, $type, $points)
        {
            if($type=='')
                return;
            $this->cms = $this->load->database('default', TRUE);  
            $data = array(
                $type => $points
            );
            $this->cms->where('id', $id);
            $query = $this->cms->update('account_addition', $data);
        }
		
		function get_username($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id, username, email, joindate, last_ip, last_login, expansion');
            $this->auth->where('id', $id);
            $query = $this->auth->get('account', '1');

            return $query->row_array();
        }
		
		function show_characters($id, $own=FALSE)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            if($own)
                $this->auth->select('name, char_db, id, unstuck_price, teleport_price, unstuck, teleport, changes, change_faction_price, change_race_price, change_appearance_price');
            else 
                $this->auth->select('name, id, char_db');
            $query = $this->auth->get('realmlist');
            $cont = '';
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $cont .= 'For realm <strong>'.$row['name'].'</strong><br /><br />';
                    $cont .= $this->select_characters($id, $row['char_db'], $row['id']);
                }
            }
            return $cont;
        }
		
		function select_characters($id, $characters_db, $realm_id)
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

            $this->characters->select('guid, race, gender, class, name, money, level');
            $this->characters->where('account', $id);
            $query = $this->characters->get('characters');

            $cont = '<table width="100%">';
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $gold=substr($row['money'], 0, -4); if ($gold=='') {$gold="0";}
                    $silver=substr($row['money'], 0, -2); 
                    $silver2=substr($silver, -2); if ($silver2=='') {$silver2="0";}
                    $copper=substr($row['money'], -2); if ($copper=='') {$copper="0";}

                    $side = ($row['race']==1 || $row['race']==3 || $row['race']==4 || $row['race']==7 || $row['race']==11 || $row['race']==22) ? 0 : 1;
                    
                    $cont .= '</td></tr>
                        <tr>
                            <td rowspan="4" width="86px"><img src="'.base_url('content/img/icon/avatars/'.$row['race'].'-'.$row['gender'].'.jpg').'"></td>
                            <td><strong><a href="'.base_url('index.php/character/index/'.$row['guid'].'/'.$realm_id).'">'.$row['name'].'</a></strong> - Level: '.$row['level'].'</td>
                        </tr>
                        <tr>
                            <td>Money: '.$gold.'<img src="'.base_url('content/img/icon/pvpranks/money_gold.gif').'" width="10px" height="10px"> '.$silver2.' <img src="'.base_url('content/img/icon/pvpranks/money_silver.gif').'" width="10px" height="10px"> '.$copper.' <img src="'.base_url('content/img/icon/pvpranks/money_copper.gif').'" width="10px" height="10px"></td>
                        </tr>
                        <tr>
                            <td><img src="'.base_url('content/img/icon/class/'.$row['class'].'.gif').'" title="Class" />&nbsp;&nbsp;<img src="'.base_url('content/img/icon/race/'.$row['race'].'-'.$row['gender'].'.gif').'"  title="Race" />&nbsp;&nbsp;<img src="'.base_url('content/img/icon/pvpranks/rank_default_'.$side.'.gif').'"  title="Faction" /></td>
                        </tr>
                        <tr style="height: 10px"><td colspan="2"><hr></hr></td></tr>';
                }
            }
            else
                $cont .= '<tr><td><span class="acenter">*<small>No players for that realm.</small></span></td></tr><tr style="height: 10px"></tr>';
            $cont .= '</table>';
            return $cont;
        }
		
		function check_account_bann($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->where('id', $id);
            $this->auth->where('active', '1');

            $query = $this->auth->get('account_banned', 1);

            return $this->auth->affected_rows();
        }
		
		function get_account_access($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->where('id', $id);

            $query = $this->auth->get('account_access', 1);
			
			$row = $query->row_array();
			
			return ($query->num_rows() > 0) ? $row['gmlevel'] : 0;
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
       
        function return_character_menu($characters_db)
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
            $this->characters->where('account', $this->session->userdata('id'));
            $query = $this->characters->get('characters');

            $cont = '';
            $options = array(NULL  => "Select Character");
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $options[$row['name']] = $row['name'];
                }
            }

            $class = ' class="" ';
            return form_dropdown('character', $options, '', $class);
        }
}
?>