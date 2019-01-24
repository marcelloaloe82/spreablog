<?php

class News_model extends CI_Model {

	function __construct(){

		$this->load->database();
	}

	public function all(){
		
		$this->db->where('status', 'published');
		$this->db->order_by('created_at', 'desc');
		return $this->db->get('news')->result_array();
	}

	
	public function get($id){

		return $this->db->get_where('news', ['id' => $id])->result_array()[0];
	}

	public function paged_news($start){

		$this->db->where('status', 'published');
		$this->db->order_by('created_at', 'desc');
		$this->db->limit(NEWS_PAGE_SIZE, $start); 
		return $this->db->get_where('news')->result_array();
	}


	public function last_news(){

		$last_id = $this->db->insert_id();

		$this->db->select('content, title');
		$this->db->where('id', $last_id);

		return $this->db->get('news')->result_array()[0];
	}

	public function create($news_data){

		return $this->db->insert('news', $news_data);
	}

	public function update($id, $news_data){

		$this->db->set($news_data);
		$this->db->where('id', $id);
		
		return $this->db->update('news');
	}

	public function delete($id){

		return $this->db->delete('news', array('id' => $id ));
	}

}