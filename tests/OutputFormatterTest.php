<?php

namespace Wilson\tests;

use GuzzleHttp\Client;
use PHPUnit_Framework_TestCase;
use GuzzleHttp\Exception\ClientException;

class OutputFormatterTest extends PHPUnit_Framework_TestCase
{
    // protected $client;
    // protected $token;

    // public function setup()
    // {
    //     $this->client = new Client(['base_uri' => 'http://checkpoint3.app']);

    //     $response = $this->client->post('/auth/login', ['query' => ['username' => 'Wil', 'password' => 'password']]);

    //     $this->token = json_decode($response->getBody())->Token;
    // }

    // public function testFormatOutput()
    // {
    //     $response = $this->client->get('/auth/logout', ['headers' => ['Authorization' => $this->token]]);

    //     $object = json_decode($response->getBody());

    //     $this->assertObjectHasAttribute('Status', $object);
    //     $this->assertObjectHasAttribute('Message', $object);
    // }

}