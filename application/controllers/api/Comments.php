<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Comments extends REST_Controller {

	function __construct(){
        // Construct the parent class
        parent::__construct();

        $this->load->library('session');
        $this->load->library('auth');
        $this->load->model('comment');
    }

    //lista dei commenti
    function index_get(){

    	if(empty($this->session->user)){
    		$this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
    	}

    	$user_id = $this->session->user['id'];

    	$comments = $this->comment->all($user_id);

    	foreach ($comments as $key => $row) {
    		
    		$comments[$key]['approva'] = sprintf("<a href=\"%sindex.php/api/comments/approve/%s\"><span class=\"glyphicon glyphicon-check\"></span></a>", base_url(), $comments[$key]['id']);
			
			$comments[$key]['cancella'] = sprintf("<a href=\"%sindex.php/api/comments/delete/%s\"><span class=\"glyphicon glyphicon-remove	\"></span></a>", base_url(), $comments[$key]['id']);
			
			$comments[$key]['view_comment'] = "<a href='#'><span class=\"glyphicon glyphicon-eye-open\"></span></a>";
			
    		unset($comments[$key]['id']);
    	}

    	$this->set_response($comments, REST_Controller::HTTP_OK);
    }

    
    public function save_post(){

        $message_ok = 'Commento inviato per l\'approvazione';
        $message_captcha = 'Occorre cliccare il captcha per inviare il commento';

        $comment_data = $this->input->post();

        $captcha_key = 'g-recaptcha-response';


        if(!in_array($captcha_key, array_keys($comment_data))) {
            $this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $secret = "6LffHIwUAAAAAA96PfNPKALU9ZXDqcsjsEbWQecK";
        $data = array('response' => $this->input->post($captcha_key) , 'secret' => $secret);
                
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result === FALSE)  {
            
            $this->set_response(['message' => $message_captcha], REST_Controller::HTTP_FORBIDDEN);
        }

        unset($comment_data[$captcha_key]);   
        $comment_data['ip_address'] = $this->input->ip_address();     

        $this->comment->save($comment_data);

        $this->set_response(['message'=>$message_ok], REST_Controller::HTTP_OK);

    }
    

    public function approve_post($comment_id){

    	if(empty($this->session->user)){
    		$this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
    	}

    	$this->comment->approve($comment_id);

    }


    
    public function delete_post($comment_id){

    	if(empty($this->session->user)){
    		$this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
    	}

        $this->comment->delete($comment_id);

    }

}