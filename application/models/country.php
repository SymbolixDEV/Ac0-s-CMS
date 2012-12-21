<?php
class Country extends CI_Model
{
    public function get_ip($ip='')
    {
        $this->load->dbutil();
        $dbs = $this->dbutil->list_databases();
        $exist = FALSE;
        foreach ($dbs as $db)
        {
            if($db==$this->config->item('ip2country_db'))
                $exist = TRUE;
        }
        if($exist==TRUE)
        {
            $ip = sprintf("%u",ip2long($ip));;

            $this->load->database();
            $config['hostname'] = $this->db->hostname;
            $config['username'] = $this->db->username;
            $config['password'] = $this->db->password;
            $config['database'] = $this->config->item('ip2country_db');
            $config['dbdriver'] = "mysql";
            $config['dbprefix'] = "";
            $config['pconnect'] = FALSE;
            $config['db_debug'] = TRUE;
            $config['cache_on'] = FALSE;
            $config['cachedir'] = "";
            $config['char_set'] = "utf8";
            $config['dbcollat'] = "utf8_general_ci";

            $this->country = $this->load->database($config, TRUE);  
            $this->country->select('country_name');
            $this->country->where('begin_ip_num <', $ip);
            $this->country->where('end_ip_num >', $ip);
            $query = $this->country->get('ip2c');
            $cont = $ip;
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $cont = $row['country_name'];
                }
            }
        }
        else
            $cont = 'No DB';
        return $cont;
    } 
    
}
?>