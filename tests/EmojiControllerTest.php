<?php

namespace Wilson\tests;

use PDO;
use Dotenv\Dotenv;
use GuzzleHttp\Client;
use PHPUnit_Framework_TestCase;
use GuzzleHttp\Exception\ClientException;

class EmojiControllerTest extends PHPUnit_Framework_TestCase
{
    protected $token;
    protected $client;

    public function setup()
    {
        //$this->client = new Client(['base_uri' => 'https://w-naija-emoji.herokuapp.com']);
        $this->client = new Client(['base_uri' => 'http://checkpoint3.app']);

        $response = $this->client->post('/auth/login', ['query' => ['username' => 'Wil', 'password' => 'password']]);

        $this->token = json_decode($response->getBody())->Token;
    }

    /**
     * This method retrieves the id of the test emoji created. All tests make use of the test emoji created.
     */
    public function getEmojiID()
    {
        if (! getenv('APP_ENV') || getenv('APP_ENV')=="local") {
            $dotenv = new Dotenv(__DIR__ . '/../');
            $dotenv->load();
        }

        $host = getenv('DB_HOST');
        $db = getenv('DB_NAME');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $driver = getenv('DB_DRIVER');

        $conn = new PDO($driver.':host='.$host.';dbname='.$db, $username, $password);
        $stmt = $conn->query("SELECT * FROM emojis WHERE name='test_emoji'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['id'];
    }

    /**
     * Test if an emoji can actually be created. The created emoji is used for the other tests as well.
     */
    public function testCreateEmoji()
    {
        $response = $this->client->post('/emoji', [
            'form_params' => [
                'name' => 'test_emoji',
                'emoji_char' => '👨',
                'category' => 'Facial',
                'keywords' => 'shocked, open-mouthed, startle',
                'created_by' => 'Gayle Smith'
            ],
            'headers' => [
               'Authorization' => $this->token
            ]
        ]);

        $expected = '201';

        if($response->getStatusCode() == 200) {
            $expected = '200';
        }

        $this->assertEquals($expected, $response->getStatusCode());
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
        $id = self::getEmojiID();

        $response = $this->client->get("/emoji/$id");

        $this->assertObjectHasAttribute('name', json_decode($response->getBody()));
        $this->assertObjectHasAttribute('emoji_char', json_decode($response->getBody()));
        $this->assertObjectHasAttribute('category', json_decode($response->getBody()));
    }

    /**
     * Test if an emoji can be fully updated
     */
    public function testUpdateEmoji()
    {
       $id = self::getEmojiID();

        $response = $this->client->put("/emoji/$id", [
            'form_params' => [
                //'name' => str_shuffle('ThisIsARandomString'),
                'name' => 'test_emoji',
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
        $actual = json_decode($response->getBody())->Message;

        $this->assertEquals($expected, $actual);
        $this->assertEquals('200', $response->getStatusCode());
    }

    /**
     *  Test if an emoji can be deleted
     */
    public function testDeleteEmoji()
    {
        $id = self::getEmojiID();

        $response = $this->client->delete("/emoji/$id", [
            'headers' => [
               'Authorization' => $this->token
            ]
        ]);

        $expected = "Emoji with ID $id deleted successfully.";
        $actual = json_decode($response->getBody())->Message;

        $this->assertEquals($expected, $actual);
    }
}