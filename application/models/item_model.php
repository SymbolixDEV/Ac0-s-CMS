<?php
class Item_model extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
        } 
		
		function show_item($item_entry, $world_db)
		{
			$this->load->database();
            $config['hostname'] = $this->db->hostname;
            $config['username'] = $this->db->username;
            $config['password'] = $this->db->password;
            $config['database'] = $world_db;
            $config['dbdriver'] = "mysql";
            $config['dbprefix'] = "";
            $config['pconnect'] = FALSE;
            $config['db_debug'] = TRUE;
            $config['cache_on'] = FALSE;
            $config['cachedir'] = "";
            $config['char_set'] = "utf8";
            $config['dbcollat'] = "utf8_general_ci";

            $this->world = $this->load->database($config, TRUE);
			$this->world->where('entry', $item_entry);
			$query = $this->world->get('item_template', '1');
			
			$data = array();
			
			if($query->num_rows() != 1)
			{
				$data['error'] = sprintf('Cannot Find The "%d" Item', $item_entry);
				return $data;
			}
			
			$bonding = array(
				0=>'',
				1=>'Binds when picked up',
				2=>'Binds when equipped',
				3=>'Binds when used',
				4=>'Quest item',
				5=>'Quest Item'
			);
			
			$InventoryType = array(			
				0=>'Non equipable',
				15=>'Ranged', //(Bows)
				1=>'Head',
				16=>'Back',
				2=>'Neck',
				17=>'Two-Hand',
				3=>'Shoulder',
				18=>'Bag',
				4=>'Shirt',
				19=>'Tabard',
				5=>'Chest',
				20=>'Robe',
				6=>'Waist',
				21=>'Main hand',
				7=>'Legs',
				22=>'Off hand',
				8=>'Feet',
				23=>'Holdable', //(Tome)
				9=>'Wrists',
				24=>'Ammo',
				10=>'Hands',
				25=>'Thrown',
				11=>'Finger',
				26=>'Ranged right', //(Wands, Guns)
				12=>'Trinket',
				27=>'Quiver',
				13=>'Weapon',
				28=>'Relic',
				14=>'Shield');	
			
			$subclass = array(
			'0-0'=>'Consumable', //Usability in combat is decided by the spell assigned.
			'0-1'=>'Potion',
			'0-2'=>'Elixir',
			'0-3'=>'Flask',
			'0-4'=>'Scroll',
			'0-5'=>'Food & Drink',
			'0-6'=>'Item Enhancement',
			'0-7'=>'Bandage',
			'0-8'=>'Other',
			'1-0'=>'Bag',
			'1-1'=>'Soul Bag',
			'1-2'=>'Herb Bag',
			'1-3'=>'Enchanting Bag',
			'1-4'=>'Engineering Bag',
			'1-5'=>'Gem Bag',
			'1-6'=>'Mining Bag',
			'1-7'=>'Leatherworking Bag',
			'1-8'=>'Inscription Bag',
			'2-0'=>'Axe', //One handed
			'2-1'=>'Axe', //Two handed
			'2-2'=>'Bow',
			'2-3'=>'Gun',
			'2-4'=>'Mace', //One handed
			'2-5'=>'Mace', //Two handed
			'2-6'=>'Polearm',
			'2-7'=>'Sword', //One handed
			'2-8'=>'Sword', //Two handed
			'2-9'=>'Obsolete',
			'2-10'=>'Staff',
			'2-11'=>'Exotic',
			'2-12'=>'Exotic',
			'2-13'=>'Fist Weapon',
			'2-14'=>'Miscellaneous', //(Blacksmith Hammer, Mining Pick, etc.)
			'2-15'=>'Dagger',
			'2-16'=>'Thrown',
			'2-17'=>'Spear',
			'2-18'=>'Crossbow',
			'2-19'=>'Wand',
			'2-20'=>'Fishing Pole',
			'3-0'=>'Red',
			'3-1'=>'Blue',
			'3-2'=>'Yellow',
			'3-3'=>'Purple',
			'3-4'=>'Green',
			'3-5'=>'Orange',
			'3-6'=>'Meta',
			'3-7'=>'Simple',
			'3-8'=>'Prismatic',
			'4-0'=>'Miscellaneous',
			'4-1'=>'Cloth',
			'4-2'=>'Leather',
			'4-3'=>'Mail',
			'4-4'=>'Plate',
			'4-5'=>'Buckler',
			'4-6'=>'Shield',
			'4-7'=>'Libram',
			'4-8'=>'Idol',
			'4-9'=>'Totem',
			'4-10'=>'Sigil',
			'5-0'=>'Reagent',
			'6-0'=>'Wand',
			'6-1'=>'Bolt',
			'6-2'=>'Arrow',
			'6-3'=>'Bullet',
			'6-4'=>'Thrown',
			'7-0'=>'Trade Goods',
			'7-1'=>'Parts',
			'7-2'=>'Explosives',
			'7-3'=>'Devices',
			'7-4'=>'Jewelcrafting',
			'7-5'=>'Cloth',
			'7-6'=>'Leather',
			'7-7'=>'Metal & Stone',
			'7-8'=>'Meat',
			'7-9'=>'Herb',
			'7-10'=>'Elemental',
			'7-11'=>'Other',
			'7-12'=>'Enchanting',
			'7-13'=>'Materials',
			'7-14'=>'Armor Enchantment',
			'7-15'=>'Weapon Enchantment',
			'8-0'=>'Generic',
			'9-0'=>'Book',
			'9-1'=>'Leatherworking',
			'9-2'=>'Tailoring',
			'9-3'=>'Engineering',
			'9-4'=>'Blacksmithing',
			'9-5'=>'Cooking',
			'9-6'=>'Alchemy',
			'9-7'=>'First Aid',
			'9-8'=>'Enchanting',
			'9-9'=>'Fishing',
			'9-10'=>'Jewelcrafting',
			'10-0'=>'Money',
			'11-0'=>'Quiver',
			'11-1'=>'Quiver',
			'11-2'=>'Quiver', //Can hold arrows
			'11-3'=>'Ammo Pouch', //Can hold bullets
			'12-0'=>'Quest',
			'13-0'=>'Key',
			'13-1'=>'Lockpick',
			'14-0'=>'Permanent',
			'15-0'=>'Junk',
			'15-1'=>'Reagent',	
			'15-2'=>'Pet', 	
			'15-3'=>'Holiday', 	
			'15-4'=>'Other',	
			'15-5'=>'Mount',
			'16-1'=>'Warrior',
			'16-2'=>'Paladin',
			'16-3'=>'Hunter',
			'16-4'=>'Rogue',
			'16-5'=>'Priest',
			'16-6'=>'Death Knight',
			'16-7'=>'Shaman',
			'16-8'=>'Mage',
			'16-9'=>'Warlock',
			'16-11'=>'Druid');
			
			$stat_types = array(
				0 => 'mana',
				1 => 'health',
				3 => '+%d Agility',
				4 => '+%d Strenght',
				5 => '+%d Intelect',
				6 => '+%d Spirit',
				7 => '+%d Stamina',
				12 => 'defense skill rating',
				13 => 'dodge rating',
				14 => 'parry rating',
				15 => 'block rating',
				16 => 'melee hit rating',
				17 => 'ranged hit rating',
				18 => 'spell hit rating',
				19 => 'melee crit rating',
				20 => 'ranged crit rating',
				21 => 'spell crit rating',
				22 => 'melee hit taken rating',
				23 => 'ranged hit taken rating',
				24 => 'spell hit taken rating',
				25 => 'malee crit taken rating',
				26 => 'ranged crit taken rating',
				27 => 'spell crit taken rating',
				28 => 'melee haste rating',
				29 => 'ranged haste rating',
				30 => 'spell haste rating',
				31 => 'hig rating',
				32 => 'crit rating',
				33 => 'hit taken rating',
				34 => 'crit taken rating',
				35 => 'resilience rating',
				36 => 'haste rating',
				37 => 'expertise rating',
				38 => 'atack power',
				39 => 'ranged atack power',
				40 => 'feral atack power',
				41 => 'spell healing done',
				42 => 'spell damage',
				43 => 'mana regeneration',
				44 => 'armor penetration rating',
				45 => 'spell power',
				46 => 'health regen',
				47 => 'spell penetration',
				48 => 'block value');
			
			$main_stats = array(3, 4, 5, 6, 7);
			
			$sockets = array(
				1 => 'Meta',
				2 => 'Red',
				4 => 'Yellow',
				8 => 'Blue');

			$dmg_types = array(
				0 => '',
				1 => 'Holy ',
				2 => 'Fire ',
				3 => 'Nature ',
				4 => 'Frost ',
				5 => 'Shadow ',
				6 => 'Arcane ');
			
            $row = $query->row_array();
			
			$data['name'] = '<b class="q'.$row['Quality'].'" >'.$row['name'].'</b>';
			$data['bonding'] = $bonding[$row['bonding']];
			$data['item_left'] = $InventoryType[$row['InventoryType']];
			$data['item_right'] = $subclass[''.$row['class'].'-'.$row['subclass'].''];
			$data['requiredlevel'] = $row['RequiredLevel'];
			$data['itemlevel'] =  $row['ItemLevel'];
			$data['armor'] = $row['armor'];
			$data['maxcount'] = $row['maxcount'];
			
			
			$main_stats_br = 0;
			for($i=1;$i<=10;$i++)
			{
				if(in_array($row['stat_type'.$i], $main_stats))
				{
					$main_stats_br++;
					$data['main_stat_'.$main_stats_br] = sprintf($stat_types[$row['stat_type'.$i]], $row['stat_value'.$i]);
				}
				elseif($row['stat_type'.$i] != '0' && $row['stat_value'.$i] != '0')
					$data['stat_'.$i] = sprintf("Equip: Increases your ".$stat_types[$row['stat_type'.$i]]." by %u", $row['stat_value'.$i]);
			}
			
			for($i=1;$i<=3;$i++)
				if($row['socketColor_'.$i] != 0)
					$data['socket_'.$i] = '<span class="ico_socket_'.$row['socketColor_'.$i].' q0"> &nbsp;'.$sockets[$row['socketColor_'.$i]].' Socket</span>';
			
			$damages['min'] = 0;
			$damages['max'] = 0;
			
			for($i=1;$i<=2;$i++)
			{
				if($row['dmg_min'.$i] != 0 and $row['dmg_max'.$i] != 0)
				{
					$damages['min'] = $damages['min'] + $row['dmg_min'.$i];
					$damages['max'] = $damages['max'] + $row['dmg_max'.$i];
					$data['dmg_min'.$i] = $row['dmg_min'.$i];
					$data['dmg_max'.$i] = $row['dmg_max'.$i].' '.$dmg_types[$row['dmg_type'.$i]].'Damage';
				}
			}
			
			$data['delay'] = round($row['delay']/1000, 2);
			
			if($damages['min'] != 0 and $damages['max'] != 0)
				$data['dps'] = round((($damages['min'] + $damages['max'])/2)/$data['delay'], 2);
				
			
			
			return $data;
		}
}
?>