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

    public function testCreateEmoji()
    {
        $response = $this->client->post('/emojis', [
            'form_params' => [
                'name' => 'Surprise',
                'emoj_char' => ':-o',
                'category' => 'Facial',
                'keywords' => 'shocked, open-mouthed, startle',
                'created_by' => 'Gayle Smith'
            ],
            'headers' => [
               'Authorization' => $this->token
            ]
        ]);

        $expected = "Emoji creation successful.";
        $actual = json_decode($response->getBody());

        $this->assertEquals($expected, $actual);
        $this->assertEquals('201', $response->getStatusCode());
    }

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