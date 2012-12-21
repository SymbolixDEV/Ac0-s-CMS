<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Donate extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
            
        }
        
	public function index($page = 'show', $id='')
	{
            $data['title'] = "Donate Rewards";
            $data['ui'] = ucfirst($page);
            $data['left_content'] = $this->auto->pages($page, array('show', 'add', 'logs'), 'Donate Rewards');
            $data['right_content'] = $this->show_right_content($page, $id);
            $this->load->view('acp/main_view', $data);
	}
        
        public function show($id='')
        {
            $this->index('show', $id);
        }
        
        public function show_validation()
        {
            $rules = $this->form_validation;
            $rules->set_rules('realmlist', 'Realmlist', 'greater_than[0]|integer|is_natural');

            if ($rules->run())
            {
                $city = $this->input->post('realmlist');
                redirect('index.php/acp/donate/show/' . $city, 'refresh');
            }
            else
            {
                redirect('index.php/acp/donate', 'refresh');
            }
        }
        
        function show_right_content($page, $id)
        {
            if($page=='edit' && $id=='' || $page=='delete' && $id=='')
            {
                $this->index();
                return;
            }
            $this->load->model('acp_donate');
            $cont = '';
            switch($page)
            {
                case "logs":
                    $cont .= "<table width='100%' border = '#000000'>
                                <tr><td>Account</td><td>Action</td><td>Date</td></tr>";
                    foreach($this->auto->return_log('donate') as $row)
                    {
                        $cont .= "<tr><td>".$row['account']."</td><td>".$row['comment']."</td><td>".$row['date']."</td></tr>";
                    }
                    $cont .= "</table>";
                    break;
                case "add":
                    $cont .= $this->session->flashdata('not_filled');
					$cont .= '
						<script>
						$(function(){
							var realm_id = $("#donate_reward_realmlist").val();
							$("#donate_reward_realmlist").change(function(){
								realm_id = $(this).val();
							});
							setAutoComplete("donate_reward", "results", "'.base_url().'index.php/acp/ajax/show_items/" + realm_id + "/?term=");
						});
						</script>';
                    $cont .= form_open('index.php/acp/donate/add_validation');
                    $cont .= "<table style='width:100%;'><tr><td><label for=''>For Realmlist</label><br />".form_dropdown('donate_reward_realmlist', $this->acp_donate->fill_drop_down(), '', 'id="donate_reward_realmlist"');
                    $data = array(
                        'name'        => 'donate_reward_name',
                        'id'          => 'donate_reward_name',
                        'value'       => '',
                        );
                    $cont .= '</td><td><label for="donate_reward_name">Name</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'donate_reward_entry',
                        'id'          => 'donate_reward_entry',
                        'value'       => '',
                        'style'       => ''
                        );
                    $cont .= '</td><td><label for="donate_reward_entry">Entry</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'donate_reward_description',
                        'id'          => 'donate_reward_description',
                        'value'       => '',
                        'style'       => ''
                        );
                    $cont .= '</td></tr><tr><td><label for="donate_reward_description">Description</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'donate_reward_points',
                        'id'          => 'donate_reward_points',
                        'value'       => '',
                        'style'       => ''
                        );
                    $cont .= '</td><td><label for="donate_reward_points">Points</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'donate_reward_quantity',
                        'id'          => 'donate_reward_quantity',
                        'value'       => '',
                        'style'       => ''
                        );
                    $cont .= '</td><td><label for="donate_reward_quantity">Quantity</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'donate_reward_edit_submit',
                        'id'          => 'donate_reward_edit_submit',
                        'value'       => 'Submit',
                        );
                    $data_quality = array(
                        '0' => 'Poor',
                        '1' => 'Common',
                        '2' => 'Uncommon',
                        '3' => 'Rare',
                        '4' => 'Epic',
                        '5' => 'Legendary',
                        '6' => 'Artifact',
                        '7' => 'Bind to Account'
                        );
                    $data_custom = array(
                        '0' => 'No',
                        '1' => 'Yes'
                        );
                    $cont .= '</td></tr><tr><td><label for="">Quality</label><br />'.form_dropdown('donate_reward_quality', $data_quality, '', 'id="donate_reward_quality"').'</td><td><label for="">Custom</label><br />'.form_dropdown('donate_reward_custom', $data_custom).'</td><td><br />'.form_submit($data);
                    $cont .= form_close('</td></tr></table>');
                    
                case "edit":
                    $cont .= $this->acp_donate->return_donate_item($id);
                    break;
                case "delete":
                    $this->acp_donate->delete_donate_reward($id);
                    $this->auto->logging('Deleted Donate Reward ID: '.$id, 'donate');
                    redirect('index.php/acp/donate');
                    break;
                default :
                    $cont .= form_open('index.php/acp/donate/show_validation');
                    $cont .= $this->session->flashdata('console_status');
                    $cont .= "<label for=''>Realmlist</label></td><td>".form_dropdown('realmlist', $this->acp_donate->fill_drop_down(), $id)."<input type='submit' name='submit' id='submit' value='Go' />";
                    $cont .= form_close(); 
                    if($id != '')
                    {
                        $cont .= $this->acp_donate->return_donate_items($id);
                    }
                    break;
            }
            
            return $cont;
        }
        
        function edit_validation()
        {
            $rules = $this->form_validation;
            $rules->set_rules('donate_reward_id', 'ID', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('donate_reward_realmlist', 'Realmlist', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('donate_reward_name', 'Name', 'required|alpha_dash|trim');
            $rules->set_rules('donate_reward_entry', 'Entry', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('donate_reward_description', 'Description', 'alpha_dash|trim');
            $rules->set_rules('donate_reward_points', 'Points', 'required|integer|is_natural|trim');
            $rules->set_rules('donate_reward_quantity', 'Quantity', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('donate_reward_quality', 'Quality', 'greater_than[-1]|less_than[8]|integer|is_natural|trim');
            $rules->set_rules('donate_reward_custom', 'Custom', 'greater_than[-1]|less_than[2]|integer|is_natural|trim');
            
            $id = $this->input->post('donate_reward_id');
            $realm_id = $this->input->post('donate_reward_realmlist');
            
            if ($rules->run() == TRUE)
            {
                $name = $this->input->post('donate_reward_name');
                $entry = $this->input->post('donate_reward_entry');
                $description = $this->input->post('donate_reward_description');
                $points = $this->input->post('donate_reward_points');
                $quantity = $this->input->post('donate_reward_quantity');
                $quality = $this->input->post('donate_reward_quality');
                $custom = $this->input->post('donate_reward_custom');
                
                $this->load->model('acp_donate');
                $this->acp_donate->update_donate_reward($id, $realm_id, $entry, $name, $description, $points, $quality, $quantity, $custom);
                $this->auto->logging('Edited donate Reward ID: '.$id, 'donate');
                redirect('index.php/acp/donate/show/'.$realm_id, 'refresh');
            }
            else
            {
                $this->session->set_flashdata('not_filled', "<div class='fail'><span class='ico_cancel'>Please fill every field.</span></div>");
                redirect('index.php/acp/donate/index/edit/'.$id, 'refresh');
            }
        }
        
        function add_validation()
        {
            $rules = $this->form_validation;
            $rules->set_rules('donate_reward_realmlist', 'Realmlist', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('donate_reward_name', 'Name', 'required|alpha_dash|trim');
            $rules->set_rules('donate_reward_entry', 'Entry', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('donate_reward_description', 'Description', 'alpha_dash|trim');
            $rules->set_rules('donate_reward_points', 'Points', 'required|integer|is_natural|trim');
            $rules->set_rules('donate_reward_quantity', 'Quantity', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('donate_reward_quality', 'Quality', 'greater_than[-1]|less_than[8]|integer|is_natural|trim');
            $rules->set_rules('donate_reward_custom', 'Custom', 'greater_than[-1]|less_than[2]|integer|is_natural|trim');
            
            $realm_id = $this->input->post('donate_reward_realmlist');
            
            if ($rules->run() == TRUE)
            {
                $name = $this->input->post('donate_reward_name');
                $entry = $this->input->post('donate_reward_entry');
                $description = $this->input->post('donate_reward_description');
                $points = $this->input->post('donate_reward_points');
                $quantity = $this->input->post('donate_reward_quantity');
                $quality = $this->input->post('donate_reward_quality');
                $custom = $this->input->post('donate_reward_custom');
                
                $this->load->model('acp_donate');
                $this->acp_donate->add_donate_reward($realm_id, $entry, $name, $description, $points, $quality, $quantity, $custom);
                $this->auto->logging('Added donate Reward', 'donate');
                redirect('index.php/acp/donate/show/'.$realm_id, 'refresh');
            }
            else
            {
                $this->session->set_flashdata('not_filled', "<div class='fail'><span class='ico_cancel'>Please fill every field.</span></div>");
                redirect('index.php/acp/donate/index/add', 'refresh');
            }
        }
}

?>