<?php

namespace Wilson\Source\Controllers;

use Exception;
use Slim\Slim;
use Wilson\Source\Models\Emoji;
use Wilson\Source\Authenticator;

class EmojiController
{
    /**
     * This method creates an instance of an emoji and stores it in the database.
     * @param  Slim   $app      [Slim instance]
     */
    public static function createEmoji(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        try {
            $auth = Authenticator::authenticate($app);
            $auth = json_decode($auth);

            if(is_object($auth)) {

                $emoji = new Emoji();

                $emoji->name = $app->request->params('name');
                $emoji->emoji_char = $app->request->params('emoji_char');
                $emoji->category = $app->request->params('category');
                $emoji->created_by = $app->request->params('created_by');

                $rows = $emoji->save();

                if($rows > 0) {
                    $app->halt(201, json_encode("Emoji creation successful."));
                }
                else {
                    throw new Exception("Emoji creation failed!");
                }
            }
            else {
                $app->halt(401, json_encode("You're not authorized to perform this action. Please login."));
            }
        }
        catch(Exception $e) {
            return $e->getMessage();
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
            $app->halt(204, json_encode($returnedValue));
        }
    }

    /**
     * This method is used to search for a particular emoji
     * @param  Slim   $app      [Slim instance]
     * @param  $position        [the position of the emoji in the emojis table]
     * @return json encoded string
     */
    public static function findEmoji(Slim $app, $position)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $returnedValue = Emoji::find($position);

        if(is_object($returnedValue)) {
            return $returnedValue->result;
        }

        if(is_string($returnedValue)) {
            $app->halt(404, json_encode($returnedValue));
        }
    }

    /**
     * This function fully updates an emoji in the database i.e. updates all fields of an emoji record
     * @param  Slim   $app      [Slim instance]
     * @param  $position        [the position of the emoji in the emojis table]
     */
    public static function updateEmoji(Slim $app, $position)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $auth = Authenticator::authenticate($app);
        $auth = json_decode($auth);

        if(is_object($auth))
        {
            $emoji = Emoji::find($position);

            $emoji->name = self::checkParamValue($app, "name", $app->request->params('name'));
            $emoji->emoji_char = self::checkParamValue($app, "emoji_char", $app->request->params('emoji_char'));
            $emoji->category = self::checkParamValue($app, "category", $app->request->params('category'));
            $emoji->keywords = self::checkParamValue($app, "keywords", $app->request->params('keywords'));
            $emoji->created_by = self::checkParamValue($app, "created_by", $app->request->params('created_by'));

            $rows = $emoji->save();

            if($rows > 0) {
                $app->halt(200, json_encode("Emoji successfully updated."));
            }
            else {
                $app->halt(401, json_encode("Emoji full update failed!"));
            }
        }
    }

    /**
     * This function checks if a parameter contained in a request has a valid value
     * @param  Slim   $app   [Slim instance]
     * @param  $param [the parameter to check]
     * @return string
     */
    public static function checkParamValue(Slim $app, $param, $value)
    {
        $app->response->headers->set('Content-Type', 'application/json');
        echo "value: $value";

        if(is_null($value) || empty($value)) {
           $app->halt(401, json_encode("Cannot update. Missing or invalid parameter: $param"));
        }
        else {
            return $value;
        }
    }

    /**
     * This function updates some fields of an empji record in the database
     * @param  Slim   $app      [Slim instance]
     * @param  $position        [the position of the emoji in the emojis table]
     */
    public static function patchEmoji(Slim $app, $position)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $auth = Authenticator::authenticate($app);
        $auth = json_decode($auth);

        if(is_object($auth))
        {
            try {
                $emoji = Emoji::find($position);

                $params = $app->request->patch();

                foreach ($params as $key => $value) {
                    $emoji->$key = $value;
                }

                $rows = $emoji->save();

                if($rows > 0) {
                    $app->halt(200, json_encode("Emoji successfully updated."));
                }
                else {
                    throw new Exception("Emoji partial update failed!");
                }
            }
            catch(Exception $e) {
                return $e->getMessage();
            }
        }
    }

    /**
     * This method is used to delete an emoji from the database
     * @param  Slim   $app      [Slim instance]
     * @param  $position        [the position of the emoji in the emojis table]
     * @return json encoded string
     */
    public static function deleteEmoji(Slim $app, $position)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $auth = Authenticator::authenticate($app);
        $auth = json_decode($auth);

        if(is_object($auth)) {
            $rows = Emoji::destroy($position);

            if($rows > 0) {
                return json_encode("Emoji $position deletion successful.");
            }
            else {
                return json_encode("Emoji $position deletion failed!");
            }
        }
        else {
            $app->halt(401, json_encode("You're not authorized to perform this action. Please login."));
        }
    }
}