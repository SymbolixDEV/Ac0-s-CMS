<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
            
        }
        
	public function index($page = '')
	{
            $data['title'] = "Home";
            $data['ui'] = ucfirst($page);
            $data['left_content'] = $this->show_left_content();
            $data['right_content'] = $this->show_right_content();
            $this->load->view('acp/main_view', $data);
	}
        
        function show_right_content()
        {
            $cont = '<span id="home"></span>
                        <script>
                            showContent("'.base_url('index.php/acp/ajax/show_status').'", "home");
                        </script><noscript>*<small>Please active your javascript to see realm status</small>.</noscript>';
            
            return $cont;
        }
        
        function show_left_content()
        {
            $this->load->model('acp_home');
            $cont = '';
            $cont .= "<li>All Accounts: <strong>".$this->acp_home->all_accounts()."</strong></li>";
            $cont .= "<li>Accounts created today: <strong>".$this->acp_home->accounts_today()."</strong></li>";
            $cont .= '<span id="realmstatus_info"></span>
                        <script>
                            showContent("'.base_url('index.php/acp/ajax/show_realms_status').'", "realmstatus_info");
                        </script><noscript>*<small>Please active your javascript to see realm status</small>.</noscript>';
            return $cont;
        }
}

?>