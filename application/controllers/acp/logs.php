<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
            
        }
        
	public function index($page = 'all')
	{
            $data['title'] = "Logs";
            $data['ui'] = ucfirst($page);
            $data['left_content'] = $this->auto->pages($page, array('all', 'console', 'news', 'realms', 'vote_shop', 'donate_shop', 'sms', 'paypal'), 'Logs');;
            $data['right_content'] = $this->show_right_content($page);
            $this->load->view('acp/main_view', $data);
	}
        
        function show_right_content($page)
        {
            $cont = '';
            $cont .= "<table width='100%'>";
            switch($page)
            {
                case "all":
                    $cont .= "<tr><td style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Account</td><td  style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Action</td><td  style='border-bottom:1px solid #000000;padding:2px;width: 120px;'>Date</td></tr>";
                    foreach($this->auto->return_log('') as $row)
                    {
                        $cont .= "<tr><td style='border-right:1px solid #000000;padding:2px;text-align:center;border-bottom:1px solid #000000;'>".$row['account']."</td><td style='border-right:1px solid #000000;padding:2px;border-bottom:1px solid #000000;'>".$row['comment']."</td><td style='border-bottom:1px solid #000000;padding:2px;'>".$row['date']."</td></tr>";
                    }
                    break;
                case "sms":
                    $cont .= "<tr><td style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Account ID</td><td style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Code</td><td  style='border-bottom:1px solid #000000;padding:2px;'>Date</td></tr>";
                    $this->load->model('acp_logs');
                    foreach($this->acp_logs->return_sms_logs() as $row)
                    {
                        $cont .= "<tr><td style='border-right:1px solid #000000;padding:2px;text-align:center;border-bottom:1px solid #000000;'>".$row['user_id']."</td><td style='border-right:1px solid #000000;padding:2px;text-align:center;border-bottom:1px solid #000000;'>".$row['code']."</td><td style='border-bottom:1px solid #000000;padding:2px;'>".date( "dS F, Y @ h:ia" , $row['date'])."</td></tr>";
                    }
                    break;
                case "paypal":
                    $cont .= "<tr><td style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Account ID</td><td style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Amount</td><td style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Who</td><td  style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Action</td><td  style='border-bottom:1px solid #000000;padding:2px;'>Date</td></tr>";
                    $this->load->model('acp_logs');
                    foreach($this->acp_logs->return_paypal_logs() as $row)
                    {
                        $cont .= "<tr><td style='border-right:1px solid #000000;padding:2px;text-align:center;border-bottom:1px solid #000000;'>".$row['username']."</td><td style='border-right:1px solid #000000;padding:2px;text-align:center;border-bottom:1px solid #000000;'>".$row['amount']."</td><td style='border-right:1px solid #000000;padding:2px;text-align:center;border-bottom:1px solid #000000;'>".$row['who']."</td><td style='border-right:1px solid #000000;padding:2px;border-bottom:1px solid #000000;'>".$row['comment']."</td><td style='border-bottom:1px solid #000000;padding:2px;'>".date( "dS F, Y @ h:ia" , $row['whendon'])."</td></tr>";
                    }
                    break;
                default:
                     $cont .= "<tr><td style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Account</td><td  style='border-bottom:1px solid #000000;padding:2px;border-right:1px solid #000000;'>Action</td><td  style='border-bottom:1px solid #000000;padding:2px;width: 120px;'>Date</td></tr>";
                    foreach($this->auto->return_log($page) as $row)
                    {
                        $cont .= "<tr><td style='border-right:1px solid #000000;padding:2px;text-align:center;border-bottom:1px solid #000000;'>".$row['account']."</td><td style='border-right:1px solid #000000;padding:2px;border-bottom:1px solid #000000;'>".$row['comment']."</td><td style='border-bottom:1px solid #000000;padding:2px;'>".$row['date']."</td></tr>";
                    }
                    break;
            }
            
            $cont .= "</table>";
            
            return $cont;
        }
}

?>