<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	function index()
	{
		$str = trim($this->input->post('search'));
	    if ($str)
        {
			$str = str_replace("/", " ", $str);
			$data['content'] = $this->search_content($str);
			$data['title'] = "Search for '{$str}'";
            $this->load->view('main_view', $data);
        }
        else
        {
            redirect(($this->input->post('return_link') && !empty($str)) ? ($this->input->post('return_link')) : 'index.php/home');
        }
	}
	
	function search_content($str = '')
	{
		if(empty($str))
			return;
		
		$this->load->model('search_model');
		
		$players = $this->search_model->get_characters($str);
		//$guild = $this->search_model->get_guilds($str);
		
		$cont = '<link rel="stylesheet" type="text/css" href="'.base_url('content/css/search_menu.css').'" />';
		
		$cont .= '<ul class="search_menu">';
		$cont .= '
					<li class="active"><a class="tabactive">Characters ('.sizeof($players).')</a></li>
					<li><a class="tabactive" style="color: red;">Under Construction</a></li>
				</ul>
				<div class="search_menu_content">
					<div class="space"><table width="100%"> 
						<tr>
                            <th class="aleft">Name</th>
							<th class="aleft">Level</th>
							<th></th>
							<th class="aleft">Status</th>
                            <th class="aleft">Realm</th>
                        </tr>'; 
					
	
            foreach($players as $player)
			{
                $cont .= '<tr>
                            <td><a href="'.base_url('index.php/character/index/'.$player['guid'].'/'.$player['realm_id']).'">'.$player['name'].'</a></td>
							<td>'.$player['level'].'</td>
							<td>'.$player['icon'].'</td>
							<td>'.$player['status'].'</td>
                            <td>'.$player['realm_name'].'</td>
                        </tr>';
			}
            
			$cont .= '</table></div></div>';

		return $cont;
	}
}