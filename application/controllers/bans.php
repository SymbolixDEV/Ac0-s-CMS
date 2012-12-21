<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bans extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
        }        
        
        public function account($page='0', $limit='30')
		{
			if(!is_numeric($page) || $page<0 || !is_numeric($limit) || $limit<=0)
				redirect('');
			
            $data['title'] = $this->lang->line('account_bans');
            $data['content'] = $this->show_bans($page, $limit, 0);
            $this->load->view('main_view', $data);
		}
		
		public function ip($page='0', $limit='30')
		{
			if(!is_numeric($page) || $page<0 || !is_numeric($limit) || $limit<=0)
				redirect('');
			
            $data['title'] = $this->lang->line('ip_bans');
            $data['content'] = $this->show_bans($page, $limit, 1);
            $this->load->view('main_view', $data);
		}
		
		public function character($realm_id = '0', $page='0', $limit='30')
		{
			if(!is_numeric($page) || $page<0 || !is_numeric($limit) || $limit<=0 || !is_numeric($realm_id) || $realm_id<=0)
				redirect('');
			
            $data['title'] = $this->lang->line('character_bans');
            $data['content'] = $this->show_bans($page, $limit, 2, $realm_id);
            $this->load->view('main_view', $data);
		}
        
        function show_bans($page, $limit, $type, $realm = '')
        {
            if      ($page!='0') { $start = ($page - 1) * $limit; } 	
            else    { $start = 0; }
            $this->load->model('tools_model');
			
			switch($type)
			{
				case 0:
					return $this->center_content($this->tools_model->show_account_bans($start, $limit), $type).$this->show_pages($page, $limit, $type);
					break;
				case 1:
					return $this->center_content($this->tools_model->show_ip_bans($start, $limit), $type).$this->show_pages($page, $limit, $type);
					break;
				case 2:
					if($realm == '') return;
					if($this->tools_model->select_realm_core($realm) != 'trinity') redirect('');
					$char_db = $this->tools_model->select_realm_char_db($realm);
					return $this->center_content($this->tools_model->show_character_bans($start, $limit, $char_db), $type).$this->show_pages($page, $limit, $type, $realm);
					break;
				default : 
					return 'incorrect option';
			}
        }
        
        function center_content($players, $type)
        {
			$types = array(0 => 'Account', 1 => 'IP', 2 => 'Character');
            $cont = '
                        <table width="100%">
                        <tr>
                            <th class="aleft">'.$types[$type].'</th>
                            <th class="aleft">Unban Date</th>
                            <th class="aleft">Ban Reason</th>
                        </tr>';
            foreach($players as $player)
                $cont .= '<tr>
                            <td>'.$player['baned_name'].'</td>
                            <td>'.$player['baned_unbandate'].'</a></td>
                            <td>'.$player['baned_reason'].'</td>
                        </tr>';
			if(sizeof($players)==0)
				$cont .= '<td colspan="8" class="aleft"><div class="warning"><span class="ico_warning">No '.$types[$type].' bans at that moment.</span></div></td>';
            $cont .= '</table>';
            
            return $cont;
        }
        
        function show_pages($page, $limit, $type, $realm = '')
        {
			$types = array(0 => 'account', 1 => 'ip', 2 => 'character');
            $this->load->model('tools_model');
            $total_rows = $this->tools_model->count_all_bans($type, $realm);
			
			if($total_rows <= $limit)
				return;
			
			$realm = ($realm != '') ? '/'.$realm : '';
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
                    $cont .= '<a class="left" href="'.base_url('index.php/bans/'.$types[$type].$realm.'/'.$prev.'/'.$limit).'"><li style="display: inline;"><<<</li></a>';
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
                        $cont .= '<a href="'.base_url('index.php/bans/'.$types[$type].$realm.'/'.$i.'/'.$limit).'"><li style="display: inline;"> '.$i.' </li></a>';
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
                    $href = 'href="'.base_url('index.php/bans/'.$types[$type].$realm.'/'.$i.'/'.$limit).'"';
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
						$href = 'href="'.base_url('index.php/bans/'.$types[$type].$realm.'/'.$i2_3.'/'.$limit).'"';
						if($i2_3==$page){ $class = 'active'; $href = ''; }
						$cont .='<a '.$href.'><li style="display: inline;" class="'.$class.'"> '.$i2_3.' </li></a>';
                    }
				}
                if($page!=$lastpage)
				{
                    $cont .= '<a class="right" href="'.base_url('index.php/bans/'.$types[$type].$realm.'/'.$next.'/'.$limit).'"><li style="display: inline;">>>></li></a>';
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
