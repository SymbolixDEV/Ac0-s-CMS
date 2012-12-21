<?php
class Acp_logs extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->cms = $this->load->database('default', TRUE);  
        }
        
        function return_paypal_logs()
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->order_by('whendon', 'desc');
            $query = $this->cms->get('paypal_data', '20');

            return $query->result_array();
        }
        
        function return_sms_logs()
        {
            $this->cms = $this->load->database('default', TRUE);  
            $this->cms->order_by('date', 'desc');
            $query = $this->cms->get('sms_log', '20');

            return $query->result_array();
        }
      
}
?>