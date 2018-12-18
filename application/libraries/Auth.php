<?php

class Auth{

	public function __construct(){

		$this->CI =& get_instance();
		$this->CI->load->library('session');
		$this->CI->load->model('user');

	}
	
	public function check_ruolo($ruolo_da_controllare){

        if(!$this->CI->session->has_userdata('user'))
            return FALSE;

        $ruolo_utente = $this->CI->user->get_ruolo($this->CI->session->user['role_id']);

        return $ruolo_utente == $ruolo_da_controllare;
    }
}