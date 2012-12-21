<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
        }        
        
        public function index($realm='1', $page='0', $limit='50')
		{
			if(!is_numeric($realm) || $realm<=0 || !is_numeric($page) || $page<0 || !is_numeric($limit) || $limit<=0)
				redirect('');
			
            $this->load->model('status_model');
            $char_db = $this->status_model->select_realm_char_db($realm);
            if      ($page!='0') { $start = ($page - 1) * $limit; } 	
            else    { $start = 0; }
            $data['title'] = $this->lang->line('status');
            $data['content'] = $this->status_model->show_realms($realm, 'index.php/status/index/').$this->show_player_status($char_db).$this->show_online_players($realm, $page, $limit);
            $this->load->view('main_view', $data);
		}
        
        function show_player_status($char_db)
        {
            $seached_player = $this->session->flashdata('searched_player_name');
			
			$status = '';
			
            if(!empty($seached_player))
            {
                if($this->status_model->count_all_online_players($char_db, $seached_player)=='1')
                        $status = '<span title="'.ucfirst($seached_player).' is Online" class="ico_accept" style="padding-bottom: 3px;">&nbsp;</span>';
                else
                    $status = '<span title="'.ucfirst($seached_player).' is Offline" class="ico_cancel" style="padding-bottom: 3px;">&nbsp;</span>';
            }
			
			$attributes_search_player = array('name' => 'status_search_player', 'id' => 'status_search_player', 'class' => 'cool', 'value' => ($seached_player) ? ucfirst($seached_player) : "");
            $attributes_search_player_js = 'placeholder="Search Player..."';
        
            $cont = '';
            $cont .= form_open('index.php/status/search_player');
            $cont .= form_hidden('return_link', current_url());
            $cont .= '<center><span style="margin: 12px 0px 0px 0px;"><label for="status_search_player" style="cursor: pointer;"><strong>Search Player: </strong></label></span><span>'.form_input($attributes_search_player, '',$attributes_search_player_js).' '.$status.'</span></center><span style="clear: both; display: block;"></span>';
            $cont .= form_close();
            return $cont;
        }
        
        function search_player()
        {
            $rules = $this->form_validation;
            $rules->set_rules('status_search_player', "Player", 'required|alpha|trim');
            $return_link = $this->input->post('return_link');
            
            if ($rules->run() == TRUE)
            {
                $searched_player = $this->input->post('status_search_player'); 
                $this->session->set_flashdata('searched_player_name', $searched_player);
            }
            
            redirect($return_link);
        }
        
        function show_online_players($realm, $page, $limit)
        {
            if      ($page!='0') { $start = ($page - 1) * $limit; } 	
            else    { $start = 0; }
            $this->load->model('status_model');
            $char_db = $this->status_model->select_realm_char_db($realm);
            return $this->center_content($this->status_model->show_online_players($char_db, $start, $limit), $realm).$this->show_pages($realm, $page, $limit, $char_db);
        }
        
        function center_content($players, $realm)
        {
            $cont = '
                        <table width="100%">
                        <tr>
                            <th></th>
                            <th class="aleft">Character</th>
                            <th class="aleft">Zone</th>
                            <th class="aleft">Level</th>
                            <th></th>
                            <th class="aleft">Ping</th>
                        </tr>';
            foreach($players as $player)
                $cont .= '<tr>
                            <td>'.$player['player_number'].'</td>
                            <td><a href="'.base_url('index.php/character/index/'.$player['player_guid'].'/'.$realm).'" onmouseout="Tooltip.hide();" onmouseover="Tooltip.show(this, \''.$player['player_name'].' profile\');" >'.$player['player_name'].'</a></td>
                            <td>'.$player['player_map'].'</td>
                            <td>'.$player['player_level'].' lvl</td>
                            <td>'.$player['player_icons'].'</td>
                            <td>'.$player['player_latency'].'</td>
                        </tr>';
			if(sizeof($players)==0)
				$cont .= '<td colspan="6" class="aleft"><div class="warning"><span class="ico_warning">No players online.</span></div></td>';
            $cont .= '</table>';
            
            return $cont;
        }
        
        function show_pages($realm, $page, $limit, $char_db)
        {
            $this->load->model('status_model');
            $total_rows = $this->status_model->count_all_online_players($char_db);
			
			if($total_rows <= $limit)
				return;
			
            if($total_rows=='0')
                $total_rows='1';
            $cont = '';
            if($total_rows>'0')
            {
                if      ($page) { $start = ($page - 1) * $limit; } 
                else    { $start = 0; }
                if      ($page == 0) $page = 1;        
                if      ($page == 1) $prev = 1;
                else    $prev = $page - 1;              //previous page is page - 1
				$next = $page + 1;                      //next page is page + 1
                $lastpage = ceil($total_rows/$limit);   //lastpage is = total pages / items per page, rounded up.
                $lpm1 = $lastpage - 1;	
                $all_pages = ceil($total_rows/$limit);
                $cont = '<center style="margin:10px 0px 10px 0px;"><ol class="cool" style="list-style: none;display: inline;">';
                if($page!='1')
                {
                    $cont .= '<a class="left" href="'.base_url('index.php/status/index/'.$realm.'/'.$prev.'/'.$limit).'"><li style="display: inline;"><<<</li></a>';
				}
                else
				{
                    $cont .= '<a class="left"><li style="display: inline;"><<<</li></a>';
				}
                if($page>3)
				{
                    $i=0;
                    $br=0;
                    while($i<$page-3 && $br<3)
                    {
                        $br++;
                        $i++;
                        $cont .= '<a href="'.base_url('index.php/status/index/'.$realm.'/'.$i.'/'.$limit).'"><li style="display: inline;"> '.$i.' </li></a>';
                    }
                    if($all_pages>6 && $page>6)
                        $cont .='<a><li style="display: inline;">...</li></a>';
                }
			
                $i = 0;
                if($all_pages<4) { $br = $all_pages; }
                else 
                {
                    $br = $page+2; 
                    if($page>3)
					$i=$page-3;   
				}
                if($all_pages<=5) { $page_counter_middle = $all_pages; }
                else { $page_counter_middle = $all_pages-2; }
			
                while($i<$br && $i<$page_counter_middle)
				{
                    $i++;
                    $class = '';
                    $href = 'href="'.base_url('index.php/status/index/'.$realm.'/'.$i.'/'.$limit).'"';
                    if($i==$page){ $class = 'active'; $href = ''; }
                    $cont .='<a '.$href.'><li style="display: inline;" class="'.$class.'"> '.$i.' </li></a>';
                }
                
                if($all_pages>6 && $page<$all_pages-5)
				{
                    $cont .='<a><li style="display: inline;">...</li></a>';
				}
                if($all_pages>3)
				{
                    $i2 = $lastpage;
                    $i2_3 = $lastpage - 3;

                    if($all_pages-$page<5)
                    {
						$i2_3 = $lastpage - 2;
                    }
                    if($all_pages<6)
                    {
						$i2_3 = $br;
                    }
                    while($i2_3<$i2)
                    {
						$i2_3++;
						$class = '';
						$href = 'href="'.base_url('index.php/status/index/'.$realm.'/'.$i2_3.'/'.$limit).'"';
						if($i2_3==$page){ $class = 'active'; $href = ''; }
						$cont .='<a '.$href.'><li style="display: inline;" class="'.$class.'"> '.$i2_3.' </li></a>';
                    }
				}
                if($page!=$lastpage)
				{
                    $cont .= '<a class="right" href="'.base_url('index.php/status/index/'.$realm.'/'.$next.'/'.$limit).'"><li style="display: inline;">>>></li></a>';
				}
                else
                {
                    $cont .= '<a class="right"><li style="display: inline;">>>></li></a>';
				}
                $cont .= '</ol></center>';
            }
            return $cont;
		}
}
?>
