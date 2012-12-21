<?php
class Forum_model extends CI_Model
{
		static $uri;
		
        public function __construct() 
        {
            parent::__construct();
            $this->cms = $this->load->database('default', TRUE);
        }
		
		function get_thread_posts($id)
		{
			$this->cms->select('id');
			$this->cms->where('thread', $id);
			$query = $this->cms->get('forum_posts');
			
			return $query->num_rows();
		}
		
		function add_post($thread_id, $text, $poster_id)
        {
            $today = time();
            $data = array(
                'thread' => $thread_id,
                'poster' => $poster_id,
                'post' => $text,
                'date' => $today
                );
            $query = $this->cms->insert('forum_posts', $data);
			
			return $this->cms->affected_rows();
        }
		
		function new_thread($thread_id, $forum_id, $name, $description, $poster_id)
        {
            $today = time();
            $data = array(
				'id' => $thread_id,
                'forum' => $forum_id,
                'name' => $name,
                'description' => $description,
                'poster' => $poster_id
                );
            $query = $this->cms->insert('forum_threads', $data);
			
			return $this->cms->affected_rows();
        }
		
		function edit_post($post_id, $text)
        {
            $today = time();
            $data = array(
                'post' => $text,
                'last_edit_date' => $today
                );
			$this->cms->where('id', $post_id);
            $query = $this->cms->update('forum_posts', $data);
			
			return $this->cms->affected_rows();
        }
		
		function delete_post($post_id)
        {
			$this->cms->where('id', $post_id);
            $query = $this->cms->delete('forum_posts');
			
			return $this->cms->affected_rows();
        }
		
		function delete_thread_posts($thread_id)
        {
			$this->cms->where('thread', $thread_id);
            $query = $this->cms->delete('forum_posts');
			
			return $this->cms->affected_rows();
        }
		
		function delete_thread($thread_id)
        {
			$this->cms->where('id', $thread_id);
            $query = $this->cms->delete('forum_threads');
			
			return $this->cms->affected_rows();
        }
		
		function edit_thread($thread_id, $forum, $name, $description, $locked)
		{
            $data = array(
				'forum' => $forum,
                'name' => mysql_real_escape_string($name),
				'description' => mysql_real_escape_string($description),
				'locked' => $locked
                );
			$this->cms->where('id', $thread_id);
            $query = $this->cms->update('forum_threads', $data);
			
			return $this->cms->affected_rows();
        }
		
