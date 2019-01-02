<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
       
        $this->load->model('user');
        $this->load->model('news_model');
        $this->load->library('session');

        $this->session->set_userdata('news_offset', 0);
    }

	public function index()
	{

		$page_data = [];

		if(!empty($this->session->user)){

			$ruolo_utente = $this->user->get_ruolo( $this->session->user['role_id']);
			$page_data['ruolo_utente'] = $ruolo_utente;

			if($ruolo_utente == 'admin'){

				$page_data['utenti']	= $this->user->all();
				$page_data['ruoli']		= $this->user->ruoli();

			}
		}

		$page_data['news'] = $this->news_model->paged_news(0);
		
		$this->load->view('blog_page', $page_data);
	}
}
