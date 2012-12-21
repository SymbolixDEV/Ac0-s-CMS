<?php
class Character_model extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->auth = $this->load->database('auth', TRUE);  
        }
        
        function get_realm($realm_id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('name, ra, ra_port, soap, soap_port, char_db, world_db, id, unstuck_price, teleport_price, unstuck, teleport, changes, change_faction_price, change_race_price, change_appearance_price, 3d_char_preview, core');
			$this->auth->where('id', $realm_id);
            $query = $this->auth->get('realmlist', '1');
            if($query->num_rows() > 0)
            {
                return $query->row_array();
            }
        }
        
        function check_character_exist($id, $characters_db)
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

            $this->characters->select('guid, account');
            $this->characters->where('guid', $id);
            $query = $this->characters->get('characters', '1');
            $br = 0;
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $br++;
                    if($row['account']==$this->session->userdata('id'))
                        $br++;
                }
            }
            
            return $br;
        }
        
        function _return_character_items($items, $world_db)
        {
            $this->load->database();
            $config['hostname'] = $this->db->hostname;
            $config['username'] = $this->db->username;
            $config['password'] = $this->db->password;
            $config['database'] = $world_db;
            $config['dbdriver'] = "mysql";
            $config['dbprefix'] = "";
            $config['pconnect'] = FALSE;
            $config['db_debug'] = TRUE;
            $config['cache_on'] = FALSE;
            $config['cachedir'] = "";
            $config['char_set'] = "utf8";
            $config['dbcollat'] = "utf8_general_ci";

            $this->world = $this->load->database($config, TRUE);  
            
            $cont = '';
			
			$bad_types = array(2, 11, 12, 18, 24, 27, 28, 25, 26);
            
            foreach($items as $key => $value)
            {
				$explode = explode('-', $value);
                $this->world->select('displayid, InventoryType');
                $this->world->where('entry', $explode[0]);
                $query = $this->world->get('item_template', '1');
                
                if($query->num_rows() > 0)
                {
                    $row = $query->row_array();
					if(!in_array($row['InventoryType'], $bad_types))
						if ($cont == "") 
							$cont = $row['InventoryType'].','.$row['displayid'];
						else 
							$cont .= ','.$row['InventoryType'].','.$row['displayid'];
                }
            }
            return $cont;
        }
        
        function _select_characters_inventory_items($id, $realm_info)
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

            $this->character = $this->load->database($config, TRUE);  
            
            if($realm_info['core'] == 'trinity')
				$this->character->select('item, itemEntry, slot, item_instance.guid as i_guid');
			elseif($realm_info['core'] == 'oregon')
				$this->character->select('item, character_inventory.item_template as itemEntry, slot, item_instance.guid as i_guid');
			else
				return;
            $this->character->where('character_inventory.guid', $id);
			$this->character->where('character_inventory.bag', '0');
            $this->character->where('character_inventory.slot <=', '18');
            $this->character->from('character_inventory');
            $this->character->join('item_instance', 'item_instance.guid = character_inventory.item');
            
            $query = $this->character->get();
			
			$cont = array();
           
			if($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $row)
				{
					$cont[$row['slot']] = $row['itemEntry'].'-'.$row['i_guid'];
				}
			}
			
			return $cont;
        }
		
		function _select_characters_items_icons($items, $realm_info)
		{
			$this->cms = $this->load->database('default', TRUE);  
			$cont = array();
			$convert = array(
				0 => 0,
				1 => 1,
				2 => 2,
				14 => 3,
				4 => 4,
				3 => 5,
				18 => 6,
				8 => 7,
				9 => 8,
				5 => 9,
				6 => 10,
				7 => 11,
				10 => 12,
				11 => 13,
				12 => 14,
				13 => 15,
				15 => 16,
				16 => 17,
				17 => 18);
			for($i=0;$i<=18;$i++)
			{
				$slot = $convert[$i];
				if(!empty($items[$i]))
				{
					$explode = explode('-', $items[$i]);
					$this->cms->where('entry', $explode[0]);
					$query = $this->cms->get('item_icons', '1');
					if ($query->num_rows() > 0)
					{
						$row = $query->row_array();
						$cont[$slot] = '<a href="http://wotlk.openwow.com/?item='.$explode[0].'"><div style="float: left; z-index: 1000; width: 56px; height: 56px; background: url(\''.base_url('icon.php?icon='.$row['icon']).'\');"></div></a>';
					}
				}
				else
				{
					$cont[$slot] = '<div style="float: left; width: 56px; height: 56px; background: url(\''.base_url('content/img/slots').'/'.$slot.'.png\');" ></div>';
				}
			}
			
			return $cont;
		}
		
		function _get_char_name_with_title($name, $gender, $title)
		{
			if(empty($name))
				return;
			if($title == '0')
				return '<strong>'.$name.'</strong>';
				
			$genders[0] = "Male";
			$genders[1] = "Female";
			
			$player_gender = $genders[$gender];
			
			$this->cms = $this->load->database('default', TRUE);  
			$this->cms->where('InGameOrder', $title);
			$this->cms->select($player_gender.'Title as title');
			
			$query = $this->cms->get('titles', '1');
			
			$row = $query->row_array();
			
			return sprintf($row['title'], "<strong>".$name."</strong>");
		}
		
        function show_character($id='', $realm_info=array(), $own=FALSE)
        {
            if($id=='' || !is_array($realm_info))
                return;
				
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

            if($realm_info['core'] == 'trinity')
				$this->characters->select('guid, account, race, gender, class, name, money, level, playerBytes, playerBytes2, online, chosenTitle, power1, power2, power3, power4, power5, power6, power7, health');
			elseif($realm_info['core'] == 'oregon')
				$this->characters->select('guid, account, race, gender, class, name, money, level, playerBytes, playerBytes2, online, chosenTitle, powerMana, powerRage, powerHappiness, powerFocus, health');
			else
				return;
            $this->characters->where('guid', $id);
            $query = $this->characters->get('characters', '1');
			
			// Set Character Race/Gender
			$char_race = array(
                        1 => 'human',
                        2 => 'orc',
                        3 => 'dwarf',
                        4 => 'nightelf',
                        5 => 'scourge',
                        6 => 'tauren',
                        7 => 'gnome',
                        8 => 'troll',
                        9 => 'goblin',
                        10 => 'bloodelf',
                        11 => 'draenei',
                        22 => 'worgen');

			$char_gender = array(
                        0 => 'male',
                        1 => 'female');
			
			if($realm_info['core'] == 'trinity')
			{
				$power = array(
					'power1' => 'Mana',
					'power2' => 'Rage',
					'power3' => 'Focus',
					'power4' => 'Energy',
					'power5' => 'Happiness',
					'power6' => 'Runes',
					'power7' => 'Runic Power');

				$class_power[1] = "power2";
				$class_power[2] = "power1";
				$class_power[3] = "power1";
				$class_power[4] = "power4";
				$class_power[5] = "power1";
				$class_power[6] = "power7";
				$class_power[7] = "power1";
				$class_power[8] = "power1";
				$class_power[9] = "power1";
				$class_power[11] = "power1";
			}
			elseif($realm_info['core'] == 'oregon')
			{
				$power = array(
					'powerMana' => 'Mana',
					'powerRage' => 'Rage',
					'powerFocus' => 'Focus',
					'powerEnergy' => 'Energy',
					'powerHappiness' => 'Happiness');

				$class_power[1] = "powerRage";
				$class_power[2] = "powerMana";
				$class_power[3] = "powerMana";
				$class_power[4] = "powerEnergy";
				$class_power[5] = "powerMana";
				$class_power[7] = "powerMana";
				$class_power[8] = "powerMana";
				$class_power[9] = "powerMana";
				$class_power[11] = "powerMana";
			}
			else
				return;

            $cont = '<table width="100%">';
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $online = ($row['online']==1) ? 'Online' : 'Offline';
                    $own = ($row['account'] == $this->session->userdata('id')) ? TRUE : FALSE;
                    $gold=substr($row['money'], 0, -4); if ($gold=='') {$gold="0";}
                    $silver=substr($row['money'], 0, -2); 
                    $silver2=substr($silver, -2); if ($silver2=='') {$silver2="0";}
                    $copper=substr($row['money'], -2); if ($copper=='') {$copper="0";}

                    $side = ($row['race']==1 || $row['race']==3 || $row['race']==4 || $row['race']==7 || $row['race']==11 || $row['race']==22) ? 0 : 1;
					
					$char_name = $this->_get_char_name_with_title($row['name'], $row['gender'], $row['chosenTitle']);
                    
					$secondPower['name'] = $power[$class_power[$row['class']]];
					$secondPower['value'] = $row[$class_power[$row['class']]];
					
                    $cont .= '
                        <tr>
                            <td rowspan="4" width="86px"><img src="'.base_url('content/img/icon/avatars/'.$row['race'].'-'.$row['gender'].'.jpg').'"></td>
                            <td colspan="5"><i>'.$char_name.'</i> - Level: '.$row['level'].'</td>
                            <td><span class="realm_'.$online.'" style="float: right;">'.$online.'</span></td>
                        </tr>
                        <tr>
                            <td colspan="1">Money: '.$gold.'<img src="'.base_url('content/img/icon/pvpranks/money_gold.gif').'" width="10px" height="10px"> '.$silver2.' <img src="'.base_url('content/img/icon/pvpranks/money_silver.gif').'" width="10px" height="10px"> '.$copper.' <img src="'.base_url('content/img/icon/pvpranks/money_copper.gif').'" width="10px" height="10px"></td>
							<td colspan="5"><div class="bars rounded health"><div>'.$row['health'].'</div></div>
                        </tr>
                        <tr>
                            <td colspan="1"><img src="'.base_url('content/img/icon/class/'.$row['class'].'.gif').'" title="Class" />&nbsp;&nbsp;<img src="'.base_url('content/img/icon/race/'.$row['race'].'-'.$row['gender'].'.gif').'"  title="Race" />&nbsp;&nbsp;<img src="'.base_url('content/img/icon/pvpranks/rank_default_'.$side.'.gif').'"  title="Faction" /></td>
							<td colspan="5"><div class="bars rounded '.strtolower($secondPower['name']).'"><div>'.$secondPower['value'].'</div></div></td>
                        </tr>
                        <tr style="height: 10px"><td colspan="6"><hr></hr></td></tr>';
                    
                    $hidden = array('char_id' => $row['guid'], 'realm_id' => $realm_info['id'], 'return_link' => current_url());
                    $cont .= form_open('index.php/character/character_change', '', $hidden);
                    
                    
                    
                    if($own)
                    {
                        $br = 0;
                        $cont .= '<tr><td colspan="7">'.$this->session->flashdata('invalid_option').'
                            <div class="service-links">
                                <div class="content character-services" id="character-services">
                                    <ul>';
                        
                        $cost_info =  array('1' => 'Vote Points', '2' => 'VIP Points');
                                        
                        if($realm_info['changes']==1)
                        {
                            $br++;
                            $attributes_change_appearance = array('name' => 'option', 'id' => 'change_appearance', 'value' => 'change_appearance');
                            $attributes_change_faction = array('name' => 'option', 'id' => 'change_faction', 'value' => 'change_faction');
                            $attributes_change_race = array('name' => 'option', 'id' => 'change_race', 'value' => 'change_race');
                            
                            $_price = explode('-', $realm_info['change_appearance_price']);
                            $price = ($realm_info['change_appearance_price']=='0' || $_price[0]=='0') ? 'FREE' : $_price[0];
                            $price_info = ($price=='FREE') ? '' : $cost_info[$_price[1]];
                            $cont .= '<label for="change_appearance"><li class="wow-service pcc"><a><span class="icon"></span><span class="radio">'.form_radio($attributes_change_appearance).'</span>
                                      <strong>Appearance Change</strong>Change your characters\' appearance <br />(Optional name change included).<br />Cost '.$price.' '.$price_info.'.</a></li></label>';
                        
                            $_price = explode('-', $realm_info['change_faction_price']);
                            $price = ($realm_info['change_faction_price']=='0' || $_price[0]=='0') ? 'FREE' : $_price[0];
                            $price_info = ($price=='FREE') ? '' : $cost_info[$_price[1]];
                            $cont .= '<label for="change_faction"><li class="wow-service pfc"><a><span class="icon"></span><span class="radio">'.form_radio($attributes_change_faction).'</span>
                                        <strong>Faction Change</strong>Change a character\'s faction <br />(Horde to Alliance or Alliance to Horde).<br />Cost '.$price.' '.$price_info.'.</a></li></label>';
                        
                            $_price = explode('-', $realm_info['change_race_price']);
                            $price = ($realm_info['change_race_price']=='0' || $_price[0]=='0') ? 'FREE' : $_price[0];
                            $price_info = ($price=='FREE') ? '' : $cost_info[$_price[1]];
                            $cont .= '<label for="change_race"><li class="wow-service prc"><a><span class="icon"></span><span class="radio">'.form_radio($attributes_change_race).'</span>
                                        <strong>Race Change</strong>Change a character\'s race <br />(within your current faction).<br />Cost '.$price.' '.$price_info.'.</a></li></label>';
                        } 
                        
                        if($realm_info['teleport']==1)
                        {
                            $br++;
                            $_price = explode('-', $realm_info['teleport_price']);
                            $price = ($realm_info['teleport_price']=='0' || $_price[0]=='0') ? 'FREE' : $_price[0];
                            $price_info = ($price=='FREE') ? '' : $cost_info[$_price[1]];
                            $attributes_teleport = array('name' => 'option', 'id' => 'teleport', 'value' => 'teleport');
                            
                            $options = ($side==0) ? array(NULL => 'Select Destination', 'Stormwind'  => 'The Stormwind', 'Ironforge' => 'Ironforge', 'Darnassus' => 'Darnassus', 'Exodar' => 'The Exodar', 'Shattrath' => 'Shattrath City', 'Dalaran' => 'Dalaran') : array(NULL => 'Select Destination', 'Orgrimmar'  => 'Orgrimmar', 'Undercity' => 'Undercity', 'ThunderBluff' => 'Thunder Bluff', 'Silvermoon' => 'Silvermoon City', 'Shattrath' => 'Shattrath City', 'Dalaran' => 'Dalaran');

                            $cont .= '<li class="wow-service pct"><a><label for="teleport"><span class="icon"></span><span class="radio">'.form_radio($attributes_teleport).'</span>
                                        <strong>Character Teleport</strong></label>'.form_dropdown('destination', $options).'<label for="teleport"><br />Cost '.$price.' '.$price_info.'. Character must be offline.</label></a></li>';
                        }   
                        
                        if($realm_info['unstuck']==1)
                        {
                            $br++;
                            $_price = explode('-', $realm_info['unstuck_price']);
                            $price = ($realm_info['unstuck_price']=='0' || $_price[0]=='0') ? 'FREE' : $_price[0];
                            $price_info = ($price=='FREE') ? '' : $cost_info[$_price[1]];
                            $attributes_unstuck = array('name' => 'option', 'id' => 'unstuck', 'value' => 'unstuck');
                            
                            $cont .= ' <label for="unstuck"><li class="wow-service char-move"><a><span class="icon"></span><span class="radio">'.form_radio($attributes_unstuck).'</span>
                                        <strong>Character Unstuck</strong>Transfer your characters when he gets stuck.<br />Cost '.$price.' '.$price_info.'. Character must be offline.</a></li></label>';
                        }
                        
                        if($br>0)
                        {
                            $attributes_submit = array('name' => 'change_character_submit', 'id' => 'change_character_submit', 'class' => 'cool', 'value' => "Submit");
                            $cont .= '<span style="clear: both; display: block;"></span><center>'.form_submit($attributes_submit).'</center>';
                        }
                                  $cont .= '</ul>
                                </div>
                            </div></td></tr>';
                    }
                    $cont .= form_close();
					
					$itemsInventory = $this->_select_characters_inventory_items($id, $realm_info);
					$itemsIcons = $this->_select_characters_items_icons($itemsInventory, $realm_info);
					
					// Head
					$cont .= '<tr><td width="48" height="48">'.$itemsIcons[0].'</td>'; 
					$cont .= '<td rowspan="8" colspan="5" align="center" valign="top">';
                    if($realm_info['3d_char_preview']==1)
                    {
                        $guid = $row['guid'];
                        $race = $row['race'];
                        $gender = $row['gender'];
                        $b = $row['playerBytes'];
                        $b2 = $row['playerBytes2'];

                        // Set Character Features
                        $ha = ($b>>16)%256;
                        $hc = ($b>>24)%256;
                        $fa = ($b>>8)%256;
                        $sk = $b%256;
                        $fh = $b2%256;

                        $rg = $char_race[$race].$char_gender[$gender];
                        $eq = $this->_return_character_items($itemsInventory, $realm_info['world_db']);
						// 3D Character Preview
						$cont .= '
									<object id="wowhead" type="application/x-shockwave-flash" data="http://static.wowhead.com/modelviewer/ModelView.swf" height="450px" width="300px"> 
										<param name="quality" value="low">
										<param name="allowscriptaccess" value="always">
										<param name="menu" value="false">
										<param value="transparent" name="wmode">
										'.sprintf('<param name="flashvars" value="model=%s&amp;modelType=16&amp;equipList=%s&amp;ha=%s&amp;hc=%s&amp;fa=%s&amp;sk=%s&amp;fh=%s&amp;fc=7&amp;mode=1&amp;contentPath=http://static.wowhead.com/modelviewer/">', $rg, $eq, $ha, $hc, $fa, $sk, $fh).'
										<param name="movie" value="http://static.wowhead.com/modelviewer/ModelView.swf">
									</object>'; 
                    }
					$cont .= '</td>';
					// Hands
					$cont .= '<td width="48" height="48" style="float: right; margin-right: 8px;">'.$itemsIcons[8].'</td></tr>'; 
					//Neck
					$cont .= '<tr><td width="48" height="48">'.$itemsIcons[1].'</td>';
					//Belt
					$cont .= '<td width="48" height="48" style="float: right; margin-right: 8px;">'.$itemsIcons[9].'</td></tr>';
					//Shoulders
					$cont .= '<tr><td width="48" height="48">'.$itemsIcons[2].'</td>';
					//Pants
					$cont .= '<td width="48" height="48" style="float: right; margin-right: 8px;">'.$itemsIcons[10].'</td></tr>';
					//Back
					$cont .= '<tr><td width="48" height="48">'.$itemsIcons[3].'</td>';
					//Boots
					$cont .= '<td width="48" height="48" style="float: right; margin-right: 8px;">'.$itemsIcons[11].'</td></tr>';
					//Chest
					$cont .= '<tr><td width="48" height="48">'.$itemsIcons[4].'</td>';
					//Ring 1
					$cont .= '<td width="48" height="48" style="float: right; margin-right: 8px;">'.$itemsIcons[12].'</td></tr>';
					//Shirt
					$cont .= '<tr><td width="48" height="48">'.$itemsIcons[5].'</td>';
					//Ring 2
					$cont .= '<td width="48" height="48" style="float: right; margin-right: 8px;">'.$itemsIcons[13].'</td></tr>';
					//Tabard
					$cont .= '<tr><td width="48" height="48">'.$itemsIcons[6].'</td>';
					//Trinket 1
					$cont .= '<td width="48" height="48" style="float: right; margin-right: 8px;">'.$itemsIcons[14].'</td></tr>';
					//Bracer
					$cont .= '<tr><td width="48" height="48">'.$itemsIcons[7].'</td>';
					//Trinket 2
					$cont .= '<td width="48" height="48" style="float: right; margin-right: 8px;">'.$itemsIcons[15].'</td></tr>';
					//Main-hand Weapon
					$cont .= '<tr><td colspan="7" align="center"><div style="width: 168px; height: 56px; margin: 0 auto;">'.$itemsIcons[16];
					//Off-hand Weapon
					$cont .= $itemsIcons[17];
					//Ranged Weapon
					$cont .= $itemsIcons[18].'</div></td>';
					
					$cont .= '</tr></table></td></tr></table>';
                }
            }
            else
                $cont .= '<tr><td><span class="acenter">No players for that realm.</span></td></tr><tr style="height: 10px"></tr>';
            $cont .= '</table>';
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
                            <td><strong><a href="'.base_url('profile/character/'.$row['guid'].'/'.$realm_id).'">'.$row['name'].'</a></strong> - Level: '.$row['level'].'</td>
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
                $cont .= '<tr><td><span class="acenter">No players for that realm.</span></td></tr><tr style="height: 10px"></tr>';
            $cont .= '</table>';
            return $cont;
        }
        
        function teleport($characters_db, $guid, $destination)
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

            switch($destination):
                //stormwind
                case 'Stormwind':
                    $map = "0";
                    $x = "-8913.23";
                    $y = "554.633";
                    $z = "93.7944";
                    $place = "Stormwind City";
                    break;
                //ironforge
                case 'Ironforge':
                    $map = "0";
                    $x = "-4981.25";
                    $y = "-881.542";
                    $z = "501.66";
                    $place = "Ironforge";
                    break;
                //darnassus
                case 'Darnassus':
                    $map = "1";
                    $x = "9951.52";
                    $y = "2280.32";
                    $z = "1341.39";
                    $place = "Darnassus";
                    break;
                //exodar
                case 'Exodar':
                    $map = "530";
                    $x = "-3987.29";
                    $y = "-11846.6";
                    $z = "-2.01903";
                    $place = "The Exodar";
                    break;
                //orgrimmar
                case 'Orgrimmar':
                    $map = "1";
                    $x = "1676.21";
                    $y = "-4315.29";
                    $z = "61.5293";
                    $place = "Orgrimmar";
                    break;
                //thunderbluff
                case 'ThunderBluff':
                    $map = "1";
                    $x = "-1196.22";
                    $y = "29.0941";
                    $z = "176.949";
                    $place = "Thunder Bluff";
                    break;
                //undercity
                case 'Undercity':
                    $map = "0";
                    $x = "1586.48";
                    $y = "239.562";
                    $z = "-52.149";
                    $place = "The Undercity";
                    break;
                //silvermoon
                case 'Silvermooncity':
                    $map = "530";
                    $x = "9473.03";
                    $y = "-7279.67";
                    $z = "14.2285";
                    $place = "Silvermoon City";
                    break;
                //shattrath
                case 'Shattrath':
                    $map = "530";
                    $x = "-1863.03";
                    $y = "4998.05";
                    $z = "-21.1847";
                    $place = "Shattrath";
                    break;
                            //dalaran
                case 'Dalaran':
                    $map = "571";
                    $x = "5804.15";
                    $y = "624.77";
                    $z = "647.77";
                    $place = "Dalaran";
                    break;
                //for unknowness -> shattrath
                default:
                    $map = "530";
                    $x = "-1863.03";
                    $y = "4998.05";
                    $z = "-21.1847";
                    $place = "Shattrath";
                    break;
                endswitch;

            $data = array(
                'position_x' => $x,
                'position_y' => $y,
                'position_z' => $z,
                'map' => $map
            );
            $this->characters->where('guid', $guid);
            $query = $this->characters->update('characters', $data);

            return $this->characters->affected_rows();
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
        
        function get_unstuck_zone($characters_db, $guid)
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

            $this->characters->where('guid', $guid);
            $query = $this->characters->get('character_homebind');
            if($query->num_rows() > 0)
            {
                return $query->row_array();
            }
            else 
                return 0;
        }

        function unstuck($characters_db, $guid)
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
            $unstuck_zone = $this->get_unstuck_zone($characters_db, $guid);
            $data = array(
                'position_x' => $unstuck_zone['posX'],
                'position_y' => $unstuck_zone['posY'],
                'position_z' => $unstuck_zone['posZ'],
                'map' => $unstuck_zone['mapId'],
                'zone' => $unstuck_zone['zoneId']
            );
            $this->characters->where('guid', $guid);
            $query = $this->characters->update('characters', $data);

            return $this->characters->affected_rows();
        }
        
        function get_character_name($guid, $characters_db)
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
            $this->characters->where('guid', $guid);
            $query = $this->characters->get('characters');
            if($query->num_rows() > 0)
            {
                $row = $query->row_array();
                return $row['name'];
            }
            else 
                return '';
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
                    $link = ($row['custom']==0) ? 'href="http://www.wowhead.com/item='.$row['entry'].'" ' : '';
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

            $class = ' class="cool" ';
            return form_dropdown('character', $options, '', $class);
        }
        
        function show_purchase($realm_id, $type)
        {
            $cont = '';
            $data = array ('realm_id' => $realm_id, 'type' => $type, 'return_link' => current_url());
            $cont .= form_open('index.php/profile/purchase', '', $data);
            $realm_info = $this->get_realm($realm_id);
            $cont .= $this->session->flashdata('invalid_option');
            $cont .= $this->show_realms($realm_id, 'profile/'.$type.'_shop/');
            $cont .= $this->return_rewards($realm_id, $type);
            $attributes_submit = array('name' => 'purchase_submit', 'id' => 'purchase_submit', 'class' => 'cool', 'value' => "Purchase");
            $cont .= '<br />'.$this->return_character_menu($realm_info['char_db']).' &nbsp;<span style="padding-top:2px;">'.form_submit($attributes_submit).'</span>';
            $cont .= form_close();
            
            return $cont;
        }
}
?>