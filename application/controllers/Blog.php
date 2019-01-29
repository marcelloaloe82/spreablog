<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	
	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('user');
        $this->load->model('news_model');
        $this->load->model('comment');
        $this->load->library('session');

    }

	public function index($slug='')	{

		
		if(!$slug){
			
			$page_data = [];

			if(!empty($this->session->user)){

				$ruolo_utente = $this->user->get_ruolo( $this->session->user['role_id']);
				$page_data['ruolo_utente'] = $ruolo_utente;

			}

			$page_data['news'] = $this->news_model->paged_news(0);
			
			$comments = $this->comment->all();

			$page_data['comments'] = $comments;

			foreach ($page_data['comments'] as $index=>$comment) {
							
				$page_data['comments'][$index]['replies'] = $this->comment->get_comment_replies($comment['id']);
				
			}

			$page_data['recaptcha'] = true;
			$page_data['editor'] = false;
			$page_data['csrf'] = ['name' => $this->security->get_csrf_token_name(),
								  'hash' => $this->security->get_csrf_hash()];
			
			$this->load->view('head', $page_data);
			$this->load->view('blog_page', $page_data);
		}
	}
}
