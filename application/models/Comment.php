<?php

class Comment extends CI_Model {

	function __construct(){

		$this->load->database();
	}

	public function all($user_id){

		$this->db->select('comments.id as id, display_name as name, email, ip_address, comments.content as content,  title as news');
		$this->db->from('comments');
		$this->db->join('news', 'news.id = comments.news_id');
		$this->db->where(['approved'=> 0, 'author_id'=>$user_id]);
		
		return $this->db->get()->result_array(); 

		//var_dump($this->db->last_query());


	}

	public function find($id){

		
		return $this->db->get_where('comments', ['id', $id])->result_array();
		
	}

	public function comment_content($id){

		return $this->db->get_where('comments', ['id'=>$id])->first_row()->content;
	}

	public function get_news_comments($news_id){

		return $this->db->get_where('comments', ['news_id', $news_id])->result_array();
	}

	
	public function approve($id){

		$this->db->set('approved', 1);
		$this->db->where('id', $id);
		$this->db->update('comments');
	}

	public function create($comment_data){

		
		return $this->db->insert('comments', $comment_data);
	}

	
	public function delete($id){

		$this->db->delete('comments', ['id' => $id ]);
		return  $this->db->affected_rows();
	}

}