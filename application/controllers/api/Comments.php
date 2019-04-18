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
        $this->load->library('email');
        
        $this->load->model('comment');
        $this->load->model('news_model');
    }

    //lista dei commenti
    function index_get($user_id){

    	if(!empty($this->session->user)){
    		

        	//$user_id = $this->session->user['id'];

        	$comments = $this->comment->all($user_id);

        	foreach ($comments as $key => $row) {
        		
        		$comments[$key]['rispondi'] = sprintf("<a href=\"%sindex.php/api/comments/reply/%s\"><span class=\"glyphicon glyphicon-send\"></span></a>", base_url(), $comments[$key]['id']);
    			
    			$comments[$key]['cancella'] = sprintf("<a href=\"%sindex.php/api/comments/delete/%s\"><span class=\"glyphicon glyphicon-remove	\"></span></a>", base_url(), $comments[$key]['id']);
    			
    			$comments[$key]['view_comment'] = "<a href='#'><span class=\"glyphicon glyphicon-eye-open\"></span></a>";

                unset($comments[$key]['id']);
        	}

            if(count($comments) === 0)
                $comments = [];

        	$this->set_response($comments, REST_Controller::HTTP_OK);


        } else{

            $this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
        }
    }

    
    public function save_post(){

        $message_ok = 'Commento inviato per l\'approvazione';
        $message_captcha = 'Occorre cliccare il captcha per inviare il commento';
        $message_forbidden = 'Operazione non consentita';

        $comment_data = $this->input->post();

        $captcha_key = 'g-recaptcha-response';


        if($this->input->post($captcha_key)) {
            

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            
            $data = array('response' => $this->input->post($captcha_key) , 'secret' => CAPTCHA_SECRET);
                    
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

            
            try{
                $this->comment->save($comment_data);
                $this->set_response(['message'=>$message_ok], REST_Controller::HTTP_OK);
                $this->notifica_email($comment_data['news_id']);

            }catch(Exception $e){

                $error_message = $e->getMessage();
                $this->set_response(['message'=>$error_message], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }


        } else{

        	if($comment_data){
            	
            	$this->set_response(['message'=>$message_captcha], REST_Controller::HTTP_FORBIDDEN);	
            	
        	} else {

        		$this->set_response(['message'=>$message_forbidden], REST_Controller::HTTP_FORBIDDEN);
        	}
        }
    }


    private function notifica_email($news_id){

        $news_data = $this->news_model->get($news_id);

        $author = $this->news_model->get_news_author($news_id);

        $email_subject = 'Nuovo commento alla news ' . $news_data['title'];
        $email_from = 'nonrisponderea@sprea.it';
        $email_from_name = 'Sprea news';
        $email_message = "C'è un nuovo commento alla news ${news_data['title']}\nVai al pannello amministratore per moderare il commento";

        $this->email->to($author->email);
        $this->email->from($email_from, $email_from_name);
        $this->email->subject($email_subject);
        $this->email->message($email_message);

        $this->email->send();

    }


    public function reply_post($comment_id){

        $message_ok = 'Risposta inviata';
        $error_message = 'Operazione non consentita';

    	if(!empty($this->session->user)){
    		
            try{

        	   $this->comment->reply($comment_id, $this->input->post());
               $this->set_response(['message'=>$message_ok], REST_Controller::HTTP_OK);
            
            }catch(Exception $e){

                $error_message = $e->getMessage();
                $this->set_response(['message'=>$error_message], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        
        } else{

            $this->set_response(['message'=>$error_message], REST_Controller::HTTP_FORBIDDEN);
        }


    }


    
    public function delete_post($comment_id){

        $message_ok = 'Commento cancellato';
        $error_message = 'Operazione non consentita';

    	if(!empty($this->session->user)){
            
            try{
                $this->comment->delete($comment_id);
                $this->set_response(['message'=>$message_ok], REST_Controller::HTTP_OK);
            
            } catch(Exception $e){

                $error_message = $e->getMessage();
                $this->set_response(['message'=>$error_message], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }

        } else{

            $this->set_response(['message'=>$error_message], REST_Controller::HTTP_FORBIDDEN);
        }




    }

}