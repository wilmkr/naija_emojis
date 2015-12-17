<?php

namespace Wilson\Source\Controllers;

use PDO;
use Slim\Slim;
use Exception;
use Firebase\JWT\JWT;
use Wilson\Source\Models\User;
use Wilson\Source\Authenticator;
use Wilson\Source\Configuration;

class UserController
{
    /**
     *  This function creates a new instance of a user
     * @param  Slim   $app
     */
    public static function register(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        try {
            $user = new User();

            $user->username = $app->request->params('username');
            $user->password = $app->request->params('password');
            $user->name = $app->request->params('name');

            $rows = $user->save();

            if($rows > 0) {
                $app->halt(201, json_encode("User registration successful."));
            }
            else {
                throw new Exception("User registration failed!");
            }
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     *  This function logs in users by checking their login credentials against the database
     *  and generates a json web token (JWT) for valid users
     * @param  Slim   $app [description]
     */
    public static function login(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $username = $app->request->params('username');
        $password = $app->request->params('password');

        try
        {
            $conn = User::getConnection();
            $sql = "SELECT * FROM users WHERE username='$username'";
            $stmt = $conn->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($password === $result['password'])
            {
                $token = [
                    'iat'  => time(),
                    'exp'  => time() + 3600,
                    'data' => [
                        'userID'   => $result['user_id'],
                        'username' => $username
                    ]
                ];

                Configuration::load();
                $secretKey = getenv('JWT_KEY');

                $jwt = JWT::encode($token, $secretKey);

                return json_encode($jwt);
            }
            else {
                $app->halt(404, json_encode("Login failed. Username or password is invalid."));
            }
        }
        catch(Exception $e) {
            $app->halt(400, json_encode($e->getMessage()));
        }
    }

    /**
     * A simple logout function
     * @param  Slim   $app
     */
    public static function logout(Slim $app)
    {
        $auth = Authenticator::authenticate($app);
        $auth = json_decode($auth);

        if(is_object($auth)) {
            return json_encode("You've logged out successfully.");
        }
    }
}