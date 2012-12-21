<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Top_killers extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
        }        
        
        public function index($realm='1')
		{
			if(!is_numeric($realm) || $realm<=0)
				redirect('home');
			
            $this->load->model('tools_model');
            $data['title'] = $this->lang->line('top_killers');
            $data['content'] = $this->tools_model->show_realms($realm, 'index.php/top_killers/index/').$this->show_top_killers($realm);
            $this->load->view('main_view', $data);
		}       
        
        function show_top_killers($realm)
        {
            $this->load->model('tools_model');
            $char_db = $this->tools_model->select_realm_char_db($realm);
            return $this->center_content($this->tools_model->show_top_killers($char_db), $realm);
        }
        
        function center_content($players, $realm)
        {
            $cont = '
                        <table width="100%">
                        <tr>
                            <th></th>
                            <th class="aleft">Character</th>
                            <th class="aleft">Level</th>
                            <th></th>
                            <th class="aleft">Today Kills</th>
                            <th class="aleft">Total Kills</th>
                        </tr>';
            foreach($players as $player)
                $cont .= '<tr>
                            <td>'.$player['player_number'].'</td>
                            <td><a href="'.base_url('index.php/character/index/'.$player['player_guid'].'/'.$realm).'" onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, \''.$player['player_name'].' profile\');" >'.$player['player_name'].'</a></td>
                            <td>'.$player['player_level'].' lvl</td>
                            <td>'.$player['player_icons'].'</td>
							<td>'.$player['player_today_kills'].'</td>
                            <td>'.$player['player_total_kills'].'</td>
                        </tr>';
			if(sizeof($players)==0)
				$cont .= '<td colspan="6" class="aleft"><div class="warning"><span class="ico_warning">No players found.</span></div></td>';
            $cont .= '</table>';
            
            return $cont;
        }
}
?>