		function select_thread_max_id()
		{
			$this->db->select_max('id');
			$query = $this->db->get('forum_threads');
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array(); 
				return $row['id'];
			}
			else
				return 0;			
		}
		
		function get_forum_threads_posts($id)
        {
			$this->cms->select('id');
			$this->cms->where('forum', $id);
			$query = $this->cms->get('forum_threads');
			
			$posts = 0;
			
			if($query->num_rows() > 0)
				foreach($query->result_array() as $row)
					$posts += $this->get_thread_posts($row['id']);
			
			return $query->num_rows().'-'.$posts;
		}
		
		function get_last_forum_post($id)
		{
			$this->cms->select('forum_threads.`name` as thread, forum_threads.`id` as id, forum_posts.poster as poster_id, forum_posts.date as date, forum_posts.id as post_id, account_addition.username as username');
			$this->cms->join('account_addition', 'forum_posts.poster = account_addition.id');
			$this->cms->join('forum_threads', 'forum_posts.thread = forum_threads.id');
			$this->cms->where('forum_threads.forum', $id);
			$this->cms->order_by('forum_posts.date', 'desc');
			$query = $this->cms->get('forum_posts', 1);
			
			$cont = array();
			$i = 0;
			
			if($query->num_rows() > 0)
				foreach($query->result_array() as $row)
				{
					$i++;
					$cont[$i]['last_forum_post_thread'] = $row['thread'];
					$cont[$i]['last_forum_post_date'] = $this->auto->relative_time($row['date']);
					$cont[$i]['last_forum_post_username'] = $row['username'];
					$cont[$i]['last_forum_post_username_link'] = base_url('index.php/profile/index/'.$row['poster_id']);
					$cont[$i]['last_forum_post_link'] = base_url('index.php/forum/showthread/'.$row['id'].'#post-'.$row['post_id']);
					$cont[$i]['last_forum_thread_link'] = base_url('index.php/forum/showthread/'.$row['id']);
				}
				
			return $cont;
		}
		
		function get_last_thread_post($id)
		{
			$this->cms->select('forum_posts.poster as poster_id, forum_posts.date as date, forum_posts.id as post_id, account_addition.username as username');
			$this->cms->join('account_addition', 'forum_posts.poster = account_addition.id');
			$this->cms->where('forum_posts.thread', $id);
			$this->cms->order_by('forum_posts.date', 'desc');
			$query = $this->cms->get('forum_posts', 1);
			
			$cont = array();
			$i = 0;
			
			if($query->num_rows() > 0)
				foreach($query->result_array() as $row)
				{
					$i++;
					$cont[$i]['last_thread_post_date'] = $this->auto->relative_time($row['date']);
					$cont[$i]['last_thread_post_username'] = $row['username'];
					$cont[$i]['last_thread_post_username_link'] = base_url('index.php/profile/index/'.$row['poster_id']);
					$cont[$i]['last_thread_post_link'] = base_url('index.php/forum/showthread/'.$id.'#post-'.$row['post_id']);
				}
				
			return $cont;
		}
		
		function get_post($id)
		{
			$this->cms->where('id', $id);
			$query = $this->cms->get('forum_posts', 1);
			
			return $query->result_array();
		}
		
		function get_last_post($id)
		{
			$this->cms->where('thread', $id);
			$this->cms->order_by('id', 'desc');
			$query = $this->cms->get('forum_posts', 1);
			
			return $query->result_array();
		}
		
		function get_all_forums()
		{
			$this->cms->select('id, name');
			$query = $this->cms->get('forum_forums');
			
			$data = array();
			
			if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
					$data[$row['id']] = $row['name'];
                }
            }
			
			return $data;
		}
		
		function get_thread($id)
		{
			$this->cms->where('id', $id);
			$query = $this->cms->get('forum_threads', 1);
			
			return $query->result_array();
		}
		
		function get_user_posts($id)
        {
            $this->cms = $this->load->database('default', TRUE);  
			$this->cms->select('id');
            $this->cms->where('poster', $id);
            $query = $this->cms->get('forum_posts');

            return $query->num_rows();
        }
		
		function get_account_access($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->where('id', $id);

            $query = $this->auth->get('account_access', 1);
			
			return ($query->num_rows() > 0) ? $query->row_array()['gmlevel'] : 0;
        }
		
		function check_account_bann($id)
        {
            $this->auth = $this->load->database('auth', TRUE);  
            $this->auth->where('id', $id);
            $this->auth->where('active', '1');

            $query = $this->auth->get('account_banned', 1);

            return ($this->auth->affected_rows() > 0) ? TRUE : FALSE;
        }
		
		function get_ranks($id, $web_ranks)
		{
			$i = 0;
			if($this->check_account_bann($id))
				return array( $i => array( 'name' => 'Banned', 'img' => base_url('content/img/ranks/web/banned.png')));
			
			$cont = array();
			$in_game_rank = $this->get_account_access($id);
			if($in_game_rank != 0)
			{
				$i++;
				$cont[$i] = array( 'name' => $this->config->item('in_game_ranks')[$in_game_rank][0], 'img' => base_url('content/img/ranks/in_game/'.$this->config->item('in_game_ranks')[$in_game_rank][1]));
			}
			
			if($web_ranks != 0)
			{
				$i++;
				$cont[$i] = array( 'name' => $this->config->item('web_ranks')[$web_ranks][0], 'img' => base_url('content/img/ranks/web/'.$this->config->item('web_ranks')[$web_ranks][1]));
			}
			
			if($i == 0)
				$cont[$i] = array( 'name' => $this->config->item('web_ranks')[0][0], 'img' => base_url('content/img/ranks/web/'.$this->config->item('web_ranks')[0][1]));
			
			return $cont;
		}
		
		function get_posts($id)
		{
			$this->cms->select('forum_posts.poster as poster_id, forum_posts.date as date, forum_posts.last_edit_date as last_edit_date, 
			forum_posts.id as post_id, forum_posts.post as post, account_addition.username as username, 
			account_addition.avatar as avatar, account_addition.reputation as reputation, account_addition.posts as posts, 
			account_addition.gender as gender, account_addition.location as location, account_addition.rank as rank');
			$this->cms->join('account_addition', 'forum_posts.poster = account_addition.id');
			$this->cms->where('forum_posts.thread', $id);
			$this->cms->order_by('forum_posts.id', 'asc');
			$query = $this->cms->get('forum_posts');
			
			$cont = array();
			
			$gender = array(0 => 'Male', 1 => 'Female');
			
			$i = 0;
			
			if($query->num_rows() > 0)
				foreach($query->result_array() as $row)
				{
					$i++;
					$cont[$i]['post_number'] = $i;
					$cont[$i]['post_date'] = $this->auto->relative_time($row['date']);
					$cont[$i]['post_username'] = $row['username'];
					$cont[$i]['post_username_avatar_link'] = (!empty($row['avatar'])) ? base_url('content/img/avatars/'.$row['username'].'/'.$row['avatar']) : base_url('content/img/avatars/default-'.$row['gender'].'.jpg');
					$cont[$i]['post_username_gender'] = $gender[$row['gender']];
					$cont[$i]['post_username_location'] = $row['location'];
					$cont[$i]['post_username_show_location'] = (!empty($row['location'])) ? array(array()) : array();
					$cont[$i]['post_username_rank'] = $this->get_ranks($row['poster_id'], $row['rank']);
					$cont[$i]['post_username_posts'] = $this->get_user_posts($row['poster_id']);
					$cont[$i]['post_username_reputation'] = $row['reputation'];
					$cont[$i]['post_username_link'] = base_url('index.php/profile/index/'.$row['poster_id']);
					$cont[$i]['post_content'] = parse_smileys($row['post'], base_url('content/img/smileys/'));
					$cont[$i]['post_id'] = $row['post_id'];
					$cont[$i]['post_last_edit_date'] = $this->auto->relative_time($row['last_edit_date']);
					$cont[$i]['post_show_last_edit_date'] = ($row['last_edit_date'] != 0) ? array(array()) : array();
					$cont[$i]['post_mod_links'] = ($this->auto->is_admin or $this->session->userdata('id') == $row['poster_id']) ? '<a href="'.base_url('index.php/forum/delete_post/'.$row['post_id']).'">Delete</a> - <a href="'.base_url('index.php/forum/edit_post/'.$row['post_id']).'">Edit</a>' : '';
				}
				
			return $cont;
		}
		
		function get_uri($id = '', $thread = false)
		{
			$this->get_main_forums($id);
			
			$o = 0;
			$cont = array($o => array('uri_name' => 'Home', 'uri_link' => base_url('index.php/forum'), 'uri_class' => ''));
			for($i = sizeof($this->uri) - 1;$i >= 1;$i--)
			{
				$o++;
				$cont[$o] = ($i == 1 and $thread == false) ? array('uri_name' => $this->uri[$i]['uri_name'], 'uri_link' => $this->uri[$i]['uri_link'], 'uri_class' => 'last') : array('uri_name' => $this->uri[$i]['uri_name'], 'uri_link' => $this->uri[$i]['uri_link'], 'uri_class' => '');
			}
			return $cont;
		}
		
		function get_main_forums($id = '', $i = 0)
		{
			if($i == 0)
				$this->uri = array(0 => array('uri_name' => 'Home', 'uri_link' => base_url('index.php/forum')));
				
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				return false;
			
			$row = $this->get_forum($id);
			
			$i++;
			$this->uri[$i] = array('uri_name' => $row[0]['name'], 'uri_link' => base_url('index.php/forum/showforum/'.$id));
			
			if($row[0]['sub'] != 0)
				$this->get_main_forums($row[0]['sub'], $i);
		}
		
		function get_account($id = 0)
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				return 'Deleted';
				
			$this->cms->select('username');	
			$this->cms->where('id', $id);
			$query = $this->cms->get('account_addition', 1);
			
			if($query->num_rows() > 0)
				foreach($query->result_array() as $row)
					return $row['username'];
			else
				return 'Deleted';
		}
		
		function get_thread_title($id)
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				return false;
				
			$this->cms->select('name');	
			$this->cms->where('id', $id);
			$query = $this->cms->get('forum_threads', 1);
			
			if($query->num_rows() > 0)
				foreach($query->result_array() as $row)
					return $row['name'];
			else
				return false;
		}
		
		function get_forum_title($id)
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				return false;
				
			$this->cms->select('name');	
			$this->cms->where('id', $id);
			$query = $this->cms->get('forum_forums', 1);
			
			if($query->num_rows() > 0)
				foreach($query->result_array() as $row)
					return $row['name'];
			else
				return false;
		}
		
		function get_forum($id = '')
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				return false;
				
			$this->cms->where('id', $id);
				
			$query = $this->cms->get('forum_forums', 1);	
			
			return $query->result_array();
		}
		
        function get_forums($id = 0)
		{
			$id = (int) $id;
			if($id < 0 or !is_int($id))
				return array();
				
			$this->cms->where('sub', $id);
				
			$query = $this->cms->get('forum_forums');
			
			$cont = array();
			
			if($query->num_rows() > 0)
			{
				$i = 0;
				foreach($query->result_array() as $row)
				{
					$i++;
					
					$explode = explode('-', $this->get_forum_threads_posts($row['id']));
					
					$cont[$i]['forum_id'] = $row['id'];
					$cont[$i]['forum_name'] = $row['name'];
					$cont[$i]['forum_link'] = base_url('index.php/forum/showforum/'.$row['id']);
					$cont[$i]['forum_description'] = $row['description'];
					$cont[$i]['forum_threads'] = $explode[0];
					$cont[$i]['forum_posts'] = $explode[1];
					
					$cont[$i]['forum_last_post'] = $this->get_last_forum_post($row['id']);				
				}
			}
			
			return $cont;
		}
		
		function get_threads($id = '')
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				return array();
				
			$this->cms->where('forum', $id);
				
			$query = $this->cms->get('forum_threads');
			
			$cont = array();
			
			if($query->num_rows() > 0)
			{
				$i = 0;
				foreach($query->result_array() as $row)
				{
					$i++;
					
					$cont[$i]['thread_id'] = $row['id'];
					$cont[$i]['thread_name'] = $row['name'];
					$cont[$i]['thread_link'] = base_url('index.php/forum/showthread/'.$row['id']);
					$cont[$i]['thread_description'] = $row['description'];
					$cont[$i]['thread_views'] = $row['viewed'];
					$cont[$i]['thread_posts'] = $this->get_thread_posts($row['id']);
					$cont[$i]['thread_poster'] = $this->get_account($row['poster']);
					$cont[$i]['thread_poster_link'] = ($cont[$i]['thread_poster'] != 'Deleted') ? base_url('index.php/profile/index/'.$row['poster']) : '#';
					$cont[$i]['thread_last_post'] = $this->get_last_thread_post($row['id']);
					$cont[$i]['thread_mod_links'] = ($this->auto->is_admin) ? '<a href="'.base_url('index.php/forum/delete_thread/'.$row['id']).'">Delete</a> - <a href="'.base_url('index.php/forum/edit_thread/'.$row['id']).'">Edit</a>' : '';
				}
			}
			
			return $cont;
		}
}
?>