<?php

namespace Wilson\Source;

use Exception;
use Slim\Slim;
use Firebase\JWT\JWT;
use Wilson\Source\Authenticator;
use Firebase\JWT\ExpiredException;

class Authenticator
{
    protected static $responseMessage = [];

    /**
     * This function authenticates users before they can be granted access to protected endpoints.
     * @param  Slim   $app
     */
    public static function authenticate(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');
        $token = $app->request->headers->get('Authorization');

        if(is_null($token)) {
            self::$responseMessage = [
                'Status' => '401',
                'Message' => "You're not authorized to perform this action. Please login."
            ];

            $app->halt(401, json_encode(self::$responseMessage));
        }

        try {
            Configuration::load();
            $secretKey = getenv('JWT_KEY');

            $jwt = JWT::decode($token, $secretKey, ['HS256']);

            return json_encode($jwt->data);
       }
       catch(ExpiredException $e) {
            self::$responseMessage = [
                'Status' => '400',
                'Message' => "Your token has expired. Please login again."
            ];

            $app->halt(400, json_encode(self::$responseMessage));
       }
       catch(Exception $e) {
            self::$responseMessage = [
                'Status' => '400',
                'Message' => 'Exception: '.$e->getMessage()
            ];

            $app->halt(400, json_encode(self::$responseMessage));
       }
    }

    /**
     * This function checks if a parameter contained in a request has a valid value
     * @param  Slim   $app   [Slim instance]
     * @param  $param        [the parameter to check]
     * @return string
     */
    public static function checkParamValue(Slim $app, $param, $value)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        if(is_null($value) || empty($value)) {
           self::$responseMessage = [
                'Status' => '400',
                'Message' => "Missing or invalid parameter: $param"
            ];

           $app->halt(400, json_encode(self::$responseMessage));
        }
        else {
            return $value;
        }
    }
}