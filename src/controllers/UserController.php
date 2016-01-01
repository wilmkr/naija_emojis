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
    protected static $responseMessage = [];

    /**
     *  This function creates a new instance of a user
     * @param  Slim   $app
     */
    public static function register(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $user = new User();

        $user->username = Authenticator::checkParamValue($app, "username", $app->request->params('username'));
        $user->password = Authenticator::checkParamValue($app, "password", $app->request->params('password'));
        $user->name = Authenticator::checkParamValue($app, "name", $app->request->params('name'));

        $conn = User::getConnection();
        $stmt = $conn->query("SELECT * FROM users WHERE username='$user->username'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            self::$responseMessage = [
                'Status' => '400',
                'Message' => "The user '$user->username' already exists."
            ];

            $app->halt(400, json_encode(self::$responseMessage));
        }

        $rows = $user->save();

        if($rows > 0) {
            self::$responseMessage = [
                'Status' => '201',
                'Message' => 'User registration successful.'
            ];

            $app->halt(201, json_encode(self::$responseMessage));
        }
        else {
            self::$responseMessage = [
                'Status' => '400',
                'Message' => 'User registration failed!'
            ];

            $app->halt(400, json_encode(self::$responseMessage));
        }
    }

    /**
     *  This function logs in users by checking their login credentials against the database
     *  and generates a json web token (JWT) for valid users
     *  @param  Slim   $app [description]
     */
    public static function login(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $username = $app->request->params('username');
        $password = $app->request->params('password');

        $conn = User::getConnection();
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $stmt = $conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result)
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

            self::$responseMessage = [
                'Status' => '200',
                'Message' => 'Login successful',
                'Token' => $jwt
            ];

            return json_encode(self::$responseMessage);
        }
        else {
            self::$responseMessage = [
                'Status' => '404',
                'Message' => 'Login failed. Username or password is invalid.'
            ];

            $app->halt(404, json_encode(self::$responseMessage));
        }
    }

    /**
     * A simple logout function
     * @param  Slim   $app
     */
    public static function logout(Slim $app)
    {
        Authenticator::authenticate($app);

        self::$responseMessage = [
            'Status' => '200',
            'Message' => "You've logged out successfully."
        ];
        return json_encode(self::$responseMessage);
    }
}