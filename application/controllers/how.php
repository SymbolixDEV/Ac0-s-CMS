<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class How extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
        } 

	public function index()
	{
            $data['title'] = "How To Connect";
            $data['content'] = $this->config->item('how_to_connect');
            $this->load->view('main_view', $data);
	}
}