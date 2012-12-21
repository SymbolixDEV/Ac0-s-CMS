<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
        }        
        
        public function index()
		{
			$this->load->model('forum_model');
			$data['title'] = 'Forum';
			$data['uri'] = $this->forum_model->get_uri();
			$data['full_content'] = true;
			$data['forums'] = $this->forum_model->get_forums();
			$data['show_forums'] = (sizeof($data['forums']) > 0) ? true : false;
			
			$this->load->view('main_view', $data);
        }
		
		public function showforum($id = '')
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				redirect('index.php/forum');
			
			$this->load->model('forum_model');
			
			$data['uri'] = $this->forum_model->get_uri($id);
			$data['title'] = $this->forum_model->get_forum_title($id);
			$data['forum_id'] = $id;
			if($data['title'] == false)
				redirect('index.php/forum');
			$data['full_content'] = true;
			$data['forums'] = $this->forum_model->get_forums($id);
			$data['threads'] = $this->forum_model->get_threads($id);
			$data['show_forums'] = (sizeof($data['forums']) > 0) ? true : false;
			$data['show_threads'] = (sizeof($data['threads']) > 0) ? true : false;
			
			$this->load->view('main_view', $data);
		}
		
		public function showthread($id = '')
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				redirect('index.php/forum');
			
			$this->load->model('forum_model');
			$thread = $this->forum_model->get_thread($id);
			$data['title'] = ($thread[0]['locked'] == 0) ? $thread[0]['name'] : $thread[0]['name'].' (locked)';
			$data['uri'] = $this->forum_model->get_uri($thread[0]['forum'], true);
			$data['uri'][sizeof($data['uri'])] = array('uri_name' => $thread[0]['name'], 'uri_link' => base_url('index.php/forum/showthread/'.$id), 'uri_class' => 'last');
			$data['description'] = $thread[0]['description'];
			if($data['title'] == false)
				redirect('index.php/forum');
			$data['thread_id'] = $id;
			$data['full_content'] = true;
			$data['show_posts'] = true;
			$data['is_locked'] = ($thread[0]['locked'] == 0) ? false : true;
			$data['posts'] = $this->forum_model->get_posts($id);
			$data['thread_mod_links'] = ($this->auto->is_admin) ? '<a href="'.base_url('index.php/forum/delete_thread/'.$id).'">Delete</a> - <a href="'.base_url('index.php/forum/edit_thread/'.$id).'">Edit</a>' : '';
			if(sizeof($data['posts']) <= 0)
			{
				$this->delete_thread($id);
				redirect('index.php/forum');
			}
			
			$this->load->view('main_view', $data);
		}
		
		public function edit_post($id = '')
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				redirect('index.php/forum');
				
			$this->load->model('forum_model');
			$data['title'] = 'Edit Post';
			$data['full_content'] = true;
			$data['post'] = $this->forum_model->get_post($id);
			
			if(sizeof($data['post']) <= 0)
				redirect('index.php/forum');
				
			if($data['post'][0]['poster'] != $this->session->userdata('id'))
				if(!$this->auto->is_admin)
					redirect('index.php/forum');
					
			$data['post'][0]['post'] = str_replace('\r\n', "&#10;", $data['post'][0]['post']);
			
			$this->load->view('main_view', $data);
		}
		
		public function delete_post($id = '')
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				redirect('index.php/forum');
				
			$this->load->model('forum_model');
			$data['post'] = $this->forum_model->get_post($id);
			
			if(sizeof($data['post']) <= 0)
				redirect('index.php/forum');
				
			if($data['post'][0]['poster'] != $this->session->userdata('id'))
				if(!$this->auto->is_admin)
					redirect('index.php/forum');
			
			$this->forum_model->delete_post($id);
			
			redirect(base_url('index.php/forum/showthread/'.$data['post'][0]['thread']));
		}
		
		public function delete_thread($id = '')
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				redirect('index.php/forum');
				
			$this->load->model('forum_model');
			$data['thread'] = $this->forum_model->get_thread($id);
			
			if(sizeof($data['thread']) <= 0)
				redirect('index.php/forum');
			
			if(!$this->auto->is_admin)
				redirect('index.php/forum');
			
			$this->forum_model->delete_thread($id);
			$this->forum_model->delete_thread_posts($id);
			
			redirect(base_url('index.php/forum/showthread/'.$data['thread'][0]['forum']));
		}
		
		public function edit_thread($id = '')
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				redirect('index.php/forum');
				
			$this->load->model('forum_model');
			$data['title'] = 'Edit Thread';
			$data['full_content'] = true;
			$data['thread'] = $this->forum_model->get_thread($id);
			$data['all_forums'] = $this->forum_model->get_all_forums();
			if(!$this->auto->is_admin)
				redirect('index.php/forum');
			if(sizeof($data['thread']) <= 0)
				redirect('index.php/forum');
			
			$this->load->view('main_view', $data);
		}
		
		public function new_thread($id = '')
		{
			$id = (int) $id;
			if($id <= 0 or !is_int($id) or $id == null)
				redirect('index.php/forum');
				
			if(!$this->auto->is_logged)
				redirect('index.php/forum');
				
			$data['title'] = 'New Thread';
			$data['full_content'] = true;
			$data['show_new_thread'] = true;
			$data['forum_id'] = $id;
			
			$this->load->view('main_view', $data);
		}
		
		function validation_edit_thread()
		{
			if(!$this->auto->is_admin)
				redirect('index.php/forum');
				
			$rules = $this->form_validation;
			$rules->set_rules('description', 'Description', 'required|alpha_dash|trim|htmlspecialchars');
			$rules->set_rules('name', 'Name', 'required|alpha_dash|trim|htmlspecialchars');
			$rules->set_rules('forum', 'Forum', 'greater_than[0]|required|integer|is_natural');
			$rules->set_rules('thread_id', 'ID', 'greater_than[0]|required|integer|is_natural');
			$rules->set_rules('locked', 'Locked', 'required|integer|is_natural');
			
			$return_link = $this->input->post('return_link');
			if ($rules->run() != TRUE)
			{
				$this->session->set_flashdata('status', "<div class='warning'><span class='ico_warning'>Please type correct evert field.</span></div>");
				redirect($return_link);
			}
			
			$forum = $this->input->post('forum');
			$thread_id = $this->input->post('thread_id');
			$description = mysql_real_escape_string($this->input->post('description'));
			$name = mysql_real_escape_string($this->input->post('name'));
			$locked = $this->input->post('locked');
			$this->load->model('forum_model');
			if($this->forum_model->edit_thread($thread_id, $forum, $name, $description, $locked) == 1)
				$this->session->set_flashdata('status', "<div class='success'><span class='ico_accept'>Successfuly edited.</span></div>");
			
			redirect(base_url('index.php/forum/showforum/'.$forum));
		}
		
		function validation_new_thread()
		{
			if(!$this->auto->is_logged)
				redirect('index.php/forum');
				
			$rules = $this->form_validation;
			$rules->set_rules('description', 'Description', 'required|alpha_dash|trim|htmlspecialchars');
			$rules->set_rules('forum_post', 'Post', 'required|alpha_dash|trim|htmlspecialchars');
			$rules->set_rules('name', 'Name', 'required|alpha_dash|trim|htmlspecialchars');
			$rules->set_rules('forum', 'Forum', 'greater_than[0]|required|integer|is_natural');
			
			$return_link = $this->input->post('return_link');
			if ($rules->run() != TRUE)
			{
				$this->session->set_flashdata('status', "<div class='warning'><span class='ico_warning'>Please type correct evert field.</span></div>");
				redirect($return_link);
			}
			
			$this->load->model('forum_model');
			$id = $this->forum_model->select_thread_max_id();
			$id = (int) $id + 1;
			if($id <= 0 or !is_int($id) or $id == null)
				redirect('index.php/forum');
			
			if($this->forum_model->new_thread($id, $this->input->post('forum'), mysql_real_escape_string($this->input->post('name')), mysql_real_escape_string($this->input->post('description')), $this->session->userdata('id')) == 1 and $this->forum_model->add_post($id, mysql_real_escape_string($this->input->post('forum_post')), $this->session->userdata('id')) == 1)
				$this->session->set_flashdata('status', "<div class='success'><span class='ico_accept'>Successfuly added new thread.</span></div>");
			
			redirect(base_url('index.php/forum/showthread/'.$id));
		}
		
		function validation_edit_post()
		{
			if($this->session->userdata('is_logged_session')!=TRUE)
				redirect('index.php/forum');
				
			$rules = $this->form_validation;
			$rules->set_rules('forum_post', 'Post', 'required|alpha_dash|trim|htmlspecialchars');
			$rules->set_rules('post_id', 'ID', 'greater_than[0]|required|integer|is_natural');
			$rules->set_rules('thread_id', 'ID', 'greater_than[0]|required|integer|is_natural');
			
			$return_link = $this->input->post('return_link');
			if ($rules->run() != TRUE)
			{
				$this->session->set_flashdata('status', "<div class='warning'><span class='ico_warning'>Please type your reply first.</span></div>");
				redirect($return_link);
			}
			
			$id = $this->input->post('post_id');
			$thread_id = $this->input->post('thread_id');
			$forum_post = mysql_real_escape_string($this->input->post('forum_post'));
			$this->load->model('forum_model');
			$data['post'] = $this->forum_model->get_post($id);
			if($data['post'][0]['poster'] != $this->session->userdata('id'))
				if(!$this->auto->is_admin)
					redirect('index.php/forum');
					
			$poster_id = $this->session->userdata('id');
			if($this->forum_model->edit_post($id, $forum_post) == 1)
				$this->session->set_flashdata('status', "<div class='success'><span class='ico_accept'>Successfuly edited.</span></div>");
			
			redirect(base_url('index.php/forum/showthread/'.$thread_id.'#post-'.$id));
		}
		
		function validation_post()
		{
			if($this->session->userdata('is_logged_session')!=TRUE)
				redirect('index.php/forum');
				
			$rules = $this->form_validation;
			$rules->set_rules('forum_post', 'Post', 'required|alpha_dash|trim|htmlspecialchars');
			$rules->set_rules('thread_id', 'ID', 'greater_than[0]|required|integer|is_natural');
			
			$return_link = $this->input->post('return_link');
			if ($rules->run() != TRUE)
			{
				$this->session->set_flashdata('status', "<div class='warning'><span class='ico_warning'>Please type your reply first.</span></div>");
				redirect($return_link."#quick_reply");
			}
			
			$id = $this->input->post('thread_id');
			$forum_post = mysql_real_escape_string($this->input->post('forum_post'));
			$poster_id = $this->session->userdata('id');
			
			$this->load->model('forum_model');
			
			$last_post = $this->forum_model->get_last_post($id);
			if($last_post[0]['poster'] == $poster_id)
			{
				if($this->forum_model->edit_post($last_post[0]['id'], $last_post[0]['post'].'\r\n\r\n'.$forum_post) == 1)
					$this->session->set_flashdata('status', "<div class='success'><span class='ico_accept'>Successfuly edited.</span></div>");
				
				redirect($return_link."#quick_reply");
			}
			
			if($this->forum_model->add_post($id, $forum_post, $poster_id) == 1)
				$this->session->set_flashdata('status', "<div class='success'><span class='ico_accept'>Successfuly posted.</span></div>");
			
			redirect($return_link."#quick_reply");
		}
		
}
?>
