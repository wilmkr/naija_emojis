<?php

namespace Wilson\Source\Controllers;

use PDO;
use Slim\Slim;
use Firebase\JWT\JWT;
use Wilson\Source\Models\User;
use Wilson\Source\Configuration;

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

    public static function login(Slim $app)
    {
        try
        {
            $app->response->headers->set('Content-Type', 'application/json');

            $username = $app->request->params('username');
            $password = $app->request->params('password');

            $conn = User::getConnection();
            $sql = "SELECT * FROM users WHERE username='$username'";
            $stmt = $conn->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($password === $result['password'])
            {
                // generate token
                $token = [
                    'iat'  => time(),
                    'exp'  => time() + 300,
                    'data' => [
                        'userId'   => $result['user_id'],
                        'username' => $username,
                    ]
                ];

                Configuration::load();
                $secretKey = getenv('JWT_KEY');

                $jwt = JWT::encode($token, $secretKey);

                // $unencodedArray = ['jwt' => $jwt];
                // echo json_encode($unencodedArray);
                //
                echo $jwt;
            }

            return json_encode($result);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    // public static function logout(Slim $app)
    // {

    // }
}