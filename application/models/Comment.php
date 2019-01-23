<?php

class Comment extends CI_Model {

	function __construct(){

		$this->load->database();
	}

	public function all(){

		
		return $this->db->get('comments')->result_array(); 
	}

	public function find($id){

		
		return $this->db->get_where('users', ['id', $id])->result_array();
		
	}

	

	public function create($comment_data){

		
		return $this->db->insert('comments', $comment_data);
	}

	
	public function delete($id){

		$this->db->delete('comments', ['id' => $id ]);
		return  $this->db->affected_rows();
	}

}