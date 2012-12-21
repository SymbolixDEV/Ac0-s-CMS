<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vote extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
            
        }
        
	public function index($page = 'show', $id='')
	{
            $data['title'] = "Vote Rewards";
            $data['ui'] = ucfirst($page);
            $data['left_content'] = $this->auto->pages($page, array('show', 'add', 'logs'), 'Vote Rewards');
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
                redirect('index.php/acp/vote/show/' . $city, 'refresh');
            }
            else
            {
                redirect('index.php/acp/vote', 'refresh');
            }
        }
        
        function show_right_content($page, $id)
        {
            if($page=='edit' && $id=='' || $page=='delete' && $id=='')
            {
                $this->index();
                return;
            }
            $this->load->model('acp_vote');
            $cont = '';
            switch($page)
            {
                case "logs":
                    $cont .= "<table width='100%' border = '#000000'>
                                <tr><td>Account</td><td>Action</td><td>Date</td></tr>";
                    foreach($this->auto->return_log('vote') as $row)
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
						var realm_id = $("#vote_reward_realmlist").val();
						$("#vote_reward_realmlist").change(function(){
							realm_id = $(this).val();
						});
						setAutoComplete("vote_reward", "results", "'.base_url().'index.php/acp/ajax/show_items/" + realm_id + "/?term=");
					});
                    </script>';
                    $cont .= form_open('index.php/acp/vote/add_validation');
                    $cont .= "<table style='width:100%;'><tr><td><label for=''>For Realmlist</label><br />".form_dropdown('vote_reward_realmlist', $this->acp_vote->fill_drop_down(), '', 'id="vote_reward_realmlist"');
                    $data = array(
                        'name'        => 'vote_reward_name',
                        'id'          => 'vote_reward_name',
                        'value'       => '',
                        );
                    $cont .= '</td><td><label for="vote_reward_name">Name</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'vote_reward_entry',
                        'id'          => 'vote_reward_entry',
                        'value'       => '',
                        'style'       => ''
                        );
                    $cont .= '</td><td><label for="vote_reward_entry">Entry</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'vote_reward_description',
                        'id'          => 'vote_reward_description',
                        'value'       => '',
                        'style'       => ''
                        );
                    $cont .= '</td></tr><tr><td><label for="vote_reward_description">Description</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'vote_reward_points',
                        'id'          => 'vote_reward_points',
                        'value'       => '',
                        'style'       => ''
                        );
                    $cont .= '</td><td><label for="vote_reward_points">Points</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'vote_reward_quantity',
                        'id'          => 'vote_reward_quantity',
                        'value'       => '',
                        'style'       => ''
                        );
                    $cont .= '</td><td><label for="vote_reward_quantity">Quantity</label>: <br />'.form_input($data);
                    $data = array(
                        'name'        => 'vote_reward_edit_submit',
                        'id'          => 'vote_reward_edit_submit',
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
                    $cont .= '</td></tr><tr><td><label for="">Quality</label><br />'.form_dropdown('vote_reward_quality', $data_quality, '', 'id="vote_reward_quality"').'</td><td><label for="">Custom</label><br />'.form_dropdown('vote_reward_custom', $data_custom).'</td><td><br />'.form_submit($data);
                    $cont .= form_close('</td></tr></table>');
                    
                case "edit":
                    $cont .= $this->acp_vote->return_vote_item($id);
                    break;
                case "delete":
                    $this->acp_vote->delete_vote_reward($id);
                    $this->auto->logging('Deleted Vote Reward ID: '.$id, 'vote');
                    redirect('acp/vote');
                    break;
                default :
                    $cont .= form_open('index.php/acp/vote/show_validation');
                    $cont .= $this->session->flashdata('console_status');
                    $cont .= "<label for=''>Realmlist</label></td><td>".form_dropdown('realmlist', $this->acp_vote->fill_drop_down(), $id)."<input type='submit' name='submit' id='submit' value='Go' />";
                    $cont .= form_close(); 
                    if($id != '')
                    {
                        $cont .= $this->acp_vote->return_vote_items($id);
                    }
                    break;
            }
            
            return $cont;
        }
        
        function edit_validation()
        {
            $rules = $this->form_validation;
            $rules->set_rules('vote_reward_id', 'ID', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('vote_reward_realmlist', 'Realmlist', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('vote_reward_name', 'Name', 'required|alpha_dash|trim');
            $rules->set_rules('vote_reward_entry', 'Entry', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('vote_reward_description', 'Description', 'alpha_dash|trim');
            $rules->set_rules('vote_reward_points', 'Points', 'required|integer|is_natural|trim');
            $rules->set_rules('vote_reward_quantity', 'Quantity', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('vote_reward_quality', 'Quality', 'greater_than[-1]|less_than[8]|integer|is_natural|trim');
            $rules->set_rules('vote_reward_custom', 'Custom', 'greater_than[-1]|less_than[2]|integer|is_natural|trim');
            
            $id = $this->input->post('vote_reward_id');
            $realm_id = $this->input->post('vote_reward_realmlist');
            
            if ($rules->run() == TRUE)
            {
                $name = $this->input->post('vote_reward_name');
                $entry = $this->input->post('vote_reward_entry');
                $description = $this->input->post('vote_reward_description');
                $points = $this->input->post('vote_reward_points');
                $quantity = $this->input->post('vote_reward_quantity');
                $quality = $this->input->post('vote_reward_quality');
                $custom = $this->input->post('vote_reward_custom');
                
                $this->load->model('acp_vote');
                $this->acp_vote->update_vote_reward($id, $realm_id, $entry, $name, $description, $points, $quality, $quantity, $custom);
                $this->auto->logging('Edited Vote Reward ID: '.$id, 'vote');
                redirect('acp/vote/show/'.$realm_id, 'refresh');
            }
            else
            {
                $this->session->set_flashdata('not_filled', "<div class='fail'><span class='ico_cancel'>Please fill every field.</span></div>");
                redirect('acp/vote/index/edit/'.$id, 'refresh');
            }
        }
        
        function add_validation()
        {
            $rules = $this->form_validation;
            $rules->set_rules('vote_reward_realmlist', 'Realmlist', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('vote_reward_name', 'Name', 'required|alpha_dash|trim');
            $rules->set_rules('vote_reward_entry', 'Entry', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('vote_reward_description', 'Description', 'alpha_dash|trim');
            $rules->set_rules('vote_reward_points', 'Points', 'required|integer|is_natural|trim');
            $rules->set_rules('vote_reward_quantity', 'Quantity', 'required|greater_than[0]|integer|is_natural|trim');
            $rules->set_rules('vote_reward_quality', 'Quality', 'greater_than[-1]|less_than[8]|integer|is_natural|trim');
            $rules->set_rules('vote_reward_custom', 'Custom', 'greater_than[-1]|less_than[2]|integer|is_natural|trim');
            
            $realm_id = $this->input->post('vote_reward_realmlist');
            
            if ($rules->run() == TRUE)
            {
                $name = $this->input->post('vote_reward_name');
                $entry = $this->input->post('vote_reward_entry');
                $description = $this->input->post('vote_reward_description');
                $points = $this->input->post('vote_reward_points');
                $quantity = $this->input->post('vote_reward_quantity');
                $quality = $this->input->post('vote_reward_quality');
                $custom = $this->input->post('vote_reward_custom');
                
                $this->load->model('acp_vote');
                $this->acp_vote->add_vote_reward($realm_id, $entry, $name, $description, $points, $quality, $quantity, $custom);
                $this->auto->logging('Added Vote Reward', 'vote');
                redirect('acp/vote/show/'.$realm_id, 'refresh');
            }
            else
            {
                $this->session->set_flashdata('not_filled', "<div class='fail'><span class='ico_cancel'>Please fill every field.</span></div>");
                redirect('acp/vote/index/add', 'refresh');
            }
        }
}

?>