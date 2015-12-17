<?php

namespace Wilson\tests;

use GuzzleHttp\Client;
use PHPUnit_Framework_TestCase;

class UserControllerTest extends PHPUnit_Framework_TestCase
{
    protected $client;
    protected $token;

    public function setup()
    {
        $this->client = new Client(['base_uri' => 'http://checkpoint3.app']);

        $response = $this->client->post('/auth/login', ['query' => ['username' => 'Wil', 'password' => 'password']]);

        $this->token = json_decode($response->getBody());
    }

    public function testRegister()
    {
        $response = $this->client->post('/register', [
            'query' => [
                'username' => 'testuser',
                'password' => 'abcdef',
                'name' => 'Gayle Smith'
            ]
        ]);

        $expected = "User registration successful.";
        $actual = json_decode($response->getBody());

        $this->assertEquals($expected, $actual);
    }

    // public function testLogin()
    // {

    // }

    // public function testLogout()
    // {

    // }
}