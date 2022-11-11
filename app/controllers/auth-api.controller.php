<?php
require_once './app/helpers/auth-api.helper.php';
require_once './app/models/usuarios-model.php';
require_once './app/views/motor-api.view.php';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

class AuthApiController {

    private $model;
    private $view;
    private $helper;
    private $data;

    function __construct(){
        $this->model = new UsuariosModel();
        $this->view = new MotorApiView();
        $this->helper = new AuthApiHelper();
        $this->data = file_get_contents("php://input");
    }
    
    function getData(){
        return json_decode($this->data);
    }

    function getToken($params = null){
        $basic = $this->helper->getHeader();
        if(empty($basic)){
            $this->view->response('No autorizado', 401);
            return;
        }

        $basic = explode(" ", $basic);
        if($basic[0] != 'Basic'){
            $this->view->response('La autenticacion debe ser Basic', 401);
            return;
        }
        
        $userpass = base64_decode($basic[1]);
        $userpass = explode(":", $userpass); /**Obtengo el user y el pass*/
        $user = $userpass[0];
        $password = $userpass[1];
        $obtainedUser = $this->model->get($user);
         
        if(isset($obtainedUser->password) && password_verify($password,$obtainedUser->password)){
            
            $header = array(
                'alg' => 'HS256',
                'typ' => 'JWT'
            );

            $payload = array(
                'name' => $user,
                'exp' => time()+1000
            );
            
            $header = base64url_encode(json_encode($header));
            $payload = base64url_encode(json_encode($payload));
            $signature = hash_hmac('SHA256', "$header.$payload", "Clave1234", true);
            $signature = base64url_encode(json_encode($signature));
            $token = "$header.$payload.$signature";
            $this->view->response($token);
        } else {
            $this->view->response('No autorizado', 401);
        }
    }
}