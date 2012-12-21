<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Character extends CI_Controller {
    
        function index($id='', $realm_id='')
        {
            if(empty($id) || empty($realm_id))
            {
                redirect('');
            }
            elseif(is_numeric($id) && is_numeric($realm_id))
            {
                $data['title'] = "Character";
                $data['content'] = $this->show_character($id, $realm_id);
                $this->load->view('main_view', $data);
            }
            else
                redirect('');
        }
		
		function show_character($id, $realm_id)
        {
            $this->load->model('character_model');
            $realm_info = $this->character_model->get_realm($realm_id);
            if($this->character_model->check_character_exist($id, $realm_info['char_db'])==0)
                redirect('');
            $cont = '';
            $cont .= '<div style="width: 100%;padding:5px;">'.$this->character_model->show_character($id, $realm_info).'</div>';
        
            return $cont;
        }
		
		function character_change()
        {
            if($this->session->userdata('is_logged_session')==FALSE)
                redirect('');
            
            $return_link = $this->input->post('return_link');
            
            if($this->input->post('option')=='')
            {
                $this->session->set_flashdata('invalid_option', "<div class='warning'><span class='ico_warning'>Please first select an option.</span></div>");
                redirect($return_link);
            }
            $option = $this->input->post('option');
            
            if($this->input->post('char_id')=='' || $this->input->post('realm_id')=='')
                die('Hack Attemp! Character\'s Id and Realm Id are not set.');
            else
            {
                $char_id = $this->input->post('char_id');
                $realm_id = $this->input->post('realm_id');
            }
            
            $this->load->model('character_model');
            $realm_info = $this->character_model->get_realm($realm_id);
            
            if($this->character_model->check_character_exist($char_id, $realm_info['char_db'])!=2)
                    die('Hack Attemp! This character is not yours.');
            
            $cms_user_info =  $this->auto->membership($this->session->userdata('id'));
            
            $vp = $cms_user_info[0]['user_vp'];
            $dp = $cms_user_info[0]['user_dp'];
            
            $_price = explode('-', $realm_info[$option.'_price']);
            $price = ($realm_info[$option.'_price']=='0' || $_price[0]=='0') ? '0' : $_price[0];
            
            $cost_info = array('1' => 'Vote Points', '2' => 'VIP Points');
            if($price==0)
            {
                $type = '';
                $points = 0;
            }
            else
            {
                $type = ($_price[1]=='1') ? 'vp' : 'dp';
                $points = ($_price[1]=='1') ? $vp : $dp;
            }
            
            if($points<$price)
            {
                $this->session->set_flashdata('invalid_option', "<div class='fail'><span class='ico_cancel'>You dont have enough ".$cost_info[$_price[1]].".</span></div>");
                redirect($return_link);
            }
            
            $char_name = $this->character_model->get_character_name($char_id, $realm_info['char_db']);
            if($char_name=='')
                die('Hack Attemp! There is no character with that Id.');
            
            $new_points = $points - $price;
            
            if($option=='teleport')
            {
                if($this->input->post('destination')=='')
                {
                    $this->session->set_flashdata('invalid_option', "<div class='warning'><span class='ico_warning'>Please select destination to teleport.</span></div>");
                    redirect($return_link);
                }
                $destination = $this->input->post('destination');
                
                if($this->character_model->teleport($realm_info['char_db'], $char_id, $destination)==1)
                {
                    
                    $this->character_model->update_points($this->session->userdata('id'), $type, $new_points);
                    $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Your character ".$char_name." was successfuly teleported.</span></div>");
                    redirect($return_link);
                }
            }
            elseif($option=='unstuck')
            {
                if($this->character_model->unstuck($realm_info['char_db'], $char_id)==1)
                {
                    
                    $this->character_model->update_points($this->session->userdata('id'), $type, $new_points);
                    $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Your character ".$char_name." was successfuly unstucked.</span></div>");
                    redirect($return_link);
                }
            }
            elseif($option=='change_appearance')
            {
                $command = 'character customize '.$char_name;
                if($realm_info['ra']==1)
                {
                    if($this->character_model->ra_access($realm_info['ra_port'], $command)==1)
                    {
                        $this->character_model->update_points($this->session->userdata('id'), $type, $new_points); 
                        $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Your character ".$char_name." was tagged for changing appearance.</span></div>");
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
                    if($this->character_model->soap_access($realm_info['soap_port'], $command)==1)
                    {
                        $this->character_model->update_points($this->session->userdata('id'), $type, $new_points); 
                        $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Your character ".$char_name." was tagged for changing appearance.</span></div>");
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
			elseif($option=='change_faction')
            {
                $command = 'character changefaction '.$char_name;
                if($realm_info['ra']==1)
                {
                    if($this->character_model->ra_access($realm_info['ra_port'], $command)==1)
                    {
                        $this->character_model->update_points($this->session->userdata('id'), $type, $new_points); 
                        $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Your character ".$char_name." was tagged for changing faction.</span></div>");
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
                    if($this->character_model->soap_access($realm_info['soap_port'], $command)==1)
                    {
                        $this->character_model->update_points($this->session->userdata('id'), $type, $new_points); 
                        $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Your character ".$char_name." was tagged for changing faction.</span></div>");
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
			elseif($option=='change_race')
            {
                $command = 'character changerace '.$char_name;
                if($realm_info['ra']==1)
                {
                    if($this->character_model->ra_access($realm_info['ra_port'], $command)==1)
                    {
                        $this->character_model->update_points($this->session->userdata('id'), $type, $new_points); 
                        $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Your character ".$char_name." was tagged for changing race.</span></div>");
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
                    if($this->character_model->soap_access($realm_info['soap_port'], $command)==1)
                    {
                        $this->character_model->update_points($this->session->userdata('id'), $type, $new_points); 
                        $this->session->set_flashdata('invalid_option', "<div class='success'><span class='ico_accept'>Your character ".$char_name." was tagged for changing race.</span></div>");
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
        }
}