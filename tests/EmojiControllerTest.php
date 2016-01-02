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
        //$this->client = new Client(['base_uri' => 'https://w-naija-emoji.herokuapp.com']);
        $this->client = new Client(['base_uri' => 'http://checkpoint3.app']);

        $response = $this->client->post('/auth/login', ['query' => ['username' => 'Wil', 'password' => 'password']]);

        $this->token = json_decode($response->getBody())->Token;
    }

    /**
     * Test if an emoji can actually be created
     */
    public function testCreateEmoji()
    {
        $response = $this->client->post('/emoji', [
            'form_params' => [
                'name' => 'Surprise',
                'emoji_char' => '😀',
                'category' => 'Facial',
                'keywords' => 'shocked, open-mouthed, startle',
                'created_by' => 'Gayle Smith'
            ],
            'headers' => [
               'Authorization' => $this->token
            ]
        ]);

        $this->assertEquals('201', $response->getStatusCode());
    }

    /**
     * Test if all emojis can be retrieved from the database
     */
    public function testGetAllEmojis()
    {
        $response = $this->client->get('/emojis');

        $this->assertInternalType('array', json_decode($response->getBody()));
        $this->assertEquals('200', $response->getStatusCode());
    }

    /**
     * Test if a user can search for a particular emoji in the database
     */
    public function testFindEmoji()
    {
        $response = $this->client->get('/emoji/1');

        $this->assertObjectHasAttribute('name', json_decode($response->getBody()));
        $this->assertObjectHasAttribute('emoji_char', json_decode($response->getBody()));
        $this->assertObjectHasAttribute('category', json_decode($response->getBody()));
    }

    /**
     * Test if an emoji can be fully updated
     */
    public function testUpdateEmoji()
    {
        $response = $this->client->put('/emoji/1', [
            'form_params' => [
                'name' => str_shuffle('ThisIsARandomString'),
                'emoji_char' => '😇',
                'category' => 'Facial',
                'keywords' => 'shocked, open-mouthed, startle',
                'created_by' => 'Gayle Smith'
            ],
            'headers' => [
               'Authorization' => $this->token
            ]
        ]);

        $expected = "Emoji successfully updated.";
        $actual = json_decode($response->getBody());

        $this->assertEquals($expected, $actual);
        $this->assertEquals('200', $response->getStatusCode());
    }

    /**
     *  Test if an emoji can be deleted
     */
    public function testDeleteEmoji()
    {
        $position = '1';
        $response = $this->client->delete('/emoji/'.$position, [
            'headers' => [
               'Authorization' => $this->token
            ]
        ]);

        $expected = "Emoji $position deleted successfully.";
        $actual = json_decode($response->getBody());

        $this->assertEquals($expected, $actual);
    }
}