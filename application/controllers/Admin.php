<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct(){
        // Construct the parent class
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user');
        $this->load->model('news_model');
    }


	public function index(){

		$data = [];

		$data['recaptcha'] = false;

		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		if($this->session->user){
			
			$ruolo_utente = $this->user->get_ruolo( $this->session->user['role_id']);

			if($ruolo_utente == 'admin'){

				$data['editor'] = false;
				$data['ruoli'] = $this->user->ruoli();
				$this->load->view('head', $data);
				$this->load->view('admin_page', $data);
				
			}

			elseif($ruolo_utente == 'editor'){
				
				$data['editor'] = true;		
				$data['user_id'] = 	$this->session->user['id'];

				$this->load->view('head', $data);
				$this->load->view('editor_page', $data);
			}

		
		}else {

			$data['editor'] = false;
						
			$this->load->view('head', $data);
			$this->load->view('login_admin_page');
		}
		

	}

	

	public function edit_news($news_id){

		$news_data = $this->news_model->get($news_id);

		$head_data['recaptcha'] = false;
		$head_data['editor'] = true;

		$news_data['csrf'] = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
		);

		$this->load->view('head', $head_data);
		$this->load->view('editor_page', $news_data);

	}


}
