<?php

namespace Wilson\Source\Controllers;

use Slim\Slim;
use Wilson\Source\Models\Emoji;
use Wilson\Source\Authenticator;

class EmojiController
{
    public static function createEmoji(Slim $app)
    {
        try {
            $app->response->headers->set('Content-Type', 'application/json');

            $emoji = new Emoji();

            $emoji->name = $app->request->params('name');
            $emoji->emoji_char = $app->request->params('emoji_char');
            $emoji->category = $app->request->params('category');
            $emoji->created_by = $app->request->params('created_by');

            $rows = $emoji->save();

            if($rows > 0) {
                return json_encode("Emoji creation successful.");
            }
            else {
                return json_encode("Emoji creation failed!");
            }
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getAllEmojis(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        return json_encode(Emoji::getAll());
    }

    public static function findEmoji(Slim $app, $position)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $obj = Emoji::find($position);

        return $obj->result;
    }

    // public static function updateEmoji()
    // {

    // }

    public static function deleteEmoji(Slim $app, $position)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $rows = Emoji::destroy($position);

        if($rows > 0) {
            return json_encode("Emoji $position deletion successful.");
        }
        else {
            return json_encode("Emoji $position deletion failed!");
        }
    }
}