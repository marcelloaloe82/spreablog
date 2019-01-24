<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct(){
        // Construct the parent class
        parent::__construct();
        $this->load->library('session');
    }


	public function index(){

		$data = [];

		$data['recaptcha'] = false;
		
		if($this->session->user){
			
			$ruolo_utente = $this->user->get_ruolo( $this->session->user['role_id']);

			if($ruolo_utente == 'admin'){

				$data['editor'] = false;
				$this->load->view('head', $data);
				$this->load->view('admin_page', $data);
				
			}

			elseif($ruolo_utente == 'editor'){
				
				$data['editor'] = true;			

				$this->load->view('head', $data);
				$this->load->view('editor_page', $data);
			}

		
		}else {

			$data['editor'] = false;
			$this->load->view('head', $data);
			$this->load->view('login_admin_page');
		}
		

	}


}
