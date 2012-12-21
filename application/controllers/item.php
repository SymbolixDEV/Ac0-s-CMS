<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item extends CI_Controller {
	
		function index()
		{
			$item_entry = (int) $this->uri->segment(3);
			if ($item_entry < 0 || !is_int($item_entry) || $item_entry == null)
				die(sprintf('Invalid Item Entry "%d"', $item_entry));
			
			$item_intance = (int) $this->uri->segment(4);
			if ($item_intance < 0 || !is_int($item_intance) || $item_intance == null)
				die(sprintf('Invalid Item Instance "%d"', $item_intance));
			
			$char_db = $this->uri->segment(5);
			if(empty($char_db) || !preg_match("/^[0-9a-zA-Z_]+$/", $char_db))
				die(sprintf('Invalid Character Database "%s"', $char_db));
				
			$world_db = $this->uri->segment(6);
			if(empty($world_db) || !preg_match("/^[0-9a-zA-Z_]+$/", $world_db))
				die(sprintf('Invalid World Database "%s"', $world_db));			
			
			$this->load->model('item_model');
			$data = $this->item_model->show_item($item_entry, $world_db);
			
			if(isset($data['error']))
				die($data['error']);
				
			echo '<link rel="stylesheet" type="text/css" href="'.base_url('content/css/default_style.css').'" />';
			echo '<div style="width: 280px;text-align: left;">';				
			echo $data['name'];
			echo ($data['bonding']!='') ? '<br />'.$data['bonding'] : '';
			echo ($data['maxcount']!='0') ? '<br />Unique ' : ''; echo ($data['maxcount']>1) ? '('.$data['maxcount'].')' : '';
			echo '<br /><span class="left">'.$data['item_left'].'</span><span class="right">'.$data['item_right'].'</span>';
			for($i=1;$i<=2;$i++)
			{
				if(isset($data['dmg_min'.$i]) and isset($data['dmg_max'.$i]))
				{
					if($i ==1)
						echo '<br /><span class="left">'.$data['dmg_min'.$i].' - '.$data['dmg_max'.$i].'</span>';
					else
						echo '<br /><span class="left">+ '.$data['dmg_min'.$i].' - '.$data['dmg_max'.$i].'</span>';
					if($i == 1)
						echo '<span class="right">'.sprintf('Speed %.2f', $data['delay']).'</span>';
				}
			}
			echo (isset($data['dps']) and $data['dps'] != 0) ? sprintf('<br />(%.2f damage per second)', $data['dps']) : '';
			echo ($data['armor']!='0') ? '<br />'.$data['armor'].' Armor' : '';
			for($i=1;$i<=3;$i++)
				echo (isset($data['main_stat_'.$i])) ? '<br />'.$data['main_stat_'.$i] : '';
			for($i=1;$i<=3;$i++)
				echo (isset($data['socket_'.$i])) ? '<br />'.$data['socket_'.$i] : '';
			echo ($data['requiredlevel']>1) ? '<br />Requires Level '.$data['requiredlevel'] : '';
			echo ($data['itemlevel']!='0') ? '<br />Item Level '.$data['itemlevel'] : '';
			for($i=1;$i<=10;$i++)
				echo (isset($data['stat_'.$i])) ? '<br /><span class="q2">'.$data['stat_'.$i].'</span>' : '';
			echo '</div>';
		}
}
?>
