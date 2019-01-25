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

    function index_get(){

    	if(empty($this->session->user)){
    		$this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
    	}

    	$user_id = $this->session->user['id'];

    	$comments = $this->comment->all($user_id);

    	foreach ($comments as $key => $row) {
    		
    		$comments[$key]['approva'] = sprintf("<a href=\"%sindex.php/api/comments/approve/%s\"><span class=\"glyphicon glyphicon-check\"></span></a>", base_url(), $comments[$key]['id']);
			
			$comments[$key]['cancella'] = sprintf("<a href=\"%sindex.php/api/comments/delete/%s\"><span class=\"glyphicon glyphicon-remove	\"></span></a>", base_url(), $comments[$key]['id']);
			
			$comments[$key]['view_comment'] = sprintf("<a href=''><span class=\"glyphicon glyphicon-eye-open\"></span></a>", base_url(), $comments[$key]['id']);
			
    		unset($comments[$key]['id']);
    	}

    	$this->set_response($comments, REST_Controller::HTTP_OK);
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

    }

}