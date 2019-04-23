<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct(){
        // Construct the parent class
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user');
        $this->load->model('news_model');

        if($this->session->user)
			$this->ruolo_utente = $this->user->get_ruolo( $this->session->user['role_id']);

		else $this->ruolo_utente = '';
    }


	public function index(){

		$data = [];

		$data['recaptcha'] = false;

		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);

		$data['page_title'] = 'Sprea News | Pannello admin';
		$data['user_id'] = 	$this->session->user['id'];
		
		if($this->session->user){
			
			
			$data['ruolo_utente'] = $this->ruolo_utente;

			if($this->ruolo_utente == 'admin'){

				$data['users'] = $this->user->utenti_ruoli(['admin', 'editor']);
				$data['editor'] = true;
				$data['ruoli'] = $this->user->ruoli();
				$data['tab_editor'] = $this->load->view('parti/tab_editor', $data, true);
				$data['comments_modals'] = $this->load->view('parti/comments_modals', $data, true);

				$this->load->view('parti/head', $data);
				$this->load->view('admin_page', $data);
				
			}

			elseif($this->ruolo_utente == 'editor'){
				
				$data['users'] = $this->user->utenti_ruoli(['admin', 'editor']);
				$data['editor'] = true;		
				$data['tab_editor'] = $this->load->view('parti/tab_editor', $data, true);
				$data['comments_modals'] = $this->load->view('parti/comments_modals', $data, true);

				$this->load->view('parti/head', $data);
				$this->load->view('editor_page', $data);
			}

		
		}else {

			$data['editor'] = false;
						
			$this->load->view('parti/head', $data);
			$this->load->view('login_page');
		}
		

	}

	

	public function edit_news($news_id){

		if(empty($this->session->user))
			redirect('/Admin');

		$news_data = $this->news_model->get($news_id);

		$head_data['recaptcha'] = false;
		$head_data['editor'] = true;
		$head_data['page_title'] = 'Sprea News | Pannello admin';

		$news_data['user_id'] = $this->session->user['id'];

		if($this->ruolo_utente == 'admin')
			$view = 'admin_page';

		else $view = 'editor_page';

		$news_data['ruolo_utente'] = $this->ruolo_utente;

		$news_data['csrf'] = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
		);

		$news_data['users'] = $this->user->utenti_ruoli(['admin', 'editor']);
		$news_data['tab_editor'] = $this->load->view('parti/tab_editor', $news_data, true);
		$news_data['comments_modals'] = $this->load->view('parti/comments_modals', $news_data, true);

		$this->load->view('parti/head', $head_data);
		$this->load->view($view, $news_data);

	}

	public function session(){

		echo '<pre>' . print_r($this->session->user, true) . '</pre>';
	}


}
