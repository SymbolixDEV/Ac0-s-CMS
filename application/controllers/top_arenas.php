<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Top_arenas extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
        }        
        
        public function index($type='2', $realm='1')
		{
			if(!is_numeric($realm) || $realm<=0 || !is_numeric($type))
				redirect('home');
				
			$arena_teams = array(2 => '2v2', 3 => '3v3', 5 => '5v5');
			
            $this->load->model('tools_model');
            $data['title'] = $this->lang->line('top_arena_teams').' '.$arena_teams[$type];
            $data['content'] = $this->tools_model->show_realms($realm, 'index.php/top_arenas/index/'.$type.'/').$this->show_top_arenas($realm, $type);
            $this->load->view('main_view', $data);
		}       
        
        function show_top_arenas($realm, $type)
        {
            $this->load->model('tools_model');
            $char_db = $this->tools_model->select_realm_char_db($realm);
			$core = $this->tools_model->select_realm_core($realm);
            return $this->center_content($this->tools_model->show_top_arenas($char_db, $core, $type), $realm, $core);
        }
        
        function center_content($players, $realm, $core)
        {
			if($core == 'trinity')
				$cont = '
                        <table width="100%">
                        <tr>
                            <th></th>
                            <th class="aleft">Team Name</th>
                            <th class="aleft">Captain</th>
                            <th class="aleft">Rating</th>
                            <th class="aleft">Combo</th>
							<th class="aleft">MMR</th>
							<th class="aleft">Win</th>
							<th class="aleft">Lose</th>
                        </tr>';
			elseif($core == 'oregon')
				$cont = '
                        <table width="100%">
                        <tr>
                            <th></th>
                            <th class="aleft">Team Name</th>
                            <th class="aleft">Captain</th>
                            <th class="aleft">Rating</th>
                            <th class="aleft">Combo</th>
							<th class="aleft">Win</th>
							<th class="aleft">Lose</th>
                        </tr>';
            foreach($players as $player)
			{
                $cont .= '<tr>
                            <td>'.$player['arena_number'].'</td>
                            <td>'.$player['arena_name'].'</a></td>
                            <td><a href="'.base_url('index.php/character/index/'.$player['arena_captain_guid'].'/'.$realm).'" onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, \''.$player['arena_captain_name'].' profile\');" >'.$player['arena_captain_name'].'</a></td>
                            <td>'.$player['arena_rating'].'</td>
							<td>';
							foreach ($player['arena_combo'] as $member)
								$cont .= '<a href="'.base_url('index.php/character/index/'.$member['guid'].'/'.$realm).'">'.$member['icon'].'</a> &nbsp; ';
							
							$cont .= '</td>';
							if($core == 'trinity')
								$cont .= '<td>'.$player['arena_mmr'].'</td>';
								
							$cont .= '
							<td>'.$player['arena_wins'].'</td>
							<td>'.$player['arena_loses'].'</td>
                        </tr>';
			}
			if(sizeof($players)==0)
				$cont .= '<td colspan="8" class="aleft"><div class="warning"><span class="ico_warning">No arena teams found.</span></div></td>';
            $cont .= '</table>';
            
            return $cont;
        }
}
?>
