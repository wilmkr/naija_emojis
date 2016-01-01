<?php

namespace Wilson\Source\Controllers;

use Exception;
use Slim\Slim;
use Wilson\Source\Models\Emoji;
use Wilson\Source\Authenticator;

class EmojiController
{
    protected static $responseMessage = [];

    /**
     * This method creates an instance of an emoji and stores it in the database.
     * @param  Slim   $app      [Slim instance]
     */
    public static function createEmoji(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        Authenticator::authenticate($app);

        $emoji = new Emoji();

        $emoji->name = Authenticator::checkParamValue($app, "name", $app->request->params('name'));
        $emoji->emoji_char = Authenticator::checkParamValue($app, "emoji_char", $app->request->params('emoji_char'));
        $emoji->category = Authenticator::checkParamValue($app, "category", $app->request->params('category'));
        $emoji->keywords = Authenticator::checkParamValue($app, "keywords", $app->request->params('keywords'));
        $emoji->created_by = Authenticator::checkParamValue($app, "created_by", $app->request->params('created_by'));

        $rows = $emoji->save();

        if($rows > 0) {
            self::$responseMessage = [
                'Status' => '201',
                'Message' => 'Emoji creation successful.'
            ];

            $app->halt(201, json_encode(self::$responseMessage));
        }
        else {
            self::$responseMessage = [
                'Status' => '400',
                'Message' => 'Emoji creation failed!'
            ];

            $app->halt(400, json_encode(self::$responseMessage));
        }
    }

    /**
     * This method fetches all the emojis in the database
     * @param  Slim   $app      [Slim instance]
     * @return json encoded string
     */
    public static function getAllEmojis(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $returnedValue = Emoji::getAll();

        if(is_array($returnedValue)) {
            return json_encode($returnedValue);
        }

        if(is_string($returnedValue)) {
            self::$responseMessage = [
                'Status' => '204',
                'Message' => $returnedValue
            ];

            $app->halt(204, json_encode(self::$responseMessage));
        }
    }

    /**
     * This method is used to search for a particular emoji
     * @param  Slim   $app      [Slim instance]
     * @param  $id              [the id of the emoji in the emojis table]
     * @return json encoded string
     */
    public static function findEmoji(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $returnedValue = Emoji::findById($id);

        if(is_object($returnedValue)) {
            // $x = (array) json_decode($returnedValue->result);
            // echo $x['keywords'];

            return $returnedValue->result;
        }

        if(is_string($returnedValue)) {
            self::$responseMessage = [
                'Status' => '404',
                'Message' => $returnedValue
            ];

            $app->halt(404, json_encode(self::$responseMessage));
        }
    }

    /**
     * This function fully updates an emoji in the database i.e. updates all fields of an emoji record
     * @param  Slim   $app      [Slim instance]
     * @param  $id              [the id of the emoji in the emojis table]
     */
    public static function updateEmoji(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        Authenticator::authenticate($app);

        $emoji = Emoji::findById($id);

        if(is_string($emoji)) {
            self::$responseMessage = [
                'Status' => '404',
                'Message' => $emoji
            ];

            $app->halt(404, json_encode(self::$responseMessage));
        }

        $emoji->name = Authenticator::checkParamValue($app, "name", $app->request->params('name'));
        $emoji->emoji_char = Authenticator::checkParamValue($app, "emoji_char", $app->request->params('emoji_char'));
        $emoji->category = Authenticator::checkParamValue($app, "category", $app->request->params('category'));
        $emoji->keywords = Authenticator::checkParamValue($app, "keywords", $app->request->params('keywords'));
        $emoji->created_by = Authenticator::checkParamValue($app, "created_by", $app->request->params('created_by'));

        $rows = $emoji->save();

        if($rows > 0) {
            self::$responseMessage = [
                'Status' => '200',
                'Message' => 'Emoji successfully updated.'
            ];

            $app->halt(200, json_encode(self::$responseMessage));
        }
        else {
            self::$responseMessage = [
                'Status' => '401',
                'Message' => 'Emoji full update failed!'
            ];

            $app->halt(401, json_encode(self::$responseMessage));
        }
    }

    /**
     * This function updates some fields of an empji record in the database
     * @param  Slim   $app      [Slim instance]
     * @param  $id              [the id of the emoji in the emojis table]
     */
    public static function patchEmoji(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        Authenticator::authenticate($app);

        $emoji = Emoji::findById($id);

        if(is_string($emoji)) {
            self::$responseMessage = [
                'Status' => '404',
                'Message' => $emoji
            ];

            $app->halt(404, json_encode(self::$responseMessage));
        }

        $params = $app->request->patch();

        foreach ($params as $key => $value) {
            $emoji->$key = $value;
        }

        $rows = $emoji->save();

        if($rows > 0) {
            self::$responseMessage = [
                'Status' => '200',
                'Message' => 'Emoji successfully updated.'
            ];

            $app->halt(200, json_encode(self::$responseMessage));
        }
        else {
            self::$responseMessage = [
                'Status' => '400',
                'Message' => 'Emoji partial update failed!'
            ];

            $app->halt(400, json_encode(self::$responseMessage));
        }
    }

    /**
     * This method is used to delete an emoji from the database
     * @param  Slim   $app      [Slim instance]
     * @param  $id              [the id of the emoji in the emojis table]
     * @return json encoded string
     */
    public static function deleteEmoji(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        Authenticator::authenticate($app);

        $rows = Emoji::destroyById($id);

        if(is_string($rows)) {
            self::$responseMessage = [
                'Status' => '404',
                'Message' => $rows
            ];

            $app->halt(404, json_encode(self::$responseMessage));
        }

        if($rows > 0) {
            self::$responseMessage = [
                'Status' => '200',
                'Message' => "Emoji with ID $id deleted successfully."
            ];

            $app->halt(200, json_encode(self::$responseMessage));
        }
        else {
            self::$responseMessage = [
                'Status' => '400',
                'Message' => "Failed to delete Emoji with ID $id."
            ];

            $app->halt(400, json_encode(self::$responseMessage));
        }
    }
}