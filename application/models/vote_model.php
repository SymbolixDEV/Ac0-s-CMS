<?php
class Vote_model extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->cms = $this->load->database('default', TRUE);  
        } 
        
        function select_vote_link($id)
        {
            $this->cms->select('link');
            $this->cms->where('id', $id);
            $query = $this->cms->get('vote_sites');
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    return $row['link'];
                }
            }
            else
                return 'home';
        }
        
        function select_vote_points($id)
        {
            $this->cms->select('points');
            $this->cms->where('id', $id);
            $query = $this->cms->get('vote_sites');
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    return $row['points'];
                }
            }
            else
                return 0;
        }
        
        function _check_voting_user($site, $user_id)
        {
            $this->cms->select('id');
            $this->cms->where('site', $site);
            $this->cms->where('user_id', $user_id);

            $query = $this->cms->get('voting');

            return $this->cms->affected_rows();
        }

        function _check_voting_ip($site, $user_ip)
        {
            $this->cms->select('id');
            $this->cms->where('site', $site);
            $this->cms->where('user_ip', $user_ip);
            $query = $this->cms->get('voting');

            return $this->cms->affected_rows();
        }
        
        function select_user_vote_points($id)
        {
            $this->cms->select('vp');
            $this->cms->where('id', $id);
            $query = $this->cms->get('account_addition');
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    return $row['vp'];
                }
            }
            else
                return 0;
        }
        
        function select_user_date_vote_points($id)
        {
            $this->cms->select('date_vote_points');
            $this->cms->where('id', $id);
            $query = $this->cms->get('account_addition');
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    return $row['date_vote_points'];
                }
            }
            else
                return 0;
        }
        
        function add_points($id, $points)
        {
            $old_points = $this->select_user_vote_points($id);
            $old_date_vote_points = $this->select_user_date_vote_points($id);
            $new_points = $old_points + $points;
            $new_date_vote_points = $old_date_vote_points + $points;
            $today = date("Ymd");
            $data = array(
                'vp' => $new_points,
                'date_vote_points' => $new_date_vote_points,
                'date' => $today
            );
            $this->cms->where('id', $id);
            $query = $this->cms->update('account_addition', $data);

            return 1;
        }
        
        function add_voting($id, $user_ip, $user_id='0')
        {
            $data = array(
                'user_ip' => $user_ip,
                'user_id' => $user_id,
                'site' => $id,
                'time' => time()
            );
            $this->cms->insert('voting', $data);
        }
        
        function select_all_available_points()
        {
            $this->cms->select_sum('points');
            $query = $this->cms->get('vote_sites');
            $row = $query->row_array();
            return $row['points'];
        }
        
        function update_daily_votes($id, $points='0', $available_points='0')
        {
            $this->cms->where('id', $id);
            $today = date("Ymd");
            if($points!='0')
            {
               $data = array(
                    'vp' => $points,
                    'date_vote_points' => $available_points
                ); 
            }
            else
            {
                $this->cms->where('date !=', $today);
                $data = array(
                    'date' => $today,
                    'date_vote_points' => '0'
                );
            }
            $query = $this->cms->update('account_addition', $data);
        }

        function check_daily_votes($id)
        {
            $available_points = $this->select_all_available_points();
            $available_points = 2 * $available_points;
            $today = date("Ymd");
            $this->cms->select('date_vote_points, vp');
            $this->cms->where('id', $id);
            $query = $this->cms->get('account_addition', '1');
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    if($row['date_vote_points']>$available_points)
                    {
                        $new_points = $row['date_vote_points'] - $available_points;
                        $new_points = $row['vp'] - $new_points;
                        $this->update_daily_votes($id, $new_points, $available_points);
                        $this->update_daily_votes($id);
                    }
                    else
                    {
                        $this->update_daily_votes($id);
                    }
                }
            }
        }
        
        function delete_voting($id)
        {
            $this->cms->where('id', $id);
            $query = $this->cms->delete('voting');
        }
        
        function check_voting()
        {
            if($this->session->userdata('is_logged_session')==TRUE)
            {
                $this->check_daily_votes($this->session->userdata('id'));
            }
            $this->cms->select('id, time');
            $query = $this->cms->get('voting');
            if($query->num_rows() > 0)
            {
                $time = time();
                foreach ($query->result_array() as $row)
                {
                    if(($time - $row['time']) > 43200)
                    {
                        $id = $row['id'];
                        $this->delete_voting($id);
                    }
                }
            }
        }
        
        function _check_time_vote($user_ip, $user_id='0')
        {
            $this->cms->select_min('time');
            if($user_id!='0')
                $this->cms->where('user_id', $user_id);
            else
                $this->cms->where('user_ip', $user_ip);

            $query = $this->cms->get('voting', '1');

            if($query->num_rows() > 0)
            {
                $time = time();
                foreach ($query->result_array() as $row)
                {
                    $return = $row['time'] + '43200' - $time;
                    $hours = floor($return / 3600);
                    $mins = floor($return % 3600 / 60);
                    $sec = $return % 60;
                    return $hours.'h '.$mins.'m '.$sec.'s';
                }
            }
            else
                return 'Ready to vote';
        }
        
        function show_vote_sites()
        {
            $query = $this->cms->get('vote_sites');
            $cont = '';
            if($query->num_rows() > 0)
            {
                $i=0;
                $br=0;
                $user_ip = getenv("REMOTE_ADDR");
                $user_id = $this->session->userdata('id');
                foreach ($query->result_array() as $row)
                {
                    $id = $row['id'];
                    if($this->session->userdata('is_logged_session'))
                    {
                        if($this->_check_voting_user($id, $user_id)=='0' && $this->_check_voting_ip($id, $user_ip)=='0')
                        {
                            $i++;
                            $br++;
                            if($i=='1')
                                $cont .= 'Vote every 12 hours and get rewarded. After voting this box will disappear.<div class="clear"></div><ol style="list-style:none; display:inline;">';
                            $cont .= '<a href="'.base_url('index.php/vote/vote_site/'.$id).'" target="_blank"><li style="display: inline;"> '.$row['name'].'&nbsp;&nbsp;</li></a> ';
                        }
                    }
                    else
                    {
                        if($this->_check_voting_ip($id, $user_ip)=='0')
                        {
                            $i++;
                            $br++;
                            if($i=='1')
                                $cont .= 'Vote every 12 hours and get rewarded. If you are not logged in, you will not recieve vote points. After voting this box will disappear.<div class="clear"></div><ol style="list-style:none; display:inline;">';
                            $cont .= '<a href="'.base_url('index.php/vote/vote_site/'.$id).'" target="_blank"><li style="display: inline;"> '.$row['name'].'&nbsp;&nbsp;</li></a> ';
                        }
                    }

                }
                if($i=='0')
                {
                    if($this->session->userdata('is_logged_session'))
                    {
                        $time = $this->_check_time_vote($user_ip, $user_id);
                    }
                    else
                    {
                        $time = $this->_check_time_vote($user_ip, $user_id='0');
                    }
                    $cont .= '<span>Vote: minimum time to vote - '.$time.' </span></h5><br />';
                }
            }
            return $cont;
        }
}
?>