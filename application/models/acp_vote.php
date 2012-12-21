<?php
class Acp_vote extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->cms = $this->load->database('default', TRUE);  
        }
        
        function return_vote_items($id)
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->order_by('id', 'asc'); 
            $this->cms->where('realm', $id);
            $query = $this->cms->get('vote_rewards');
            $cont = '';
            foreach ($query->result_array() as $row)
            {
                $cont .= '<div class="menage_news"><div class="content"><div class="news_id">'.$row['id'].'</div><div class="news_title"><a href="http://www.wowhead.com/item='.$row['entry'].'&xml" class="q'.$row['quality'].'">'.$row['name'].'</a></div><div class="news_options"><a class="edit" href="'.base_url('index.php/acp/vote/index/edit/'.$row['id']).'">Edit</a> - <a class="delete" href="'.base_url('index.php/acp/vote/index/delete/'.$row['id']).'">Delete</a></div><div class="clear"></div></div></div>';
            }
            
            return $cont;
        }
        
        function return_vote_item($id)
        {
            $this->cms = $this->load->database('default', TRUE); 
            $this->cms->where('id', $id);
            $query = $this->cms->get('vote_rewards');
            $cont = '';
            foreach ($query->result_array() as $row)
            {
                $hidden = array('vote_reward_id' => $row['id']);
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
                $cont .= form_open('index.php/acp/vote/edit_validation', '', $hidden);
                $cont .= "<table style='width:100%;'><tr><td><label for=''>For Realmlist</label><br />".form_dropdown('vote_reward_realmlist', $this->fill_drop_down(), $row['realm'],  'id="vote_reward_realmlist"');
                $data = array(
                    'name'        => 'vote_reward_name',
                    'id'          => 'vote_reward_name',
                    'value'       => $row['name'],
                    );
                $cont .= '</td><td><label for="vote_reward_name">Name</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'vote_reward_entry',
                    'id'          => 'vote_reward_entry',
                    'value'       => $row['entry'],
                    'style'       => ''
                    );
                $cont .= '</td><td><label for="vote_reward_entry">Entry</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'vote_reward_description',
                    'id'          => 'vote_reward_description',
                    'value'       => $row['description'],
                    'style'       => ''
                    );
                $cont .= '</td></tr><tr><td><label for="vote_reward_description">Description</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'vote_reward_points',
                    'id'          => 'vote_reward_points',
                    'value'       => $row['points'],
                    'style'       => ''
                    );
                $cont .= '</td><td><label for="vote_reward_points">Points</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'vote_reward_quantity',
                    'id'          => 'vote_reward_quantity',
                    'value'       => $row['quantity'],
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
                $cont .= '</td></tr><tr><td><label for="">Quality</label><br />'.form_dropdown('vote_reward_quality', $data_quality, $row['quality'], 'id="vote_reward_quality"').'</td><td><label for="">Custom</label><br />'.form_dropdown('vote_reward_custom', $data_custom, $row['custom']).'</td><td><br />'.form_submit($data);
                $cont .= form_close('</td></tr></table>');
            }
            
            return $cont;
        }
        
        function update_vote_reward($id, $realm_id, $entry, $name, $description, $points, $quality, $quantity, $custom)
        {
            $this->cms = $this->load->database('default', TRUE); 
            $this->cms->where('id', $id);
            $data = array(
                'realm'        => $realm_id,
                'entry'          => $entry,
                'name'        => $name,
                'description'          => $description,
                'points'        => $points,
                'quality'          => $quality,
                'quantity'        => $quantity,
                'custom'          => $custom,
            );
            $query = $this->cms->update('vote_rewards',$data);
        }
        
        function add_vote_reward($realm_id, $entry, $name, $description, $points, $quality, $quantity, $custom)
        {
            $this->cms = $this->load->database('default', TRUE); 
            $data = array(
                'realm'        => $realm_id,
                'entry'          => $entry,
                'name'        => $name,
                'description'          => $description,
                'points'        => $points,
                'quality'          => $quality,
                'quantity'        => $quantity,
                'custom'          => $custom,
            );
            $query = $this->cms->insert('vote_rewards',$data);
        }
        
        function delete_vote_reward($id)
        {
            $this->cms->where('id', $id);
            $query = $this->cms->delete('vote_rewards');
        }
        
        function fill_drop_down()
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->select('id, name');
            $query = $this->auth->get('realmlist');

            $data[0] = 'Choose Realmlist';
            if ($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $data[$row['id']] = $row['name'];
                }
            }
            return $data;
        }
}
?>