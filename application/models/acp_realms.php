<?php
class Acp_realms extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->cms = $this->load->database('default', TRUE);  
        }
        
        function delete_realm($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->cms->where('id', $id);
            $query = $this->cms->delete('realmlist');
        }
        
        function return_realms()
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->order_by('id', 'asc'); 
            $query = $this->auth->get('realmlist');
            $cont = '';
            foreach ($query->result_array() as $row)
            {
                $cont .= '<div class="menage_news"><div class="content"><div class="news_id">'.$row['id'].'</div><div class="news_title">'.$row['name'].'</div><div class="news_options"><a class="edit" href="'.base_url('index.php/acp/realms/index/edit/'.$row['id']).'">Edit</a> - <a class="delete" href="'.base_url('index.php/acp/realms/index/delete/'.$row['id']).'">Delete</a></div><div class="clear"></div></div></div>';
            }
            
            return $cont;
        }
        
        function return_realm($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->where('id', $id);
            $query = $this->auth->get('realmlist');
            $cont = '';
            foreach ($query->result_array() as $row)
            {
                $hidden = array('realm_id' => $row['id']);
                $cont .= $this->session->flashdata('not_filled');
                $cont .= form_open('index.php/acp/realms/edit_validation', '', $hidden);
                $cont .= '<table width="100%">';
                $data = array(
                    'name'        => 'realm_name',
                    'id'          => 'realm_name',
                    'value'       => $row['name'],
                    );
                $cont .= '<tr><td><label for="realm_name">Name</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'realm_address',
                    'id'          => 'realm_address',
                    'value'       => $row['address'],
                    );
                $cont .= '</td><td><label for="realm_address">Adress</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'realm_port',
                    'id'          => 'realm_port',
                    'value'       => $row['port'],
                    );
                $cont .= '</td><td><label for="realm_port">Port</label>: <br />'.form_input($data);
                $cont .= '</td></tr>';
                $data = array(
                    'name'        => 'realm_allow_level',
                    'id'          => 'realm_allow_level',
                    'value'       => $row['allowedSecurityLevel'],
                    );
                $cont .= '<tr><td><label for="realm_allow_level">Allow GM Level</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'ra_port',
                    'id'          => 'ra_port',
                    'value'       => $row['ra_port'],
                    'style'       => 'width:50px;'
                    );
                $options = array(
                    '0'   => 'Disabled',
                    '1' => 'Enabled',
                    );
                $cont .= '</td><td><label for="ra_port">RA</label>: <br />'.form_input($data).form_dropdown('ra', $options, $row['ra'], 'style="width: 100px"');
                 $data = array(
                    'name'        => 'soap_port',
                    'id'          => 'soap_port',
                    'value'       => $row['soap_port'],
                    'style'       => 'width:50px;'
                    );
                $options = array(
                    '0'   => 'Disabled',
                    '1' => 'Enabled',
                    );
                $cont .= '</td><td><label for="soap_port">Soap</label>: <br />'.form_input($data).form_dropdown('soap', $options, $row['soap'], 'style="width: 100px"');
                $cont .= '</td></tr>';
                $data = array(
                    'name'        => 'char_db',
                    'id'          => 'char_db',
                    'value'       => $row['char_db'],
                    );
                $cont .= '<tr><td><label for="char_db">Character DB</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'world_db',
                    'id'          => 'world_db',
                    'value'       => $row['world_db'],
                    );
                $cont .= '</td><td><label for="world_db">World DB</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'p_limit',
                    'id'          => 'p_limit',
                    'value'       => $row['p_limit'],
                    );
                $cont .= '</td><td><label for="p_limit">Player Limit</label>: <br />'.form_input($data);
                $cont .= '</td></tr>';
                
                $price = ($row['unstuck_price']=="0") ? array('0' => '0', '1' => '0') : explode('-',$row['unstuck_price']);
                $data = array(
                    'name'        => 'unstuck_price',
                    'id'          => 'unstuck_price',
                    'value'       => $price[0],
                    'style'       => 'width:35px;'
                    );
                $options = array(
                    '0'   => 'Off',
                    '1' => 'On',
                    );
                $options2 = array(
                    '1'   => 'vp (Vote Points)',
                    '2' => 'dp (Donate Points)',
                    );
                $cont .= '<tr><td><label for="unstuck_price">Unstuck</label>: <br />'.form_dropdown('unstuck', $options, $row['unstuck'], 'style="width: 58px"').form_dropdown('unstuck_price_type', $options2, $price[1], 'style="width: 53px"').form_input($data);
                $price = ($row['teleport_price']=="0") ? array('0' => '0', '1' => '0') : explode('-',$row['teleport_price']);
                $data = array(
                    'name'        => 'teleport_price',
                    'id'          => 'teleport_price',
                    'value'       => $price[0],
                    'style'       => 'width:35px;'
                    );
                $options = array(
                    '0'   => 'Off',
                    '1' => 'On',
                    );
                $options2 = array(
                    '1'   => 'vp (Vote Points)',
                    '2' => 'dp (Donate Points)',
                    );
                $cont .= '</td><td><label for="teleport_price">Teleport</label>: <br />'.form_dropdown('teleport', $options, $row['teleport'], 'style="width: 58px"').form_dropdown('teleport_price_type', $options2, $price[1], 'style="width: 53px"').form_input($data);
                $options = array(
                    '0'   => 'Disabled',
                    '1' => 'Enabled',
                    );
                $cont .= '</td><td><label for="">Character Changes</label>: <br />'.form_dropdown('changes', $options, $row['changes'], 'style="width: 100px"');
                $cont .= '</td></tr>';
                $price = ($row['change_race_price']=="0") ? array('0' => '0', '1' => '0') : explode('-',$row['change_race_price']);
                $data = array(
                    'name'        => 'change_race_price',
                    'id'          => 'change_race_price',
                    'value'       => $price[0],
                    'style'       => 'width:105px;'
                    );
                $options = array(
                    '1'   => 'vp (Vote Points)',
                    '2' => 'dp (Donate Points)',
                    );
                $cont .= '<tr><td><label for="change_race_price">Character Change Race</label>: <br />'.form_dropdown('change_race_price_type', $options, $price[1], 'style="width: 53px"').form_input($data);
                $price = ($row['change_faction_price']=="0") ? array('0' => '0', '1' => '0') : explode('-',$row['change_faction_price']);
                $data = array(
                    'name'        => 'change_faction_price',
                    'id'          => 'change_faction_price',
                    'value'       => $price[0],
                    'style'       => 'width:105px;'
                    );
                $options = array(
                    '1'   => 'vp (Vote Points)',
                    '2' => 'dp (Donate Points)',
                    );
                $cont .= '</td><td><label for="change_faction_price">Character Change Faction</label>: <br />'.form_dropdown('change_faction_price_type', $options, $price[1], 'style="width: 53px"').form_input($data);
                $price = ($row['change_appearance_price']=="0") ? array('0' => '0', '1' => '0') : explode('-',$row['change_appearance_price']);
                $data = array(
                    'name'        => 'change_appearance_price',
                    'id'          => 'change_appearance_price',
                    'value'       => $price[0],
                    'style'       => 'width:105px;'
                    );
                $options = array(
                    '1'   => 'vp (Vote Points)',
                    '2' => 'dp (Donate Points)',
                    );
                $cont .= '</td><td><label for="change_appearance_price">Character Change Appearance</label>: <br />'.form_dropdown('change_appearance_price_type', $options, $price[1], 'style="width: 53px"').form_input($data);
                $cont .= '</td></tr>';
                $options = array(
                    '0'   => 'Disabled',
                    '1' => 'Enabled',
                    );
                $cont .= '<tr><td><label for="">3D Character Preview</label>: <br />'.form_dropdown('3d_char_preview', $options, $row['3d_char_preview'], 'style="width: 100px"').'</td>';
				$data = array(
                        'name'        => 'core',
                        'id'          => 'core',
                        'style'       => 'width:50px;'
                        );
                    $options = array(
                        'trinity'   => 'TrinityCore',
                        'oregon' => 'OregonCore',
                        );
                    $cont .= '<td><label for="core">Core</label>: <br />'.form_dropdown('core', $options, $row['core'], 'style="width: 180px"');
                $data = array(
                    'name'        => 'realms_edit_submit',
                    'id'          => 'realms_edit_submit',
                    'value'       => 'Submit',
                    'style'       => 'width:70px;'
                    );
                $cont .= '</tr><tr><td colspan="3" align="right">'.form_submit($data).'</td><tr>';
                $cont .= form_close('</table>');
            }
            
            return $cont;
        }
        
        function select_realm_max_id()
        {
            $this->auth = $this->load->database('auth', TRUE);
            $this->auth->select_max('id');
            $query = $this->auth->get('realmlist');
            if($query->num_rows()>0)
            {
                foreach ($query->result_array() as $row)
                {
                    return $row['id'];
                }
            }
            else
                return 0;
        }
        
        function update_realm($id,$name,$address,$port,$allow_level,$ra,$ra_port,$soap,$soap_port,$char_db,$world_db,$p_limit,$unstuck,$teleport,$changes,$d_char_preview,$unstuck_price,$teleport_price,$change_faction_price,$change_race_price,$change_appearance_price,$core)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $unstuck_price = ($unstuck_price=="-1") ? "0" : $unstuck_price;
            $teleport_price = ($teleport_price=="-1") ? "0" : $teleport_price;
            $change_faction_price = ($change_faction_price=="-1") ? "0" : $change_faction_price;
            $change_race_price = ($change_race_price=="-1") ? "0" : $change_race_price;
            $change_appearance_price = ($change_appearance_price=="-1") ? "0" : $change_appearance_price;
            
            $this->auth->where('id', $id);
            $data = array(
                'name'        => $name,
                'address'          => $address,
                'port'        => $port,
                'allowedSecurityLevel'        => $allow_level,
                'ra'        => $ra,
                'ra_port'        => $ra_port,
                'soap'        => $soap,
                'soap_port'        => $soap_port,
                'char_db'        => $char_db,
                'world_db'        => $world_db,
                'p_limit'        => $p_limit,
                'unstuck'        => $unstuck,
                'teleport'        => $teleport,
                'changes'        => $changes,
                '3d_char_preview'        => $d_char_preview,
                'unstuck_price'        => $unstuck_price,
                'teleport_price'        => $teleport_price,
                'change_faction_price'        => $change_faction_price,
                'change_race_price'        => $change_race_price,
                'change_appearance_price'        => $change_appearance_price,   
				'core'	=> $core
            );
            $query = $this->auth->update('realmlist',$data);
        }
        
        function add_realm($name,$address,$port,$allow_level,$ra,$ra_port,$soap,$soap_port,$char_db,$world_db,$p_limit,$unstuck,$teleport,$changes,$d_char_preview,$unstuck_price='0',$teleport_price='0',$change_faction_price='0',$change_race_price='0',$change_appearance_price='0', $core='trinity')
        {
            $this->auth = $this->load->database('auth', TRUE);
            $unstuck_price = ($unstuck_price=="-1") ? "0" : $unstuck_price;
            $teleport_price = ($teleport_price=="-1") ? "0" : $teleport_price;
            $change_faction_price = ($change_faction_price=="-1") ? "0" : $change_faction_price;
            $change_race_price = ($change_race_price=="-1") ? "0" : $change_race_price;
            $change_appearance_price = ($change_appearance_price=="-1") ? "0" : $change_appearance_price;
            
            $id = $this->select_realm_max_id() + 1;
            
            $data = array(
                'id'        => $id,
                'name'        => $name,
                'address'          => $address,
                'port'        => $port,
                'allowedSecurityLevel'        => $allow_level,
                'ra'        => $ra,
                'ra_port'        => $ra_port,
                'soap'        => $soap,
                'soap_port'        => $soap_port,
                'char_db'        => $char_db,
                'world_db'        => $world_db,
                'p_limit'        => $p_limit,
                'unstuck'        => $unstuck,
                'teleport'        => $teleport,
                'changes'        => $changes,
                '3d_char_preview'        => $d_char_preview,
                'unstuck_price'        => $unstuck_price,
                'teleport_price'        => $teleport_price,
                'change_faction_price'        => $change_faction_price,
                'change_race_price'        => $change_race_price,
                'change_appearance_price'        => $change_appearance_price, 
				'core' => $core
            );
            $query = $this->auth->insert('realmlist',$data);
        }
}
?>