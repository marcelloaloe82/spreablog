<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class News extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->library('session');
        $this->load->model("news_model");

        $this->session->set_userdata('offset', 0);
    }

    public function all_get($id)
    {
        
        $news = json_encode($this->news_model->all());


        if (!empty($news))
        {
            $this->set_response($news, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        
    }

    public function nextpage(){

        $this->session->set_userdata('offset', $this->session->offset + 10);

        $news = $this->news_model->paged_news($this->session->offset);

        if (!empty($news))
        {
            $this->set_response($news, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                
                'message' => 'Nessuna news trovata'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
        
    }

    public function prevpage(){

        $this->session->set_userdata('offset', $this->session->offset - 10);

        $news = $this->news_model->paged_news($this->session->offset);

        if (!empty($news))
        {
            $this->set_response($news, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                
                'message' => 'Nessuna news trovata'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }


    public function create_draft_post()
    {
        if($this->session->user){

            $news_data = [
                'title'     => $this->post('title'),
                'content'   => $this->post('content'),
                'status'    => 'draft',
                'author_id' => $this->session->user['id']
            ];

            $message_ko = "Non è stato possibile salvare. Errore interno del server";
            
            $message_ok = "Bozza salvata";
            

            if($this->news_model->create( $news_data )) {
                
                $last_news = $this->news_model->last_news();
                
                $this->set_response(['message'  => $message, 
                                    'content'   => $last_news['content'],
                                    'title'     => $last_news['title']
                                    ], 
                                    REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
            }

            else $this->response(['message'=>$message_ko], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            
        }

        else $this->response(NULL, REST_Controller::HTTP_FORBIDDEN);

    }

    public function update_post(){

         if($this->session->user){

             $news_data = [
                'title'     => $this->post('title'),
                'content'   => $this->post('content'),
                'status'    => $this->post('status'),
                'author_id' => $this->session->user['id']
            ];

            $id            = $this->post('id');

            $message_ok = "Aggiornamento effettuato";
            
            $message_ko = "Errore interno del server";
            $message_err_id = "Nessuna news selezionata";
            

            if(empty($id))
                $this->response(["message"=>$message_err_id], REST_Controller::HTTP_BAD_REQUEST);

            
            
            if($this->news_model->update($id, $news_data) )
                $this->set_response(["message"=>$message_ok], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code

            else $this->response(["message"=>$message_ko], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            
         }

         else $this->response(NULL, REST_Controller::HTTP_FORBIDDEN);

    }


    public function create_news_post(){

        if($this->session->user){

            $news_data = [
                'title'     => $this->post('title'),
                'content'   => $this->post('content'),
                'author_id' => $this->session->user['id'],
                'status'    => 'published'
            ];

            $message_ok         = "News pubblicata";
            $message_ko         = "Non è stato possibile salvare. Errore interno del server";
            $message_no_content = "Manca il contenuto della news";

            if(empty($news_data['content'])){
                $this->response(["message"=>$message_no_content], REST_Controller::HTTP_BAD_REQUEST);
            }
            

            if($this->news_model->create( $news_data )){

                $last_news = $this->news_model->last_news();

                $this->set_response(['message'  => $message_ok, 
                                    'content'   => $last_news['content'],
                                    'title'     => $last_news['title']
                                    ],  
                                    REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
            }

            else $this->response(["message"=>$message_ko], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

        }

        else $this->response(NULL, REST_Controller::HTTP_FORBIDDEN);

    }

    public function update_draft_post(){


        if($this->session->user){

            $news_data = [
                'title'     => $this->post('title'),
                'content'   => $this->post('content'),
                'author_id' => $this->session->user['id']
            ];

            $id =  $this->post('id');
            $message_ok = "News aggiornata";
            
            $message_ko = "Errore interno del server";

            $message_err_id = "Nessuna news selezionata";

            if(empty($id))
                $this->response(["message"=>$message_err_id], REST_Controller::HTTP_BAD_REQUEST);

            
            if($this->news_model->update( $id, $news_data))
                $this->set_response(["message"=>$message_ok], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code

            else $this->response(["message"=>$message_ko], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }

        else $this->response(NULL, REST_Controller::HTTP_FORBIDDEN);
    }

    public function delete_post()
    {
        
        if($this->session->user){

            $id = $this->post('id');

            $message = "News cancellata";

            $message_ko = "Errore interno del server";

            // Validate the id.
            if (empty($id)){
                // Set the response and exit
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            
            if($this->news_model->delete($id))
                $this->set_response(["message"=>$message], REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code

            else $this->response(["message"=>$message_ko], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
       }

        else $this->response(NULL, REST_Controller::HTTP_FORBIDDEN);
    }

}
