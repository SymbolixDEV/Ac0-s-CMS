<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Realms extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
            
        }
        
	public function index($page = 'menage', $id='')
	{
            $data['title'] = ucfirst($page);
            $data['ui'] = 'Realms';
            $data['left_content'] = $this->auto->pages($page, array('menage', 'add', 'logs'), 'realms');
            $data['right_content'] = $this->show_right_content($page, $id);
            $this->load->view('acp/main_view', $data);
	}
        
        function show_right_content($page, $id)
        {
            if($page=='edit' && $id=='' || $page=='delete' && $id=='')
            {
                $this->index();
                return;
            }
            
            $cont = '';
            $this->load->model('acp_realms');
            switch($page)
            {
                case "add":
                    $cont .= $this->session->flashdata('not_filled');
                    $cont .= form_open('index.php/acp/realms/add_validation');
                    $cont .= '<table width="100%">';
                    $data = array(
                        'name'        => 'realm_name',
                        'id'          => 'realm_name'
                        );
                    $cont .= '<tr><td><label for="realm_name">Name</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'realm_address',
                        'id'          => 'realm_address'
                        );
                    $cont .= '</td><td><label for="realm_address">Adress</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'realm_port',
                        'id'          => 'realm_port'
                        );
                    $cont .= '</td><td><label for="realm_port">Port</label>: <br />'.form_input($data);
                    $cont .= '</td></tr>';
                    $data = array(
                        'name'        => 'realm_allow_level',
                        'id'          => 'realm_allow_level'
                        );
                    $cont .= '<tr><td><label for="realm_allow_level">Allow GM Level</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'ra_port',
                        'id'          => 'ra_port',
                        'style'       => 'width:50px;'
                        );
                    $options = array(
                        '0'   => 'Disabled',
                        '1' => 'Enabled',
                        );
                    $cont .= '</td><td><label for="ra_port">RA</label>: <br />'.form_input($data).form_dropdown('ra', $options, '', 'style="width: 100px"');
                     $data = array(
                        'name'        => 'soap_port',
                        'id'          => 'soap_port',
                        'style'       => 'width:50px;'
                        );
                    $options = array(
                        '0'   => 'Disabled',
                        '1' => 'Enabled',
                        );
                    $cont .= '</td><td><label for="soap_port">Soap</label>: <br />'.form_input($data).form_dropdown('soap', $options, '', 'style="width: 100px"');
                    $cont .= '</td></tr>';
                    $data = array(
                        'name'        => 'char_db',
                        'id'          => 'char_db'
                        );
                    $cont .= '<tr><td><label for="char_db">Character DB</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'world_db',
                        'id'          => 'world_db'
                        );
                    $cont .= '</td><td><label for="world_db">World DB</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'p_limit',
                        'id'          => 'p_limit'
                        );
                    $cont .= '</td><td><label for="p_limit">Player Limit</label>: <br />'.form_input($data);
                    $cont .= '</td></tr>';

                    $data = array(
                        'name'        => 'unstuck_price',
                        'id'          => 'unstuck_price',
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
                    $cont .= '<tr><td><label for="unstuck_price">Unstuck</label>: <br />'.form_dropdown('unstuck', $options, '', 'style="width: 58px"').form_dropdown('unstuck_price_type', $options2, '', 'style="width: 53px"').form_input($data);
                    $data = array(
                        'name'        => 'teleport_price',
                        'id'          => 'teleport_price',
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
                    $cont .= '</td><td><label for="teleport_price">Teleport</label>: <br />'.form_dropdown('teleport', $options, '', 'style="width: 58px"').form_dropdown('teleport_price_type', $options2, '', 'style="width: 53px"').form_input($data);
                    $options = array(
                        '0'   => 'Disabled',
                        '1' => 'Enabled',
                        );
                    $cont .= '</td><td><label for="">Character Changes</label>: <br />'.form_dropdown('changes', $options, '', 'style="width: 100px"');
                    $cont .= '</td></tr>';
                    $data = array(
                        'name'        => 'change_race_price',
                        'id'          => 'change_race_price',
                        'style'       => 'width:105px;'
                        );
                    $options = array(
                        '1'   => 'vp (Vote Points)',
                        '2' => 'dp (Donate Points)',
                        );
                    $cont .= '<tr><td><label for="change_race_price">Character Change Race</label>: <br />'.form_dropdown('change_race_price_type', $options, '', 'style="width: 53px"').form_input($data);
                    $data = array(
                        'name'        => 'change_faction_price',
                        'id'          => 'change_faction_price',
                        'style'       => 'width:105px;'
                        );
                    $options = array(
                        '1'   => 'vp (Vote Points)',
                        '2' => 'dp (Donate Points)',
                        );
                    $cont .= '</td><td><label for="change_faction_price">Character Change Faction</label>: <br />'.form_dropdown('change_faction_price_type', $options, '', 'style="width: 53px"').form_input($data);
                    $data = array(
                        'name'        => 'change_appearance_price',
                        'id'          => 'change_appearance_price',
                        'style'       => 'width:105px;'
                        );
                    $options = array(
                        '1'   => 'vp (Vote Points)',
                        '2' => 'dp (Donate Points)',
                        );
                    $cont .= '</td><td><label for="change_appearance_price">Character Change Appearance</label>: <br />'.form_dropdown('change_appearance_price_type', $options, '', 'style="width: 53px"').form_input($data);
                    $cont .= '</td></tr>';
                    $options = array(
                        '0'   => 'Disabled',
                        '1' => 'Enabled',
                        );
                    $cont .= '<tr><td><label for="">3D Character Preview</label>: <br />'.form_dropdown('3d_char_preview', $options, '', 'style="width: 100px"').'</td>';
					$data = array(
                        'name'        => 'core',
                        'id'          => 'core',
                        'style'       => 'width:50px;'
                        );
                    $options = array(
                        'trinity'   => 'TrinityCore',
                        'oregon' => 'OregonCore',
                        );
                    $cont .= '<td><label for="core">Core</label>: <br />'.form_dropdown('core', $options, '', 'style="width: 180px"');
                    $data = array(
                        'name'        => 'realms_edit_submit',
                        'id'          => 'realms_edit_submit',
                        'value'       => 'Submit',
                        'style'       => 'width:70px;'
                        );
                    $cont .= '</tr><tr><td colspan="3" align="right">'.form_submit($data).'</td><tr>';
                    $cont .= form_close('</table>');
                    break;
                
                case "edit":
                    $cont .= $this->acp_realms->return_realm($id);
                    break;
                
                case "logs":
                    $cont .= "<table width='100%' border = '#000000'>
                                <tr><td>Account</td><td>Action</td><td>Date</td></tr>";
                    foreach($this->auto->return_log('realms') as $row)
                    {
                        $cont .= "<tr><td>".$row['account']."</td><td>".$row['comment']."</td><td>".$row['date']."</td></tr>";
                    }
                    $cont .= "</table>";
                    break;
                
                case "delete":
                    $this->acp_realms->delete_realm($id);
                    $this->auto->logging('Deleted Realm ID: '.$id, 'realms');
                    redirect('index.php/acp/realms');
                    break;
                
                default:
                    $cont .= $this->acp_realms->return_realms();
                    break;
            }
            return $cont;
        }
        
        function edit_validation()
        {
            $rules = $this->form_validation;
            $rules->set_rules('realm_id', 'ID', 'required|trim');
            $rules->set_rules('realm_name', 'Name', 'required|trim');
            $rules->set_rules('realm_address', 'Adress', 'required|trim');
            $rules->set_rules('realm_port', 'Port', 'required|trim');
            $rules->set_rules('realm_allow_level', 'Allow Lever', 'required|trim');
            $rules->set_rules('ra_port', 'RA Port', 'required|trim');
            $rules->set_rules('ra', 'RA', 'required|trim');
            $rules->set_rules('soap_port', 'Soap Port', 'required|trim');
            $rules->set_rules('soap', 'Soap', 'required|trim');
            $rules->set_rules('char_db', 'Title', 'required|trim');
            $rules->set_rules('world_db', 'Title', 'required|trim');
            $rules->set_rules('p_limit', 'Content', 'required|trim');
            $rules->set_rules('unstuck_price', 'Unstuck Price', 'required|trim');
            $rules->set_rules('unstuck', 'Unstuck', 'required|trim');
            $rules->set_rules('unstuck_price_type', 'Unstuck Price Type', 'required|trim');
            $rules->set_rules('teleport_price', 'Teleport Price', 'required|trim');
            $rules->set_rules('teleport', 'Teleport', 'required|trim');
            $rules->set_rules('teleport_price_type', 'Teleport Price Type', 'required|trim');
            $rules->set_rules('change_race_price', 'Change Race Price', 'required|trim');
            $rules->set_rules('changes', 'Changes', 'required|trim');
            $rules->set_rules('change_race_price_type', 'Change Race Price Type', 'required|trim');
            $rules->set_rules('change_faction_price', 'Change Faction Price', 'required|trim');
            $rules->set_rules('change_faction_price_type', 'Change Faction Price Type', 'required|trim');
            $rules->set_rules('change_appearance_price', 'Change Appearance Price', 'required|trim');
            $rules->set_rules('change_appearance_price_type', 'Change Appearance Price', 'required|trim');
            $rules->set_rules('3d_char_preview', '3d Character Preview', 'required|trim');
			$rules->set_rules('core', 'Core', 'required|trim');
            
            $id = $this->input->post('realm_id');
            
            if ($rules->run() == TRUE)
            {
                $this->load->model('acp_realms');
                $this->acp_realms->update_realm($id,
                        $this->input->post('realm_name'),
                        $this->input->post('realm_address'),
                        $this->input->post('realm_port'),
                        $this->input->post('realm_allow_level'),
                        $this->input->post('ra'),
                        $this->input->post('ra_port'),
                        $this->input->post('soap'),
                        $this->input->post('soap_port'),
                        $this->input->post('char_db'),
                        $this->input->post('world_db'),
                        $this->input->post('p_limit'),
                        $this->input->post('unstuck'),
                        $this->input->post('teleport'),
                        $this->input->post('changes'),
                        $this->input->post('3d_char_preview'),
                        $this->input->post('unstuck_price').'-'.$this->input->post('unstuck_price_type'),
                        $this->input->post('teleport_price').'-'.$this->input->post('teleport_price_type'),
                        $this->input->post('change_faction_price').'-'.$this->input->post('change_faction_price_type'),
                        $this->input->post('change_race_price').'-'.$this->input->post('change_race_price_type'),
                        $this->input->post('change_appearance_price').'-'.$this->input->post('change_appearance_price_type'),
						$this->input->post('core'));
                $this->auto->logging('Edited Realm ID: '.$id, 'realms');
                redirect('index.php/acp/realms', 'refresh');
            }
            else
            {
                $this->session->set_flashdata('not_filled', "<div class='fail'><span class='ico_cancel'>Please fill every field.</span></div>");
                redirect('index.php/acp/realms/index/edit/'.$id, 'refresh');
            }
        }
        function add_validation()
        {
            $rules = $this->form_validation;
            $rules->set_rules('realm_name', 'Name', 'required|trim');
            $rules->set_rules('realm_address', 'Adress', 'required|trim');
            $rules->set_rules('realm_port', 'Port', 'required|trim');
            $rules->set_rules('realm_allow_level', 'Allow Lever', 'required|trim');
            $rules->set_rules('ra_port', 'RA Port', 'required|trim');
            $rules->set_rules('ra', 'RA', 'required|trim');
            $rules->set_rules('soap_port', 'Soap Port', 'required|trim');
            $rules->set_rules('soap', 'Soap', 'required|trim');
            $rules->set_rules('char_db', 'Title', 'required|trim');
            $rules->set_rules('world_db', 'Title', 'required|trim');
            $rules->set_rules('p_limit', 'Content', 'required|trim');
            $rules->set_rules('unstuck', 'Unstuck', 'required|trim');
            $rules->set_rules('unstuck_price_type', 'Unstuck Price Type', 'required|trim');
            $rules->set_rules('teleport', 'Teleport', 'required|trim');
            $rules->set_rules('teleport_price_type', 'Teleport Price Type', 'required|trim');
            $rules->set_rules('changes', 'Changes', 'required|trim');
            $rules->set_rules('change_race_price_type', 'Change Race Price Type', 'required|trim');
            $rules->set_rules('change_faction_price_type', 'Change Faction Price Type', 'required|trim');
            $rules->set_rules('change_appearance_price_type', 'Change Appearance Price', 'required|trim');
            $rules->set_rules('3d_char_preview', '3d Character Preview', 'required|trim');
			$rules->set_rules('core', 'Core', 'required|trim');
            
            if ($rules->run() == TRUE)
            {
                $this->load->model('acp_realms');
                $this->acp_realms->add_realm(
                        $this->input->post('realm_name'),
                        $this->input->post('realm_address'),
                        $this->input->post('realm_port'),
                        $this->input->post('realm_allow_level'),
                        $this->input->post('ra'),
                        $this->input->post('ra_port'),
                        $this->input->post('soap'),
                        $this->input->post('soap_port'),
                        $this->input->post('char_db'),
                        $this->input->post('world_db'),
                        $this->input->post('p_limit'),
                        $this->input->post('unstuck'),
                        $this->input->post('teleport'),
                        $this->input->post('changes'),
                        $this->input->post('3d_char_preview'),
                        $this->input->post('unstuck_price').'-'.$this->input->post('unstuck_price_type'),
                        $this->input->post('teleport_price').'-'.$this->input->post('teleport_price_type'),
                        $this->input->post('change_faction_price').'-'.$this->input->post('change_faction_price_type'),
                        $this->input->post('change_race_price').'-'.$this->input->post('change_race_price_type'),
                        $this->input->post('change_appearance_price').'-'.$this->input->post('change_appearance_price_type'),
						$this->input->post('core'));
                $this->auto->logging('Added Realm', 'realms');
                redirect('index.php/acp/realms', 'refresh');
            }
            else
            {
                $this->session->set_flashdata('not_filled', "<div class='fail'><span class='ico_cancel'>Please fill every field.</span></div>");
                redirect('index.php/acp/realms/index/add');
            }
        }
}