<?php
$data['title'] = $title;
$data['site_title'] = $this->config->item('site_title');
$data['img_url'] = $this->config->item('img_url');
$data['realmlist'] = $this->config->item('realmlist');
$data['header_content'] = $this->auto->head_content();
$data['realms_status'] = $this->auto->realms_status();
$data['base_url'] = base_url();
$data['current_url'] = current_url();
$data['news'] = (isset($news)) ? $news : array();
$data['pages'] = (isset($pages)) ? $pages : '';
$data['vote_pages'] = (isset($vote_pages)) ? $vote_pages : '';
$data['content'] = (isset($content)) ? $content : '';
$data['logged'] = $this->auto->membership();
$data['not_logged'] = $this->auto->login_form();
$data['logged2'] = $this->auto->membership();
$data['not_logged2'] = $this->auto->login_form();
$data['realms'] = $this->auto->get_realms();
$data['realms_tools'] = $this->auto->get_realms();
$data['realm_characters'] = $this->auto->get_characters();
$data['status'] = $this->session->flashdata('status');
$data['login_status'] = $this->session->flashdata('login_status');
$data['total_accounts'] = $this->auto->show_total_accounts();
$data['full_content_true'] = (isset($full_content)) ? array(array()) : array();
$data['full_content_false'] = (isset($full_content)) ? array() : array(array());
$data['current_server_time'] = date('H:i');

$controller = ($this->uri->segment(1)=='') ? 'home' : $this->uri->segment(1);
if($controller == 'forum')
{
	$data['uri'] = (isset($uri)) ? $uri : array();
	$data['forums'] = (isset($forums)) ? $forums : array();
	$data['description'] = (isset($description)) ? $description : '';
	$data['thread_id'] = (isset($thread_id)) ? $thread_id : 0;
	$data['forum_id'] = (isset($forum_id)) ? $forum_id : 0;
	$data['threads'] = (isset($threads)) ? $threads : array();
	$data['posts'] = (isset($posts)) ? $posts : array();
	$data['show_forum'] = (isset($show_forums) and $show_forums == true) ? array(array()) : array();
	$data['show_new_thread'] = (isset($show_new_thread) and $show_new_thread == true) ? array(array()) : array();
	$data['show_threads'] = (isset($show_threads) and $show_threads == true) ? array(array()) : array();
	$data['show_posts'] = (isset($show_posts) and $show_posts == true) ? array(array()) : array();
	$data['is_locked'] = (isset($is_locked) and $is_locked == true) ? true : false;
	$data['is_allowed_new_thread'] = ($this->auto->is_logged and $this->auto->is_baned == FALSE and $this->uri->segment(2)=='showforum') ?  array(array()) : array();
	$data['is_allowed_post'] = ($this->auto->is_logged and $this->auto->is_baned == FALSE and $this->uri->segment(2)=='showthread' and $data['is_locked'] == FALSE) ?  array(array()) : array();
	$data['thread_mod_links'] = (isset($thread_mod_links)) ? $thread_mod_links : null;
	$data['post'] = (isset($post)) ? $post : array();
	$data['thread'] = (isset($thread)) ? $thread : array();
	$data['all_forums'] = (isset($all_forums)) ? $all_forums : array();
	
}
include ('application/language/'.$this->session->userdata('language_session').'/main_lang.php');
foreach ($lang as $key => $val)
    $data['lang_'.$key] = $val;

$this->parser->parse($this->config->item('style').'/template/header', $data);
$this->parser->parse($this->config->item('style').'/pages/'.$controller, $data);
$this->parser->parse($this->config->item('style').'/template/footer', $data);
?>