<?php

class AuthApiHelper {
    
    function getToken(){
        $auth = $this->getHeader();
        
        $auth = explode(" ", $auth);
        if($auth[0]!='Bearer' || count($auth) != 2){
            return array();
        }
        $token = explode(".", $auth[1]); /**Baerer $header.$payload... */
        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];
        
        $new_signature = hash_hmac('SHA256', "$header.$payload", "Clave1234", true);
        $new_signature = base64url_encode($new_signature);
        if($signature!=$new_signature)
            return array();

        $payload = json_decode(base64_decode($payload));
        if(!isset($payload->exp) || $payload->exp<time())
            return array();
        
        return $payload;
    }

    function getHeader(){
        $header = "";
        if(isset($_SERVER['HTTP_AUTHORIZATION'])){
            $header = $_SERVER['HTTP_AUTHORIZATION'];
        }
        if(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])){
            $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        return $header;
    }

    function isLoggedIn(){
        $token = $this->getToken();
        if(isset($token->name)){
            return true;
        } else {
            return false;
        }
    }
}