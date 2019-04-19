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
        
        if($this->session->user)
			$this->ruolo_utente = $this->user->get_ruolo( $this->session->user['role_id']);

		else $this->ruolo_utente = '';

    }

	public function index()	{

		
		$page_data = [];

		if(empty($this->session->user)){

			$this->load->view('login_page');

			return;


		} else {

			$page_data['ruolo_utente'] = $this->ruolo_utente;
			$page_data['page_title'] = 'Sprea News';
			$page_data['news'] = $this->news_model->paged_news(0);
			
			$page_data['comments'] = $this->comment->all();


			foreach ($page_data['comments'] as $index=>$comment) {
							
				//var_dump($this->comment->get_comment_replies($comment['id'])); 
				$page_data['comments'][$index]['replies'] = $this->comment->get_comment_replies($comment['id']);
				
			}

			$page_data['recaptcha'] = true;
			$page_data['editor'] = false;
			$page_data['csrf'] = ['name' => $this->security->get_csrf_token_name(),
								  'hash' => $this->security->get_csrf_hash() ];
			
			$this->load->view('parti/head', $page_data);
			$this->load->view('blog_page', $page_data);
		
		}	

				
	}

	public function view($slug=NULL){


		if($slug != NULL){

			$page_data['page_title'] = 'Sprea News';
			$page_data['ruolo_utente'] = $this->ruolo_utente;

			$news_id = $this->news_model->get_news_id_from_slug($slug);
			$page_data['single_news'] = $this->news_model->get($news_id);
			$page_data['comments'] = $this->comment->get_news_comments($news_id);

			
			foreach ($page_data['comments'] as $key => $comment) {

				$replies = $this->comment->get_comment_replies($comment['id']);
				
				if($replies)
					$page_data['comments'][$key]['replies'] = $replies;

				else $page_data['comments'][$key]['replies'] = [];
			}

			//echo '<pre>' . print_r($page_data['comments'], true ) . '</pre>'; die;

			$page_data['recaptcha'] = true;
			$page_data['editor'] = false;
			$page_data['csrf'] = ['name' => $this->security->get_csrf_token_name(),
								  'hash' => $this->security->get_csrf_hash()];
			
			$this->load->view('parti/head', $page_data);
			$this->load->view('single_news', $page_data);
		}
	}
}
