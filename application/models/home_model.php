<?php
class Home_model extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->news = $this->load->database('default', TRUE);  
        } 
    
	function show_news($id, $start, $limit)
	{
            $this->news->order_by('id', 'desc');
            if($id!='0')
            {
                $this->news->where('id', $id);
                $query = $this->news->get('news', '1');
            }
            else
                $query = $this->news->get('news', $limit, $start);
            
            $cont = '';
            if($query->num_rows() > 0)
            {
                $i = 0;
                foreach ($query->result_array() as $row)
                {
                    $i++;
                    $comments = array();
                    $cont[$i]['comment_form'] = array();
                    if($id=='0')
                    {
                        $news_content = $this->limit_string($row['news'], 450, $row['id']);
                    }
                    else
                    {
                        $news_content = parse_smileys($row['news'], base_url('content/img/smileys/'));
                        $comments = $this->_article_comments($row['id'], $start, $limit);
                        $cont[$i]['comment_form'] = array(array());
                    }
                    $cont[$i]['news_id'] = $row['id'];
                    $cont[$i]['news_title'] = '<a href="'.base_url('index.php/home/index/'.$row['id']).'">'.$row['news_title'].'</a>';
                    $cont[$i]['news_content'] = $news_content;
                    $cont[$i]['news_poster'] = '<a href="'.base_url('index.php/profile/index/'.$row['poster_id']).'">'.$this->_get_potser_name($row['poster_id']).'</a>';
                    $cont[$i]['news_date'] = $row['news_date'];
                    $cont[$i]['news_comments_count'] = '<a href="'.base_url('index.php/home/index/'.$row['id'].'#comments').'">'.$this->_get_news_comments($row['id']).'</a>';
                    
                    if(!empty($comments))
                    {
                        $o = 0;
                        foreach($comments as $comment)
                        {
                            $o++;
                            $cont[$i]['news_comments'][$o]['comment_poster'] = '<a href="'.base_url('index.php/profile/index/'.$comment['poster_id']).'">'.$this->_get_potser_name($comment['poster_id']).'</a>';
                            $cont[$i]['news_comments'][$o]['comment'] = parse_smileys($comment['comment'], base_url('content/img/smileys/'));
                            $cont[$i]['news_comments'][$o]['comment_date'] = $comment['date'];
                        }
                    }
                    else
                        $cont[$i]['news_comments'] = array();
                }
            }
            return $cont;			
	}
        
        function _article_comments($id='0', $start, $limit)
        {
            if($id=='0')
                return;
            $this->news->order_by('id', 'desc');
            $this->news->where('news_id', $id);
            $query = $this->news->get('comments', $limit, $start);
            
            return $query->result_array();
        }
        
        function select_user_posts($id)
        {
            $this->news->select('posts');
            $this->news->where('id', $id);
            $query = $this->news->get('account_addition', '1');
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    return $row['posts'];
                }
            }
            else
                return 0;
        }
        
        function add_comment($id, $text, $poster_id)
        {
            $today = date("M d, Y");
            $data = array(
                'news_id' => $id,
                'poster_id' => $poster_id,
                'comment' => mysql_real_escape_string($text),
                'date' => $today
                );
            $data2 = array(
                'posts' => $this->select_user_posts($poster_id)+1
                );
            $query = $this->news->insert('comments', $data);
            $this->news->where('id', $poster_id);
            $query2 = $this->news->update('account_addition', $data2);
        }
	
	function limit_string($string, $charlimit, $id)
        {
            if(strlen($string)>$charlimit)
            {
                if(substr($string,$charlimit-1,1) != ' ')
                {
                    $string = substr($string,'0',$charlimit);
                    $array = explode(' ',$string);
                    array_pop($array);
                    $new_string = implode(' ',$array);

                    return parse_smileys($new_string, base_url('content/img/smileys/')).' <a href="'.base_url('home/article/'.$id).'">...</a>';
                }
                else
                {   
                    return parse_smileys(substr($string,'0',$charlimit-1), base_url('content/img/smileys/')).' <a href="'.base_url('home/article/'.$id).'">...</a>';
                }
            }
            else
                return parse_smileys($string, base_url('content/img/smileys/'));
        } 
	
	function _get_news_comments($id='')
	{
		if($id=='')
			return;
		$this->news->select('id');
		$this->news->where('news_id', $id);
		$query = $this->news->get('comments');
		
		return $query->num_rows();
	}
	
	function _get_potser_name($id='')
	{
		if($id=='')
			return;
		$this->news->select('username');
		$this->news->where('id', $id);
		$query = $this->news->get('account_addition');
		if($query->num_rows() > 0)
			foreach ($query->result_array() as $row)
				return $row['username'];
		else
			return 'Admin';
	}
	
	function show_news_rows()
	{
		$this->news->select('id');
		$query = $this->news->get('news');
		
		return $query->num_rows();
	}
    
	function show_comment_rows($news_id)
	{
		$this->news->select('id');
		$this->news->where('news_id', $news_id);
		$query = $this->news->get('comments');
		
		return $query->num_rows();
	}
}
?>