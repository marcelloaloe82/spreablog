<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Users extends REST_Controller {

    private $valid_keys = ["nome", "cognome", "email", "role_id", "password"];

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
        $this->load->library('auth');
        $this->load->model("user");
    }

    public function index_get($id=NULL)
    {
        // Users from a data store e.g. database
        /*$users = [
            ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
            ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
            ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
        ];*/


        $message_no_users = "Nessun utente trovato";


        if( $this->auth->check_ruolo("admin")){

            $users = ['data' => $this->user->all() ];

            

            // If the id parameter doesn't exist return all the users

            if ($id === NULL)
            {
                // Check if the users data store contains users (in case the database result returns NULL)
                if ($users)
                {
                    // Set the response and exit
                    $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
                else
                {
                    // Set the response and exit
                    $this->response([
                        'status' => FALSE,
                        'message' => $message_no_users
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }

            // Find and return a single record for a particular user.

            $id = (int) $id;

            // Validate the id.
            if ($id <= 0)
            {
                // Invalid id, set the response and exit.
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $user = NULL;

            if (!empty($users))
            {
                foreach ($users as $key => $value)
                {
                    if (isset($value['id']) && $value['id'] === $id)
                    {
                        $user = $value;
                    }
                }
            }

            if (!empty($user))
            {
                $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'User could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

         else{
            $this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
        }


    }

    public function ruoli_get(){

        if( $this->auth->check_ruolo("admin")){

            $ruoli = json_encode( $this->user->ruoli(), JSON_UNESCAPED_SLASHES);
            $this->set_response($ruoli, REST_Controller::HTTP_OK);
        }

        else{
            $this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
        }

    }

    

    public function create_post()
    {
        // $this->some_model->update_user( ... );

        if( $this->auth->check_ruolo("admin")){

            $parametri_utente   = $this->post();
            $message_error      = "Valore non valido per il parametro ";
            $message_ok         = "Utente creato correttamente";

            foreach ($parametri_utente as $key => $value) {
            

                if(in_array($key, $this->valid_keys)){

                    if(empty($value)){
                        $this->response(['message' => $message_error. $key ], REST_Controller::HTTP_BAD_REQUEST);
                    }

                    if($key == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                        $this->response(['message' => $message_error . $key ], REST_Controller::HTTP_BAD_REQUEST);
                    }

                    
                }

                else unset($parametri_utente[ $key ]);
            }

            
            $this->user->create($parametri_utente);

            $this->set_response(['message' => $message_ok], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
            
        }

        else $this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);

    }

    public function update_post(){

        if($this->auth->check_ruolo("admin")){
            
            $message;

            $arr_parametri = $this->post();

            foreach ($arr_parametri as $key => $value) {
                        
                if(in_array($key, $this->valid_keys)){

                    if(empty($value))
                        unset($arr_parametri[ $key ]);
                }
            }
            
            $update_result = $this->user->update($this->post("id"), $arr_parametri);

            if($update_result === 1)
                $message = "Dati utente aggiornati";

            else $message = "Nessun aggiornamento effettuato, controllare i dati inviati";
            

            $this->set_response(["message"=>$message], REST_Controller::HTTP_OK);

        }

        else {
            $this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
        }
        
           

    }



    public function delete_post()
    {

        if( $this->auth->check_ruolo("admin")){

            $id = (int) $this->post('id');

            $message = "Utente cancellato";

            $message_ko = "Errore interno del server";

            // Validate the id.
            if ($id <= 0){
                // Set the response and exit
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            
            if($this->user->delete($id))
                $this->set_response(['message' => $message], REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code

            else $this->response(["message" => $message_ko], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            
        }


        else {
            $this->set_response(NULL, REST_Controller::HTTP_FORBIDDEN);
        }


    }

}
