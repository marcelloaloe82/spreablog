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
        $this->load->model('news_model');
        $this->load->model('user');
        $this->load->library('slug');

       
    }

    

    public function nextpage_get($start){


        $news = $this->news_model->paged_news($start);

        $html_news = "<div class=\"row\">
            <div class=\"col-sm-12\">
              <h2>%s</h2>
              %s
              <div class=\"news-content\" data-post-id=\"%s\">
              %s
              </div>
              %s %s
            </div>
          </div>
          <hr>";

        $arr_html_news = []; 

        if($this->session->user)

            $ruolo_utente = $this->user->get_ruolo( $this->session->user['role_id']);

        if (count($news) > 0){

            if(!empty($ruolo_utente) && $ruolo_utente == 'editor'){

              $button_modifica = "<button class='btn btn-primary edit-button'>Modifica</button>";
              $button_elimina  = "<button class='btn btn-primary btn-danger delete-news-button'>Elimina</button>";

            } else{

                $button_modifica = "";
                $button_elimina  = "";
      
            }
            
            foreach($news as $single_news) {


                $data_pubblicazione = "<h4>Pubblicato il: ". @strftime("%d %B %Y ",  strtotime($single_news['created_at'])) . "</h4>";

                $arr_html_news[] = sprintf($html_news, 
                                           $single_news['title'], 
                                           $data_pubblicazione, 
                                           $single_news['id'],
                                           $single_news['content'],
                                           $button_modifica,
                                           $button_elimina);
          
            }


            $str_html_news = implode("", $arr_html_news);
            
            $this->set_response($str_html_news, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        
        } else {
            
            $this->set_response(array(), REST_Controller::HTTP_OK); 
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


    public function create_post(){

        if($this->session->user){

            $news_data = [
                'title'     => $this->post('title'),
                'content'   => $this->post('content'),
                'author_id' => $this->session->user['id'],
                'slug'      => $this->slug->create_uri($this->post('title')),
                'status'    => 'published',
                'created_at'=> @strftime("%Y-%m-%d %H:%M") 
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
                $this->set_response(["message"=>$message_ok], REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code

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
