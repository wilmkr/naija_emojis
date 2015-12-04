<?php

namespace Wilson\Source;

use Exception;
use Slim\Slim;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class Authenticator
{
    public static function authenticate(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');
        $token = $app->request->headers->get('Authorization');

        if(is_null($token)) {
            $app->halt(401, json_encode("You're not authorized to perform this action. Please login."));
        }

        try {
            Configuration::load();
            $secretKey = getenv('JWT_KEY');

            $jwt = JWT::decode($token, $secretKey, ['HS256']);

            return json_encode($jwt->data);
       }
       catch(ExpiredException $e) {
            $app->halt(400, json_encode("Your token has expired. Please login again."));
       }
       catch(Exception $e) {
            return json_encode($e->getMessage());
       }
    }
}