<?php

namespace Wilson\tests;

use GuzzleHttp\Client;
use PHPUnit_Framework_TestCase;

class EmojiControllerTest extends PHPUnit_Framework_TestCase
{
    protected $client;
    protected $token;

    public function setup()
    {
        $this->client = new Client(['base_uri' => 'http://checkpoint3.app']);

        $response = $this->client->post('/auth/login', ['query' => ['username' => 'Wil', 'password' => 'password']]);

        $this->token = json_decode($response->getBody());
    }

    // public function testCreateEmoji()
    // {

    // }

    // public function testGetAllEmojis()
    // {

    // }

    // public function testFindEmoji()
    // {

    // }

    // public function testUpdateEmoji()
    // {

    // }

    // public function testCheckParamValue()
    // {

    // }

    // public function testPatchEmoji()
    // {

    // }

    // public function testDeleteEmoji()
    // {

    // }
}