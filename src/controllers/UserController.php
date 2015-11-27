<?php

namespace Wilson\Source\Controllers;

use Slim\Slim;
use Wilson\Source\Models\User;

class UserController
{
    public static function register(Slim $app)
    {
        try {
            $app->response->headers->set('Content-Type', 'application/json');

            $user = new User();

            $user->username = $app->request->params('username');
            $user->password = $app->request->params('password');
            $user->name = $app->request->params('name');

            $rows = $user->save();

            if($rows > 0) {
                return json_encode("User registration successful.");
            }
            else {
                return json_encode("User registration failed!");
            }
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}