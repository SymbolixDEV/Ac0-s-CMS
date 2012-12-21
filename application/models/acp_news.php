<?php
class Acp_news extends CI_Model
{
        public function __construct() 
        {
            parent::__construct();
            $this->cms = $this->load->database('default', TRUE);  
        }
        
        function return_news()
        {
            $this->cms->order_by('id', 'asc'); 
            $query = $this->cms->get('news');
            $cont = '';
            foreach ($query->result_array() as $row)
            {
                $cont .= '<div class="menage_news"><div class="content"><div class="news_id">'.$row['id'].'</div><div class="news_title">'.$row['news_title'].'</div><div class="news_options"><a class="edit" href="'.base_url('index.php/acp/news/index/edit/'.$row['id']).'">Edit</a> - <a class="delete" href="'.base_url('index.php/acp/news/index/delete/'.$row['id']).'">Delete</a></div><div class="clear"></div></div></div>';
            }
            
            return $cont;
        }
        
        function return_new($id)
        {
            $this->cms->where('id', $id);
            $query = $this->cms->get('news');
            $cont = '';
            foreach ($query->result_array() as $row)
            {
                $hidden = array('news_id' => $row['id']);
                $cont .= $this->session->flashdata('not_filled');
                $cont .= form_open('index.php/acp/news/edit_validation', '', $hidden);
                $data = array(
                    'name'        => 'news_title',
                    'id'          => 'news_title',
                    'value'       => $row['news_title'],
                    );
                $cont .= '<label for="news_title">News Title</label>: <br />'.form_input($data);
                $data = array(
                    'name'        => 'news_content',
                    'id'          => 'news_content',
                    'value'       => $row['news'],
                    'style'       => 'width: 655px;min-width: 655px;max-width:655px;'
                    );
                $cont .= '<br /><label for="news_content">News Content</label>: <br />'.form_textarea($data);
                $data = array(
                    'name'        => 'news_edit_submit',
                    'id'          => 'news_edit_submit',
                    'value'       => 'Submit',
                    );
                $cont .= '<br />'.form_submit($data);
                $cont .= form_close();
            }
            
            return $cont;
        }
        
        function update_new($id,$title,$content)
        {
            $this->cms->where('id', $id);
            $data = array(
                'news_title'        => $title,
                'news'          => $content
            );
            $query = $this->cms->update('news',$data);
        }
        function add_new($title,$content)
        {
            $today = date("M d, Y");
            $data = array(
                'news_date' => $today,
                'poster_id' => $this->session->userdata('id'),
                'news_title'        => $title,
                'news'          => $content
            );
            $query = $this->cms->insert('news',$data);
        }
        
        function delete_news($id)
        {
            $this->cms->where('id', $id);
            $query = $this->cms->delete('news');
        }
}
?>