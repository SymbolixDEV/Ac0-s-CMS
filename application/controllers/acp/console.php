<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Console extends CI_Controller {
    
        public function __construct() 
        {
            parent::__construct();
            
        }
        
	public function index($page = '')
	{
            $data['title'] = "Console";
            $data['ui'] = ucfirst($page);
            $data['left_content'] = $this->auto->pages($page, array('', 'logs'), 'Console');;
            $data['right_content'] = $this->show_right_content($page);
            $this->load->view('acp/main_view', $data);
	}
        
        function show_right_content($page)
        {
            $this->load->model('acp_console');
            $cont = '';
            switch($page)
            {
                case "logs":
                    $cont .= "<table width='100%' border = '#000000'>
                                <tr><td>Account</td><td>Action</td><td>Date</td></tr>";
                    foreach($this->auto->return_log('console') as $row)
                    {
                        $cont .= "<tr><td>".$row['account']."</td><td>".$row['comment']."</td><td>".$row['date']."</td></tr>";
                    }
                    $cont .= "</table>";
                    break;
                default :
                    $cont .= form_open('index.php/acp/console/send');
                    $cont .= $this->session->flashdata('console_status');
                    $cont .= "<table width='100%'>
                                <tr><td width='60px'><label for=''>Realmlist</label></td><td>".form_dropdown('realmlist', $this->acp_console->fill_drop_down())."</td></tr>
                                <tr><td width='60px'><label for='command'>Command</label></td><td><input type='text' name='command' id='command' style='width: 600px;'/></td></tr><tr><td></td><td><input type='submit' name='submit' id='submit' value='Submit' /></td></tr></table>";
                    $cont .= form_close(); 
                    break;
            }
            
            return $cont;
        }
        
        function send()
        {
            $rules = $this->form_validation;
            $rules->set_rules('realmlist', 'Realmlist', 'required|integer|greater_than[0]|trim');
            $rules->set_rules('command', 'Command', 'required|alpha_dash|trim');

            if ($rules->run() == TRUE)
            {
                $realm_id = $this->input->post('realmlist');
                $command = $this->input->post('command');
                $this->load->model('acp_console');
                $realm_info = $this->acp_console->realm_info($realm_id);
                $send = '';
                if($realm_info['ra']=='1')
                    $send = $this->ExecuteRACommand($command, 'localhost', $realm_info['ra_port']);
                elseif($realm_info['soap']=='1')
                    $send = $this->ExecuteSoapCommand($command, 'localhost', $realm_info['soap_port']);
                if($send['sent'])
                {
                    $this->session->set_flashdata('console_status', "<div class='success'><span class='ico_accept'>".$send['message']."</span></div>");
                }
                else
                {
                    $this->session->set_flashdata('console_status', "<div class='fail'><span class='ico_cancel'>".$send['message']."</span></div>");
                }
                $sended = ($send['sent']==TRUE) ? 'TRUE' : 'FALSE'; 
                $this->auto->logging('Sended: '.trim($sended).' | Command: '.trim($command).' | '.trim($send['message']), 'console');
            }
            else
                $this->session->set_flashdata('console_status', "<div class='warning'><span class='ico_warning'>Please type your command.</span></div>");
            redirect('index.php/acp/console', 'refresh');          
        }
        
        function ExecuteRACommand($command, $ip, $ra_port)
        {
            try
            {
                $telnet = @fsockopen($ip, $ra_port, $error, $error_str, 10);
            }
            catch(Exception $e)
            {
                return array('sent' => false, 'message' => trim($e->getMessage()));
            }
            if(!$telnet)
            {
                return array('sent' => false, 'message' => "ERROR: {$error}. No Connection Found.");
            }
            fputs($telnet, $this->config->item('admin_user')."\n");
            sleep(2);
            fputs($telnet, $this->config->item('admin_pass')."\n");
            sleep(2);
            
            fputs($telnet,  $command."\n");
            sleep(2);
            fclose($telnet);
		
            return array('sent' => true, 'message' => 'Successfuly Sended');
        }
        
        function ExecuteSoapCommand($command, $ip, $soap_port)
        {
            $connection = new SoapClient(NULL,
                    array(
                    "location" => "http://".$ip.":".$soap_port."/",
                    "uri" => "urn:TC",
                    "style" => SOAP_RPC,
                    "login" => $this->config->item('admin_user'),
                    "password" => $this->config->item('admin_pass'),
                    ));
		
            try
            {
                $result = $connection->executeCommand(new SoapParam($command, "command"));
            }
            catch(Exception $e)
            {
                return array('sent' => false, 'message' => trim($e->getMessage()));
            }
            return array('sent' => true, 'message' => 'Successfuly Sended');
        }
}

?>