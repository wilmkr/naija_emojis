<?php

namespace Wilson\Source\Controllers;

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
            $app->halt(400, json_encode($e->getMessage()));
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

    // public static function updateEmoji(Slim $app, $position)
    // {
            // $emoji = Emoji::find($position);
            // $emoji->name = "Dipo Murray";
            // $user->save();
    // }

    /**
     * This function updates some fields of an empji record in the database
     * @param  Slim   $app      [Slim instance]
     * @param  [type] $position [the position of the emoji in the emojis table]
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
                    $app->halt(201, json_encode("Emoji successfully updated."));
                }
                else {
                    throw new Exception("Emoji update failed!");
                }
            }
            catch(Exception $e) {
                $app->halt(400, json_encode($e->getMessage()));
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