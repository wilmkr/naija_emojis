<?php

namespace Wilson\tests;

use GuzzleHttp\Client;
use PHPUnit_Framework_TestCase;
use GuzzleHttp\Exception\ClientException;

class UserControllerTest extends PHPUnit_Framework_TestCase
{
    protected $client;
    protected $token;

    public function setup()
    {
        //$this->client = new Client(['base_uri' => 'https://w-naija-emoji.herokuapp.com']);
        $this->client = new Client(['base_uri' => 'http://checkpoint3.app']);

        $response = $this->client->post('/auth/login', ['query' => ['username' => 'Wil', 'password' => 'password']]);

        $this->token = json_decode($response->getBody())->Token;
    }

    /**
     * Test if a user can actually be registered or not
     */
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

        if($response->getStatusCode() == 200){
            $expected = "The user 'testuser' already exists.";
        }

        $actual = json_decode($response->getBody())->Message;

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test login module, and that a token is actually returned upon successful user login
     */
    public function testLogin()
    {
        $response = $this->client->post('/auth/login', ['query' => ['username' => 'Wil', 'password' => 'password']]);

        $this->assertInternalType('string', json_decode($response->getBody())->Message);
        $this->assertEquals('200', $response->getStatusCode());
    }

    /**
     * Test logout functionality
     */
    public function testLogout()
    {
        $response = $this->client->get('/auth/logout', ['headers' => ['Authorization' => $this->token]]);

        $expected = "You've logged out successfully.";
        $actual = json_decode($response->getBody())->Message;

        $this->assertEquals($expected, $actual);
    }

    // public function testLogoutWithoutToken()
    // {
    //     $response = "";
    //     try {
    //         $response = $this->client->get('/auth/logout');

    //         $expected = "You've logged out successfully.";
    //         $actual = json_decode($response->getBody())->Message;

    //         $this->assertEquals($expected, $actual);
    //     }
    //     catch(ClientException $ce) {

    //     }
    // }
}