<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Auth extends REST_Controller {

    private $valid_keys = ['username', 'password'];

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model('user');
        $this->load->library('session');
    }

    public function login_post()
    {
        
        $message_invalid_user = "Dati di autenticazione non validi";
        $message_user_authenticated = "Autenticazione effettuata";

        $dati = $this->post();

        array_walk($dati, function($item, $key){


            $message_empty_param = "Valore non valido per il parametro ";

            if(in_array($key, $this->valid_keys)){

                if(empty($item)){

                    $this->response(["message" => $message_empty_param . $key], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        });

        $username = $dati['username'];
        $password = $dati['password'];

        $pw_hash = hash("sha256", SALT . $password);

        $user = $this->user->find($username, $pw_hash);

        

        if(count($user) === 0)
            $this->response(["message" => $message_invalid_user], REST_Controller::HTTP_BAD_REQUEST);
        // Find and return a single record for a particular user.
        else {
            $this->session->set_userdata('user', $user[0]);
            
            $this->response(["message" => $message_user_authenticated], REST_Controller::HTTP_OK);
        }

        
    }


    public function logout_post(){
        // $this->some_model->update_user( ... );

        $message_user_logged_out = "Logout effettuato";
        $this->session->unset_userdata('user');
        session_write_close();
        $this->response(["message" => $message_user_logged_out], REST_Controller::HTTP_OK);
        
    }

}
