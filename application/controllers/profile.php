<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

		public function __construct() 
        {
            parent::__construct();
        } 
    
        function index($id='')
        {
            if(empty($id))
            {
                $data['title'] = "Profile";
                $data['content'] = ($this->auto->is_logged != TRUE) ? "Please login." : $this->profile_content();
                $this->load->view('main_view', $data);
            }
            elseif(is_numeric($id) && $id>0)
            {
                $this->load->model('profile_model');
                if($this->profile_model->check_auth_account_exist($id)!=1 || $this->profile_model->check_cms_account_exist($id)!=1)
                    redirect('');
                $data['title'] = "Profile";
                $data['content'] = $this->profile_content($id);
                $this->load->view('main_view', $data);
            }
            else
                redirect('');
        }       
        
        function change_settings()
        {
            if($this->auto->is_logged != TRUE)
            {
                $cont = "Please login.";
            }  
            else
            {
                $id = $this->session->userdata('id');
                $this->load->model('profile_model');
                $cms_userdata = $this->auto->membership($id);
                $submit = array('name' => 'submit', 'value' => 'Submit', 'class' => '');
				$input_location = array('name' => 'location', 'id' => 'location', 'class' => '', 'style' => 'width: 300px;', 'value' => $cms_userdata[0]['user_location']);
				$genders = array(
					0 => 'Male',
					1 => 'Female'
				);
				$cont = "";
                $cont .= $this->session->flashdata('change_nickname_status');
				$cont .= $this->session->flashdata('change_location_status');
				$cont .= $this->session->flashdata('change_gender_status');
                $cont .= form_open('index.php/profile/validate_settings');
                $cont .= '<center><table>
							<tr>
								<td><span class="member" title="Nickname">Nickname *</span></td>
								<td align="right">'.form_dropdown('characters', $this->profile_model->return_all_characters($id), $cms_userdata[0]['user_nickname'],'class="" style="width: 300px;"').'</td>
							</tr>
							<tr>
								<td><span class="gender_'.$cms_userdata[0]['user_gender'].'" title="Gender">Gender *</span></td>
								<td align="right">'.form_dropdown('gender', $genders, $cms_userdata[0]['user_gender'],'class="" style="width: 300px;"').'</td>
							</tr>
							<tr>
								<td><label for="location"><span class="location" title="Location">Location</span></label></td>
								<td>'.form_input($input_location).'</td>
							</tr>';
                $cont .= '<tr><td colspan="2" align="right">'.form_submit($submit).'</td></tr></table></center>';
                $cont .= form_close();
            }
            $data['title'] = "Settings";
            $data['content'] = $cont;
            $this->load->view('main_view', $data);
        }
		
		function change_expansion()
        {
            if($this->auto->is_logged != TRUE)
            {
                $cont = "Please login.";
            }  
            else
            {
                $id = $this->session->userdata('id');
                $this->load->model('profile_model');
				$expansions = array(
					'0' => 'Classic',
					'1' => '<font style="color: green">The Burning Crusade</font>',
					'2' => '<font style="color: blue">Wrath of the Lich King</font>',
					'3' => '<font style="color: red">Cataclysm</font>',
				);
				$auth_userdata = $this->profile_model->get_username($id);
                $submit = array('name' => 'submit', 'value' => 'Submit', 'class' => '');
                $cont = "";
                $cont .= $this->session->flashdata('change_expansion_status');
                $cont .= form_open('index.php/profile/validate_change_expansion');
                $cont .= '<center><table>
							<tr>
								<td><span class="expansion" title="Expansion">Expansion *</span></td>
								<td align="right">'.form_dropdown('expansion', $expansions, $auth_userdata['expansion'], 'class="" style="width: 300px;"').'</td>
							</tr>';
                $cont .= '<tr><td colspan="2" align="right">'.form_submit($submit).'</td></tr></table></center>';
                $cont .= form_close();
            }
            $data['title'] = "Change Expansion";
            $data['content'] = $cont;
            $this->load->view('main_view', $data);
        }
		
		function validate_change_expansion()
        {
            $rules = $this->form_validation;
            $rules->set_rules('expansion', "Expansion", 'required|numeric|trim');
            
            if ($rules->run() == TRUE)
            {
                $id = $this->session->userdata('id');
                $this->load->model('profile_model');
                if($this->profile_model->set_new_expansion($id, $this->input->post('expansion')) == 1)
                    $this->session->set_flashdata('change_expansion_status', "<div class='success'><span class='ico_accept'>Successfuly changed expansion.</span></div>");
            }
            else
                $this->session->set_flashdata('change_expansion_status', "<div class='warning'><span class='ico_warning'>You must fill correct every field.</span></div>");
				
			redirect('index.php/profile/change_expansion');
        }
        
        function validate_settings()
        {
            $rules = $this->form_validation;
            $rules->set_rules('characters', "Character", 'required|alpha_dash|trim');
			$rules->set_rules('gender', "Gender", 'required|numeric|trim');
			$rules->set_rules('location', "Location", 'alpha_dash|trim');
            
            if ($rules->run() == TRUE)
            {
                $id = $this->session->userdata('id');
                $this->load->model('profile_model');
                if($this->profile_model->set_new_nickname($id, $this->input->post('characters')) == 1)
                    $this->session->set_flashdata('change_nickname_status', "<div class='success'><span class='ico_accept'>Successfuly changed nikcname.</span></div>");
               
				if($this->profile_model->set_new_location($id, $this->input->post('location')) == 1)
                    $this->session->set_flashdata('change_location_status', "<div class='success'><span class='ico_accept'>Successfuly changed location.</span></div>");
				if($this->profile_model->set_new_gender($id, $this->input->post('gender')) == 1)
                    $this->session->set_flashdata('change_gender_status', "<div class='success'><span class='ico_accept'>Successfuly changed gender.</span></div>");
			}
            else
                $this->session->set_flashdata('change_location_status', "<div class='warning'><span class='ico_warning'>You must fill correct every field.</span></div>");
			
			redirect('index.php/profile/change_settings');
        }
        
        function donate()
        {
            if($this->auto->is_logged != TRUE)
            {
                $cont = "Please login.";
            }  
            else
            {
                $attributes_paypal_quantity = array('name' => 'quantity', 'id' => 'quantity', 'class' => '', 'style' => 'width: 300px;', 'value' => '1');
                $attributes_paypal_submit = array('name' => 'donate_submit', 'id' => 'donate_submit', 'class' => '', 'value' => "Donate");

                $cont = '';
                $cont .= '<center><h4>Donate with PayPal</h4>';
                    $hidden = array(
                        'cmd' => '_xclick',
                        'business' => $this->config->item('paypalemail'),
                        'item_name' => 'Donation Points',
                        'item_number' => $this->session->userdata('id'),
                        'amount' => '1.00',
                        'currency_code' => $this->config->item('paypalcurrecy'),
                        'notify_url' => base_url('postback.php')
                    );
                $cont .= form_open('https://'.$this->config->item('paypalurl').'/cgi-bin/webscr', '', $hidden);
                $cont .= form_input($attributes_paypal_quantity);
                $cont .= '<br>'.sprintf("%s point costs", 'One').' 1'.$this->config->item('paypalcurrecy_symbol');
                $cont .= '<br />'.form_submit($attributes_paypal_submit);
                $cont .= form_close().'</center>';
            }
            $data['title'] = "Donate";
            $data['content'] = $cont;
            $this->load->view('main_view', $data);
        }
        
        public function change_password()
        {
            if($this->auto->is_logged != TRUE)
            {
                $cont = "Please login.";
            }  
            else
            {
                $attributes_old_password = array('name' => 'change_password_old_password', 'id' => 'change_password_old_password', 'class' => '', 'style' => 'width: 300px;');
                $attributes_new_password = array('name' => 'change_password_new_password', 'id' => 'change_password_new_password', 'class' => '', 'style' => 'width: 300px;');
                $attributes_re_new_password = array('name' => 'change_password_re_new_password', 'id' => 'change_password_re_new_password', 'class' => '', 'style' => 'width: 300px;');
                $attributes_submit = array('name' => 'register_submit', 'id' => 'register_submit', 'class' => '', 'value' => "Change");
                $cont = '<center><br /><br /><table>';
                $cont .= form_open(base_url('index.php/profile/validation_change_password'));
                $cont .= $this->session->flashdata('change_password_status');
                $cont .= validation_errors("<tr><td colspan='2'><div class='warning'><span class='ico_warning'>", "</span></div></td></tr>");
                $cont .= '<tr><td class="aleft"><label style="cursor:pointer;" for="change_password_old_password">Old Password</label></td><td class="aleft">'.form_password($attributes_old_password).'</td></tr>
                <tr><td class="aleft"><label style="cursor:pointer;" for="change_password_new_password">New Password</label></td><td class="aleft">'.form_password($attributes_new_password).'</td></tr>
                <tr><td class="aleft"><label style="cursor:pointer;" for="change_password_re_new_password">New Password Confirm</label></td><td class="aleft">'.form_password($attributes_re_new_password).'</td></tr>
                <tr><td class="aleft"></td><td class="aright">'.form_submit($attributes_submit).'</td></tr>';
                $cont .= form_close().'</table></center>';
            }
            $data['title'] = "Change Password";
            $data['content'] = $cont;
            $this->load->view('main_view', $data);
        }
        
        function validation_change_password()
        {
            $rules = $this->form_validation;
            $rules->set_rules('change_password_old_password', "Old Password", 'required|min_length[6]|max_length[40]');
            $rules->set_rules('change_password_new_password', "New Password", 'required|min_length[6]|max_length[40]');
            $rules->set_rules('change_password_re_new_password', "New Password Confirm", 'required|matches[change_password_new_password]');
            
            if ($rules->run() == TRUE)
            {
                $this->load->model('profile_model');
                if($this->profile_model->check_account($this->session->userdata('username'), $this->input->post('change_password_old_password')) == 1)
                {
                    $this->profile_model->set_new_password($this->session->userdata('username'), $this->input->post('change_password_re_new_password'));
                    $this->session->set_flashdata('change_password_status', "<tr><td colspan='2'><div class='success'><span class='ico_accept'>Successfuly changed.</span></div></td></tr>");
                    redirect('index.php/profile/change_password');
                }
                else
                {
                    $this->session->set_flashdata('change_password_status', "<tr><td colspan='2'><div class='fail'><span class='ico_cancel'>Incorect old password.</span></div></td></tr>");
                    redirect('index.php/profile/change_password');
                }  
            }
            else
                $this->change_password();
        }
        
        public function vote_shop($realm_id='1')
        {
            if($this->auto->is_logged!= TRUE)
            {
                $cont = "Please login.";
            }  
            else
            {
                $this->load->model('profile_model');
                $cont = $this->profile_model->show_purchase($realm_id, 'vote');
            }
            if(is_numeric($realm_id) && $realm_id>0)
            {
                $data['title'] = "Vote Shop";
                $data['content'] = $cont;
                $this->load->view('main_view', $data);
            }
            else
                redirect('');
        }
        
        public function donate_shop($realm_id='1')
        {
            if($this->auto->is_logged != TRUE)
            {
                $cont = "Please login.";
            }  
            else
            {
                $this->load->model('profile_model');
                $cont = $this->profile_model->show_purchase($realm_id, 'donate');
            }
            if(is_numeric($realm_id) && $realm_id>0)
            {
                $this->load->model('profile_model');
                $data['title'] = "Donate Shop";
                $data['content'] = $cont;
                $this->load->view('main_view', $data);
            }
            else
                redirect('');
        }
        
        function purchase()
        {
            if($this->session->userdata('is_logged_session')==FALSE)
                redirect('');
            
            $rules = $this->form_validation;
            $rules->set_rules('realm_id', 'Realm Id', 'required|trim');
            $rules->set_rules('type', 'Type', 'required|trim');
            $rules->set_rules('return_link', 'Return Link', 'required|trim');
            $rules->set_rules('character', 'Character', 'required|alpha_dash|trim');
            $rules->set_rules('item_entry', 'Item', 'required|trim');
            
            $return_link = $this->input->post('return_link');
            
            if ($rules->run() == TRUE)
            {
                $text_type = array('vote' => 'vp', 'donate' => 'dp');
                $this->load->model('profile_model');
                $realm_info = $this->profile_model->get_realm($this->input->post('realm_id'));
                $type = $this->input->post('type');
                $item_info = $this->profile_model->return_rewards($this->input->post('realm_id'), $type, $this->input->post('item_entry'));
                $cms_user_info =  $this->auto->membership($this->session->userdata('id'));
            
                $vp = $cms_user_info[0]['user_vp'];
                $dp = $cms_user_info[0]['user_dp'];
                
                $points = ($type=='vote') ? $vp : $dp;
                
                if($points<$item_info['points'])
                {
                    $this->session->set_flashdata('invalid_option', "<div class='fail'><span class='ico_cancel'>You dont have enough ".$type.". points.</span></div>");
                    redirect($return_link);
                }
                
                $char_name = $this->input->post('character');                
                $new_points = $points - $item_info['points'];
                $type_points = $text_type[$type];
                
                $command = "send items $char_name \"Online Shop\" \"Thanks you. It's your reward\" {$item_info['entry']}[:{$item_info['quantity']}]";
                if($realm_info['ra']==1)
                {
                    if($this->profile_model->ra_access($realm_info['ra_port'], $command)==1)
                    {
                        $this->profile_model->update_points($this->session->userdata('id'), $text_type[$type], $new_points); 
                        $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Successfuly give ".$item_info['name']." to your character ".$char_name.".</span></div>");
                        redirect($return_link); 
                    }
                    else
                    {
                        $this->session->set_flashdata('invalid_option', "<div class='fail'><span class='ico_cancel'>Something is wrong with RA.</span></div>");
                        redirect($return_link);
                    }  
                }
                elseif($realm_info['soap']==1)
                {
                    if($this->profile_model->soap_access($realm_info['soap_port'], $command)==1)
                    {
                        $this->profile_model->update_points($this->session->userdata('id'), $text_type[$type], $new_points); 
                        $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Successfuly give ".$item_info['name']." to your character ".$char_name.".</span></div>");
                        redirect($return_link); 
                    }
                    else
                    {
                        $this->session->set_flashdata('invalid_option', "<div class='fail'><span class='ico_cancel'>Something is wrong with SOAP.</span></div>");
                        redirect($return_link);
                    } 
                }
                else
                {
                    $this->session->set_flashdata('invalid_option', "<div class='fail'><span class='ico_cancel'>No remote set.</span></div>");
                    redirect($return_link);
                }
            }
            else
                redirect($return_link);
        }
        
        function profile_content($id='')
        {
            $own = FALSE;
            if(empty($id))
            {
                $id = $this->session->userdata('id');
                $own = TRUE;
            }
            elseif($id == $this->session->userdata('id'))
                    $own = TRUE;
            
            $this->load->model('profile_model');
            
			$expansions = array(
				'0' => 'Classic',
				'1' => '<font style="color: green">The Burning Crusade</font>',
				'2' => '<font style="color: blue">Wrath of the Lich King</font>',
				'3' => '<font style="color: red">Cataclysm</font>',
			);
            $bann_status = array(
                '0' => '<font style="color:green">Active</font>',
                '1' => '<font style="color:red">Banned</font>'
            );
			$genders = array(
				0 => 'Male',
				1 => 'Female'
			);
            $cms_userdata = $this->auto->membership($id);
            $auth_userdata = $this->profile_model->get_username($id);
			
			$in_game_ranks_config = $this->config->item('in_game_ranks');
			$web_ranks_config = $this->config->item('web_ranks');
            
            $cont = '<table class="profile_holder" >
						<tr>
							<td class="avatar_holder" rowspan="8"><div class="avatar_content" onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, \'Comming Soon\');" style="cursor: pointer; background-image: url(\''.base_url('content/img/avatars/default-'.$cms_userdata[0]['user_gender'].'.jpg').'\');"></div></td>
							<td><span class="member" title="Nickname">Nickname</span></td>
							<td>'.$cms_userdata[0]['user_nickname'].'</td>
							<td style="width: 20px;">';
							$cont .= ($own) ? '<a href="'.base_url('index.php/profile/change_settings').'"><div class="edit" onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, \'Change Nickname\');"></div></a>' : '';
							$cont .= '</td>
							<td class="delimeter" rowspan="8"></td>
							<td><span class="in_game_rank" title="In-game Rank">In-Game Rank</span></td>
							<td>'.$in_game_ranks_config[$this->profile_model->get_account_access($id)][0].'</td>
						</tr>
						<tr>
							<td><span class="location" title="Location">Location</span></td>
							<td>'.$cms_userdata[0]['user_location'].'</td>
							<td>';
							$cont .= ($own) ? '<a href="'.base_url('index.php/profile/change_settings').'"><div class="edit" onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, \'Edit Location\');"></div></a>' : '';
							$cont .= '</td>
							<td><span class="reputation" title="Reputation">Reputation</span></td>
							<td>'.$cms_userdata[0]['user_reputation'].'</td>
						</tr>
						<tr>
							<td><span class="email" title="E-mail">E-mail</span></td>
							<td>'.$auth_userdata['email'].'</td>
							<td></td>
							<td><span class="posts" title="Posts">Posts</span></td>
							<td>'.$cms_userdata[0]['user_posts'].'</td>
						</tr>
						<tr>
							<td><span class="gender_'.$cms_userdata[0]['user_gender'].'" title="Gander">Gender</span></td>
							<td>'.$genders[$cms_userdata[0]['user_gender']].'</td>
							<td>';
							$cont .= ($own) ? '<a href="'.base_url('index.php/profile/change_settings').'"><div class="edit"  onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, \'Change Gender\');"></div></a>' : '';
							$cont .= '</td>
							<td><span class="status" title="Account Status">Account status</span></td>
							<td>'.$bann_status[$this->profile_model->check_account_bann($id)].'</td>
						</tr>
						<tr>
							<td><span class="expansion" title="Expansion">Expansion</span></td>
							<td>'.$expansions[$auth_userdata['expansion']].'</td>
							<td>';
							$cont .= ($own) ? '<a href="'.base_url('index.php/profile/change_expansion').'"><div class="change"  onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, \'Change Expansion\');"></div></a>' : '';
							$cont .= '</td>
							<td><span class="web_rank" title="Web Rank">Web Rank</span></td>
							<td>'.$web_ranks_config[$cms_userdata[0]['user_rank']][0].'</td>
						</tr>';
					
					if($own)
						$cont .= '<tr height="20px"></tr>
						<tr>
							<td><span class="last_ip" title="Last IP">Last IP</span></td>
							<td>'.$auth_userdata['last_ip'].'</td>
							<td></td>
							<td><span class="vote_points" title="Vote Points">Vote Points</span></td>
							<td>'.$cms_userdata[0]['user_vp'].'</td>
						</tr>
						<tr>
							<td><span class="last_login" title="Last Login">Last Login</span></td>
							<td>2012-07-04 14:40:07</td>
							<td></td>
							<td><span class="donate_points" title="Donate Points">Donate Points</span></td>
							<td>'.$cms_userdata[0]['user_dp'].'</td>
						</tr>';
				
				$cont .= '</table>';
					
			if($own)
				$cont .= '
					<br />
					<table class="profile_holder" style="text-align: center;">
						<tr>
							<td><a href="'.base_url('index.php/profile/change_password').'" class="cool_button"><span class="change_btn" title="Change Password">Change Password</span></a></td>
							<td><a href="'.base_url('index.php/profile/vote_shop').'" class="cool_button"><span class="vote_points" title="Vote Shop">Vote Shop</span></a></td>
							<td><a href="'.base_url('index.php/profile/donate_shop').'" class="cool_button"><span class="donate_points" title="Donate Shop">Donate Shop</span></a></td>
							<td><a href="'.base_url('index.php/profile/donate').'" class="cool_button"><span class="donate_points" title="Donate">Donate</span></a></td>
						</tr>
					</table>';
					
			$cont .= '<br /><div style="width: 100%;float: right;padding:5px;">';
			$cont .= $this->profile_model->show_characters($id,$own).'</div><span style="clear: both; display: block;"></span>';
			
			return $cont;
        }
}