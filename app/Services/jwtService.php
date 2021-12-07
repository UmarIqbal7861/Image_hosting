<?php
namespace App\Services;

use Firebase\JWT\JWT;

use Firebase\JWT\Key;

use Illuminate\Support\Facades\Config;


class jwtService{    
    public function get_jwt()    
    {       
        $key = Config::get('Constant.Key');
        $payload = array(
            "iss" => "localhost",
            "aud" => "users",
            "iat" => time(),
            "exp" => time()+1800,
            "nbf" => 1357000000
        );

        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;    
    }
}