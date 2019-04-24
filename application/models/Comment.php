<?php

class Comment extends CI_Model {

	function __construct(){

		$this->load->database();
	}

	public function all($user_id=''){

		$this->db->from('comments');
		
		if($user_id){
		
			$this->db->select('news.id, comments.id as id, display_name as name, email, ip_address, comments.content as content,  title as news');
			$this->db->join('news', 'news.id = comments.news_id');
			$this->db->where(['approved'=> 0, 'author_id'=>$user_id]);
		
			$result_comments = $this->db->get()->result_array();

			$this->db->select('news.id, comments.id as id, display_name as name, email, ip_address, comments.content as content,  title as news, interested_authors');
			$this->db->from('comments');
			$this->db->join('news', 'news.id = comments.news_id');
			$this->db->where('interested_authors IS NOT NULL');
			$this->db->where('approved', 0);

			$result_interested = $this->db->get()->result_array();

			foreach ($result_interested as $key => $row_interested) {

				$gia_presente = false;
				$interested_authors_exploded = explode(',', $row_interested['interested_authors']);

				if(in_array($user_id, $interested_authors_exploded)){

					unset($row_interested['interested_authors']);
					$result_comments[] = $row_interested;
					
				}
			}

			return $result_comments;
		
		} else{
		
			$this->db->where('approved = 1 and reply_to IS NULL');
			return $this->db->get()->result_array();
		}

		
		 

		//var_dump($this->db->last_query());

	}



	public function find($id){

		return $this->db->get_where('comments', ['id'=>$id])->first_row();
		
	}

	public function comment_content($id){

		return $this->db->get_where('comments', ['id'=>$id])->first_row()->content;
	}

	
	public function get_news_comments($news_id){

		return $this->db->get_where('comments', "news_id=$news_id and news_id is not null and reply_to is null and approved = 1")->result_array();
		
	}

	public function get_comment_replies($comment_id){

		return $this->db->get_where('comments', "reply_to=$comment_id and reply_to is not null")->result_array();
	}

	
	public function reply($id, $comment_data){

		$this->db->set('approved', 1);
		$this->db->where('id', $id);
		$this->db->update('comments');

		$comment_data['news_id'] =  $this->find($id)->news_id;
		$comment_data['reply_to'] =  $id;
		
		$this->db->insert('comments', $comment_data);
	}

	public function save($comment_data){

		
		return $this->db->insert('comments', $comment_data);
	}

	
	public function delete($id){

		$this->db->delete('comments', ['id' => $id ]);
		return  $this->db->affected_rows();
	}

}