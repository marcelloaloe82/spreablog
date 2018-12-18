<?php

class User extends CI_Model {

	function __construct(){

		$this->load->database();
	}

	public function all(){

		return $this->db->get('users')->result();
	}

	public function find($username, $password){

		$this->db->select('id, nome, cognome, email, role_id');
		return $this->db->get_where('users', array('email' => $username, 'password' => $password))->result_array();
	}

	public function ruoli(){

		return $this->db->get('roles')->result_array();
	}

	public function get_id_ruolo($ruolo){

		$this->db->select('id');
		return $this->db->get_where('roles', ["name" => $ruolo])->result_array()[0]['id'];		
	}

	public function get_ruolo($id_ruolo){

		return $this->db->get_where('roles', ["id" => $id_ruolo])->result_array()[0]['name'];
	}

	public function create($user_data){

		
		$user_data['password'] = hash("sha256", SALT . $user_data['password']);
		return $this->db->insert('users', $user_data);
	}

	public function update($id, $user_data){

		$this->db->set($user_data);
		$this->db->where('id', $id);
		
		$this->db->update('users', $user_data); 

		return $this->db->affected_rows();
	}

	public function delete($id){

		return $this->db->delete("users", array('id' => $id ));
	}

}